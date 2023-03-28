<?php

namespace App\Services\Api\ModernWarfare;

use Exception;

class AuthTokens
{

    private $counter = 0;

    private $restricted = [];

    private $unrestricted = [];


    public function __clone()
    {
        foreach ($this->restricted as $index => $token) {
            $this->restricted[$index] = clone $token;
        }

        foreach ($this->unrestricted as $index => $token) {
            $this->unrestricted[$index] = clone $token;
        }
    }


    public function add(AuthToken $token) : void
    {
        $this->unrestricted[] = $token;
    }


    public function getToken() : AuthToken
    {
        if (!count($this->unrestricted)) {
            throw new Exception('MW API AuthTokens Error: Modern Warfare API Needs A Minimum Of 1 Account To Call The API');
        }

        $this->counter++;

        if (!array_key_exists($this->counter, $this->unrestricted)) {
            $this->counter = 0;
        }

        return $this->unrestricted[$this->counter];
    }


    public function isEmpty() : bool
    {
        return count($this->restricted) + count($this->unrestricted) === 0;
    }


    public function restricted(string $email) : void
    {
        foreach ($this->unrestricted as $index => $token) {
            if ($token->getEmail() !== $email) {
                continue;
            }

            $this->restricted[] = $token;

            unset($this->unrestricted[$index]);

            break;
        }

        $this->unrestricted = array_values($this->unrestricted);
    }
}
