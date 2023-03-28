<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Simple Square Checkout
 *  - https://developer.squareup.com/explorer/square/checkout-api/create-checkout
 *  - https://developer.squareup.com/docs/testing/test-values
 *
 */

namespace System\Payment\Square;

use Contracts\UUID\RandomGenerator;
use Exception;

class Checkout
{

    private const PRODUCTION_URL = 'https://connect.squareup.com/v2/locations/%s/checkouts';

    private const SANDBOX_URL = 'https://connect.squareupsandbox.com/v2/locations/%s/checkouts';


    private $headers = [
        'Connection: close',
        'Content-Type: application/json',
        'Square-Version: 2020-08-26'
    ];

    private $location;

    private $sandbox = false;


    public function __construct(string $location, string $token)
    {
        $this->headers[] = "Authorization: Bearer {$token}";
        $this->location = $location;
    }


    public function create(array $data) : array
    {
        $ch = curl_init(
            sprintf(($this->sandbox ? self::SANDBOX_URL : self::PRODUCTION_URL), $this->location)
        );

        curl_setopt_array($ch, [
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HTTPHEADER => $this->headers
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch)['http_code'];

        if ($status !== 200) {
            throw new Exception("Square Responded With HTTP Status Code {$status} - Response: {$response}");
        }

        curl_close($ch);

        return json_decode($response, true) ?? [];
    }


    public function useProduction() : self
    {
        $this->sandbox = false;

        return $this;
    }


    public function useSandbox() : self
    {
        $this->sandbox = true;

        return $this;
    }
}
