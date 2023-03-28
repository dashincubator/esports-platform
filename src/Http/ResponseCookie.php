<?php declare(strict_types=1);

namespace System\Http;

use Contracts\Http\ResponseCookie as Contract;
use DateTime;

class ResponseCookie implements Contract
{

    // Name Of The Cookie
    private $name;

    // Value Of The Cookie
    private $value;

    // Expiration Timestamp Of The Cookie
    private $expiration;

    // Path The Cookie Is Valid On
    private $path;

    // Domain The Cookie Is On
    private $domain;

    // Whether Or Not Cookie Is Set On HTTPS
    private $isSecure;

    // Whether Or Not Cookie Is HTTP Only
    private $isHttpOnly;

    // Cookie Policy
    private $samesite;


    public function __construct(
        string $name,
        string $value,
        int $expiration,
        string $path = '/',
        string $domain = '',
        bool $isSecure = true,
        bool $isHttpOnly = true,
        string $samesite = 'Lax'
    ) {
        $this->expiration = $expiration;
        $this->name = $name;
        $this->value = $value;
        $this->path = $path;
        $this->domain = $domain;
        $this->samesite = $samesite;
        $this->isSecure = $isSecure;
        $this->isHttpOnly = $isHttpOnly;
    }


    public function getDomain() : string
    {
        return $this->domain;
    }


    public function getExpiration() : int
    {
        return $this->expiration;
    }


    public function getName() : string
    {
        return $this->name;
    }


    public function getPath() : string
    {
        return $this->path;
    }


    public function getSameSite() : string
    {
        return $this->samesite;
    }


    public function getValue() : string
    {
        return $this->value;
    }


    public function isHttpOnly() : bool
    {
        return $this->isHttpOnly;
    }


    public function isSecure() : bool
    {
        return $this->isSecure;
    }


    public function setDomain(string $domain) : void
    {
        $this->domain = $domain;
    }


    public function setExpiration(int $expiration) : void
    {
        $this->expiration = $expiration;
    }


    public function setHttpOnly(bool $isHttpOnly) : void
    {
        $this->isHttpOnly = $isHttpOnly;
    }


    public function setName(string $name) : void
    {
        $this->name = $name;
    }


    public function setPath(string $path) : void
    {
        $this->path = $path;
    }


    public function setSameSite(string $samesite) : void
    {
        $this->samesite = $samesite;
    }


    public function setSecure(bool $isSecure) : void
    {
        $this->isSecure = $isSecure;
    }


    public function setValue($value) : void
    {
        $this->value = $value;
    }
}
