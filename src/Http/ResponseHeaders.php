<?php declare(strict_types=1);

namespace System\Http;

use Contracts\Http\{ResponseCookie, ResponseHeaders as Contract};
use Contracts\Collections\Associative as Collection;

class ResponseHeaders extends AbstractHeaders implements Contract
{

    // Cookie Collection
    private $cookies = [];


    public function __construct(Collection $headers, array $values = [])
    {
        parent::__construct($headers);

        foreach ($values as $name => $value) {
            $this->set(strtoupper($name), $value);
        }
    }


    public function deleteCookie(ResponseCookie $cookie) : void
    {
        $cookie->setExpiration(0);
        $cookie->setValue('');

        $this->set($cookie);
    }
 

    public function getCookies(bool $includeDeletedCookies = false) : array
    {
        $cookies = [];

        foreach ($this->cookies as $domain => $cookiesByDomain) {
            foreach ($cookiesByDomain as $path => $cookiesByPath) {
                foreach ($cookiesByPath as $name => $cookie) {
                    // Only Include Active Cookies
                    if ($includeDeletedCookies || $cookie->getExpiration() >= time()) {
                        $cookies[] = $cookie;
                    }
                }
            }
        }

        return $cookies;
    }


    public function setCookie(ResponseCookie $cookie) : void
    {
        $this->cookies[$cookie->getDomain()][$cookie->getPath()][$cookie->getName()] = $cookie;
    }
}
