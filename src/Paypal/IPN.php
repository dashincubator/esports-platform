<?php

namespace System\Paypal;

use Contracts\Paypal\IPN as Contract;
use Exception;

class IPN implements Contract
{

    private const PRODUCTION_URL = 'https://ipnpb.paypal.com/cgi-bin/webscr';

    private const RESPONSE_VALID = 'VERIFIED';

    private const SANDBOX_URL = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';


    private $headers = [
        'User-Agent: PHP-IPN-Verification-Script',
        'Connection: Close'
    ];

    private $sandbox = false;


    private function buildVerificationFields() : string
    {
        $input = [];
        $raw = explode('&', file_get_contents('php://input'));

        foreach ($raw as $keyvalue) {
            $keyvalue = explode('=', $keyvalue);

            if (count($keyvalue) == 2) {
                if (substr_count($keyvalue[1], '+') === 1) {
                    $keyvalue[1] = str_replace('+', '%2B', $keyvalue[1]);
                }

                $input[$keyvalue[0]] = urldecode($keyvalue[1]);
            }
        }

        $fields = 'cmd=_notify-validate';

        foreach ($input as $key => $value) {
            $fields .= "&{$key}=" . urlencode($value);
        }

        return $fields;
    }


    public function isValid() : bool
    {
        $ch = curl_init(($this->sandbox ? self::SANDBOX_URL : self::PRODUCTION_URL));

        curl_setopt_array($ch, [
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1, // Remove
            CURLOPT_POSTFIELDS => $this->buildVerificationFields(),
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO => __DIR__ . "/cert/cacert.pem",
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HTTPHEADER => $this->headers
        ]);

        $response = curl_exec($ch);

        if (!$response) {
            $code = curl_errno($ch);
            $str = curl_error($ch);

            curl_close($ch);

            throw new Exception("cURL error: [{$code}] {$str}");
        }

        $status = curl_getinfo($ch)['http_code'];

        if ($status !== 200) {
            throw new Exception("PayPal Responded With HTTP Status Code {$status} - Response: {$response}");
        }

        curl_close($ch);

        return (bool) ($response == self::RESPONSE_VALID);
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
