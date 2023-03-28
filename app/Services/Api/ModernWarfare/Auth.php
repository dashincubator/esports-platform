<?php

/**
 *------------------------------------------------------------------------------
 *
 *  @see ModernWarfare.php
 *
 */

namespace App\Services\Api\ModernWarfare;

use Contracts\Cache\Cache;
use Exception;

class Auth
{

    private $accounts = [];

    private $cache;

    private $factory;

    // Default Headers
    // - Authorization Cookie Appended On Login
    private $headers = [
        'Content-Type: application/json',
        'Cookie: new_SiteId=cod;ACT_SSO_LOCALE=en_US;country=US;XSRF-TOKEN=68e8b62e-1d9d-4ce1-b93f-cbe5ff31a041;',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0'
    ];

    private $key = 'ModernWarfareAPI';

    private $tokens;


    public function __construct(Cache $cache, Factory $factory, array $accounts = [])
    {
        $this->accounts = $accounts;
        $this->cache = $cache;
        $this->factory = $factory;
    }


    public function cachebust() : void
    {
        $this->cache->delete($this->key);
    }


    public function getToken() : AuthToken
    {
        if (!count($this->accounts)) {
            throw new Exception('MW API Auth Error: Modern Warfare API Needs A Minimum Of 1 Account To Call The API');
        }

        if (is_null($this->tokens)) {
            $this->tokens = $this->login();
            $this->updateCache();
        }

        if ($this->tokens->isEmpty()) {
            return $this->getToken();
        }

        return $this->tokens->getToken();
    }


    private function login() : AuthTokens
    {
        return $this->cache->get($this->key, function() {
            $tokens = $this->factory->createAuthTokens();

            foreach ($this->accounts as $email => $password) {
                $response = $this->post(compact('email', 'password'), $this->registeredDeviceHeaders(), 'https://profile.callofduty.com/cod/mapp/login');

                if (!($response['success'] ?? false)) {
                    continue;
                }

                $headers = $this->headers;

                foreach ($headers as $index => $header) {
                    $header = strtolower($header);

                    if (mb_strpos($header, 'cookie:') !== false){
                        $headers[$index] .= "rtkn={$response['rtkn']};ACT_SSO_COOKIE={$response['s_ACT_SSO_COOKIE']};atkn={$response['atkn']};";
                    }
                }

                $tokens->add($this->factory->createAuthToken($email, $headers));
            }

            return $tokens;
        });
    }


    private function post(array $fields, array $headers, string $url) : array
    {
        // Mimic RPS Delay Used In Github Repo
        sleep(2);

        $ch = curl_init();
        $fields = json_encode($fields);

        curl_setopt_array($ch, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => array_merge(
                $this->headers,
                ['Content-Length: ' . strlen($fields)],
                $headers
            ),
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $fields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL            => $url,
        ]);

        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }


    private function registeredDeviceHeaders() : array
    {
        $id = hash('md5', uniqid());
        $response = $this->post(['deviceId' => $id], [], 'https://profile.callofduty.com/cod/mapp/registerDevice');
        $token = $response['data']['authHeader'] ?? false;

        if (!$token) {
            throw new Exception('Modern Warfare Device Registration Failed - ' . json_encode(compact('id', 'response')));
        }

        return [
            "Authorization: bearer {$token}",
            "x_cod_device_id: {$id}"
        ];
    }


    public function restricted(string $email) : void
    {
        $this->tokens->restricted($email);
        $this->updateCache();
    }


    public function updateCache() : void
    {
        $this->cache->set($this->key, $this->tokens);
    }
}
