<?php declare(strict_types=1);

namespace System\Http;

use Contracts\Collections\Associative as Collection;
use Contracts\Container\Container;
use Contracts\Http\{
    Factory as Contract,
    Request, RequestFile, RequestHeaders,
    Response, ResponseCollection, ResponseCookie, ResponseHeaders
};

class Factory implements Contract
{

    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function createRequest(
        array $cookies = [],
        array $env = [],
        array $files = [],
        array $post = [],
        array $query = [],
        array $server = []
    ) : Request
    {
        return $this->container->resolve(Request::class, [
            $this->container->resolve(Collection::class),
            $this->container->resolve(Collection::class, [$cookies]),
            $this->container->resolve(Collection::class),
            $this->container->resolve(Collection::class, [$env]),
            $this->container->resolve(Collection::class, [$files]),
            $this->container->resolve(RequestHeaders::class, [$server]),
            $this->container->resolve(Collection::class),
            $this->container->resolve(Collection::class, [$post]),
            $this->container->resolve(Collection::class),
            $this->container->resolve(Collection::class, [$query]),
            $this->container->resolve(Collection::class, [$server])
        ]);
    }


    public function createRequestFromGlobals() : Request
    {
        $cookies = $_COOKIE ?? [];
        $env = $_ENV ?? [];
        $files = [];
        $post = $_POST ?? [];
        $query = $_GET ?? [];
        $server = $_SERVER ?? [];

        foreach (($_FILES ?? []) as $key => $value) {
            if ($value['size'] === 0) {
                continue;
            }

            $files[$key] = $this->container->resolve(RequestFile::class, [
                $value['tmp_name'],
                $value['name'],
                $value['size'],
                $value['type'],
                $value['error']
            ]);
        }

        // Handle Missing 'CONTENT_TYPE' && 'CONTENT_LENGTH' Bug
        if (array_key_exists('HTTP_CONTENT_LENGTH', $server)) {
            $server['CONTENT_LENGTH'] = $server['HTTP_CONTENT_LENGTH'];
        }

        if (array_key_exists('HTTP_CONTENT_TYPE', $server)) {
            $server['CONTENT_TYPE'] = $server['HTTP_CONTENT_TYPE'];
        }

        return $this->createRequest($cookies, $env, $files, $post, $query, $server);
    }


    public function createResponse(string $content, array $headers = [], int $status = 200) : Response
    {
        return $this->container->resolve(Response::class, [
            $this->container->resolve(ResponseHeaders::class, [$headers]),
            $content,
            $status
        ]);
    }


    public function createResponseCollection() : ResponseCollection
    {
        return $this->container->resolve(ResponseCollection::class);
    }


    public function createResponseCookie(
        string $name,
        string $value,
        int $expiration,
        string $path = '/',
        string $domain = '',
        bool $isSecure = true,
        bool $isHttpOnly = true,
        string $samesite = 'Lax'
    ) : ResponseCookie
    {
        return $this->container->resolve(ResponseCookie::class, [$name, $value, $expiration, $path, $domain, $isSecure, $isHttpOnly, $samesite]);
    }
}
