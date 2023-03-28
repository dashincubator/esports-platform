<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Http Request
 *
 */

namespace Contracts\Http;

use Contracts\Collections\Associative as Collection;

interface Request
{

    /**
     *  @return Collection Route Variables Or Other MetaData
     */
    public function getAttributes() : Collection;


    /**
     *  @return string Client IP
     */
    public function getClientIp() : ?string;


    /**
     *  @return Collection '$_COOKIES'
     */
    public function getCookies() : Collection;


    /**
     *  @return Collection '$_DELETE'
     */
    public function getDelete() : Collection;


    /**
     *  @return Collection '$_ENV'
     */
    public function getEnvironment() : Collection;


    /**
     *  @return Collection '$_FILES'
     */
    public function getFiles() : Collection;


    /**
     *  @return string The Full Url
     */
    public function getFullUrl() : string;


    /**
     *  @return RequestHeaders Request Headers
     */
    public function getHeaders() : RequestHeaders;


    /**
     *  @return string The Host
     */
    public function getHost() : string;


    /**
     *  @return Collection Returns Collection Based On HTTP Method
     */
    public function getInput() : Collection;


    /**
     *  @return array The Raw Body Decoded To Json
     */
    public function getJsonBody() : array;


    /**
     *  @return string Method Used In The Request
     */
    public function getMethod() : string;


    /**
     *  @return string|null The Auth Password
     */
    public function getPassword() : ?string;


    /**
     *  @return Collection '$_PATCH'
     */
    public function getPatch() : Collection;


    /**
     *  @return string Path Of The Request Without GET Parameters
     */
    public function getPath() : string;


    /**
     *  Gets The Port Number
     *
     *  @return int The Port Number
     */
    public function getPort() : int;


    /**
     *  @return Collection '$_POST'
     */
    public function getPost() : Collection;


    /**
     *  @param string
     */
    public function getPreviousUrl(bool $fallBackToReferer = true) : string;


    /**
     *  @return Collection '$_PUT'
     */
    public function getPut() : Collection;


    /**
     *  @return Collection '$_GET'
     */
    public function getQuery() : Collection;


    /**
     *  @return string The Raw Body
     */
    public function getRawBody() : string;


    /**
     *  @return string Previous Url Provided By HTTP Referer Header
     */
    public function getReferer() : string;


    /**
     *  @return Collection '$_SERVER'
     */
    public function getServer() : Collection;


    /**
     *  @return string|null The Auth User
     */
    public function getUser() : ?string;


    /**
     *  @return bool True If The Request Was Made Using Ajax, Otherwise False
     */
    public function isAjax() : bool;


    /**
     *  @return bool True If Request Body Was JSON, Otherwise False
     */
    public function isJson() : bool;


    /**
     *  @return bool True If Method Matches, Otherwise False
     */
    public function isMethod(string $method) : bool;


    /**
     *  @return bool True If 'HTTPS', Otherwise False
     */
    public function isSecure() : bool;


    /**
     *  @return bool True If Client IP Matches Proxy, Otherwise False
     */
    public function isUsingProxy() : bool;


    /**
     *  Set List Of Trusted Proxies
     *
     *  @param array|string $proxies List Of Proxies
     */
    public function setProxies($proxies) : void;


    /**
     *  @param string $previousUrl
     */
    public function setPreviousUrl(string $previousUrl) : void;


    /**
     *  @param string $name Name Of The Proxy Header
     *  @param mixed $value Value Of The Header
     */
    public function setProxyHeaderName(string $name, $value) : void;
}
