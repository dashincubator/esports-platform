<?php

namespace App\Services\Api\ModernWarfare;

class AuthToken
{

    private $email;

    private $headers;


    public function __construct(string $email, array $headers)
    {
        $this->email = $email;
        $this->headers = $headers;
    }


    public function getEmail() : string
    {
        return $this->email;
    }


    public function getHeaders() : array
    {
        return $this->headers;
    }
}
