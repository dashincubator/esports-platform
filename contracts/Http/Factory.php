<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  HTTP Factory
 *
 */

namespace Contracts\Http;

interface Factory
{

    /**
     *  Create HTTP Request Using Variables Provided
     *
     *  @param array $cookies Global $_COOKIE Data, Mock Data, Etc.
     *  @param array $env     ^ For $_ENV
     *  @param array $files   ^ For $_FILES
     *  @param array $post    ^ For $_POST
     *  @param array $query   ^ For $_GET
     *  @param array $server
     *  @return Request          [description]
     */
    public function createRequest(
        array $cookies = [],
        array $env = [],
        array $files = [],
        array $post = [],
        array $query = [],
        array $server = []
    ) : Request;

    public function createRequestFromGlobals() : Request;


    /**
     *  Create HTTP Response
     *
     *  @param string $content
     *  @param array $headers
     *  @param integer $status
     *  @return Response
     */
    public function createResponse(string $content, array $headers = [], int $status = 200) : Response;


    /**
     *  @return ResponseCollection
     */
    public function createResponseCollection() : ResponseCollection;


    /**
     *  Create New Cookie
     *
     *  @param string $name
     *  @param string $value
     *  @param mixed $expiration
     *  @param string $path
     *  @param string $domain
     *  @param boolean $isSecure
     *  @param boolean $isHttpOnly
     *  @return ResponseCookie
     */
    public function createResponseCookie(
        string $name,
        string $value,
        int $expiration,
        string $path = '/',
        string $domain = '',
        bool $isSecure = false,
        bool $isHttpOnly = true,
        string $samesite = 'Strict'
    ) : ResponseCookie;
}
