<?php

namespace App\Http\Middleware\Boot;

use Closure;
use Contracts\Encryption\Encrypter;
use Contracts\Http\{Middleware as Contract, Request, Response};

class CookieEncryption implements Contract
{

    private $encrypter;

    private $skip;

    private $use;


    public function __construct(Encrypter $encrypter, array $skip, bool $use)
    {
        $this->encrypter = $encrypter;
        $this->skip = $skip;
        $this->use = $use;
    }


    private function decrypt(Request $request) : Request
    {
        if (!$this->use) {
            return $request;
        }

        $cookies = $request->getCookies();

        foreach ($cookies as $key => $value) {
            if ($this->skip($key)) {
                continue;
            }

            $cookies->set($key, is_array($value) ? $this->decryptArray($value) : $this->decryptValue($value));
        }

        return $request;
    }


    private function decryptArray(array $values)
    {
        $decrypted = [];

        foreach ($values as $key => $value) {
            $decrypted[$key] = $this->decryptValue($value);
        }

        return $decrypted;
    }


    private function decryptValue($value)
    {
        return is_string($value) ? $this->encrypter->decrypt($value) : '';
    }


    private function encrypt(Response $response) : Response
    {
        if (!$this->use) {
            return $response;
        }
 
        foreach ($response->getHeaders()->getCookies(true) as $cookie) {
            if ($this->skip($cookie->getName())) {
                continue;
            }

            $cookie->setValue($this->encrypter->encrypt($cookie->getValue()));
        }

        return $response;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        return $this->encrypt($next($this->decrypt($request)));
    }


    private function skip(string $name) : bool
    {
        return in_array($name, $this->skip);
    }
}
