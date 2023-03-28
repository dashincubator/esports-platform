<?php declare(strict_types=1);

namespace System\Http;

use Contracts\Http\{Request as Contract, RequestHeaders};
use Contracts\Collections\Associative as Collection;
use Exception;

class Request implements Contract
{

    // Valid HTTP Methods
    private const METHODS = [
        'connect' => 'CONNECT',
        'delete' => 'DELETE',
        'get' => 'GET',
        'head' => 'HEAD',
        'options' => 'OPTIONS',
        'patch' => 'PATCH',
        'post' => 'POST',
        'put' => 'PUT',
        'trace' => 'TRACE'
    ];

    // Collection Of Variables
    private $attributes;

    // Client IP Address
    private $clientIp;

    // Collection Of Cookies
    private $cookies;

    // Collection Of DELETE Parameters
    private $delete;

    // Request Domain
    private $domain;

    // Collection Of ENVIRONMENT Parameters
    private $environment;

    // Collection Of FILES Parameters
    private $files;

    // Full Url
    private $fullUrl;

    // Collection Of Headers
    private $headers;

    // Server Host
    private $host;

    // Method Used In Request
    private $method;

    // Collection Of PATCH Parameters
    private $patch;

    // Path Of The Request Without GET Parameters
    private $path;

    // Collection Of POST Parameters
    private $post;

    // Previous Url
    private $previousUrl;

    // List Of Proxy IP's
    private $proxies = [];

    // Proxy Header Names
    private $proxyHeaderNames = [
        'forwarded' => 'FORWARDED',
        'client-ip' => 'X_FORWARDED_FOR',
        'client-host' => 'X_FORWARDED_HOST',
        'client-port' => 'X_FORWARDED_PORT',
        'client-proto' => 'X_FORWARDED_PROTO'
    ];

    // Collection Of PUT Parameters
    private $put;

    // Collection Of GET Parameters
    private $query;

    // Raw Body Of The Request
    private $rawBody;

    // Referer
    private $referer;

    // HTTP Or HTTPS
    private $secure;

    // Collection Of SERVER Parameters
    private $server;


    public function __clone()
    {
        $this->attributes = clone $attributes;
        $this->cookies = clone $cookies;
        $this->delete = clone $delete;
        $this->environment = clone $environment;
        $this->files = clone $files;
        $this->headers = clone $headers;
        $this->patch = clone $patch;
        $this->post = clone $post;
        $this->put = clone $put;
        $this->query = clone $query;
        $this->server = clone $server;
    }


    public function __construct(
        Collection $attributes,
        Collection $cookies,
        Collection $delete,
        Collection $environment,
        Collection $files,
        RequestHeaders $headers,
        Collection $patch,
        Collection $post,
        Collection $put,
        Collection $query,
        Collection $server
    ) {
        $this->attributes = $attributes;
        $this->cookies = $cookies;
        $this->delete = $delete;
        $this->environment = $environment;
        $this->files = $files;
        $this->headers = $headers;
        $this->patch = $patch;
        $this->post = $post;
        $this->put = $put;
        $this->query = $query;
        $this->server = $server;

        // PHP Doesn't Use PUT/PATCH/DELETE Globals So We Manually Read From The
        // Input Stream To Grab Any Form Data That Has Been Provided.
        if (
            in_array($this->getMethod(), [self::METHODS['put'], self::METHODS['patch'], self::METHODS['delete']])
            && mb_strpos($this->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded') === 0
        ) {
            parse_str($this->getRawBody(), $collection);

            switch ($this->getMethod()) {
                case self::METHODS['put']:
                    $this->put->replace($collection);
                    break;
                case self::METHODS['patch']:
                    $this->patch->replace($collection);
                    break;
                case self::METHODS['delete']:
                    $this->delete->replace($collection);
                    break;
            }
        }
    }


    public function getAttributes() : Collection
    {
        return $this->attributes;
    }


    public function getClientIp() : ?string
    {
        if (is_null($this->clientIp)) {
            $ips = [];

            // RFC 7239
            if ($this->headers->has($this->proxyHeaderNames['forwarded'])) {
                $header = $this->headers->get($this->proxyHeaderNames['forwarded']);
                preg_match_all("/for=(?:\"?\[?)([a-z0-9:\.\-\/_]*)/", $header, $matches);
                $ips = $matches[1];
            }
            elseif ($this->headers->has($this->proxyHeaderNames['client-ip'])) {
                $ips = explode(',', $this->headers->get($this->proxyHeaderNames['client-ip']));
                $ips = array_map('trim', $ips);
            }

            $ips[] = $this->server->get('REMOTE_ADDR');
            $fallback = [$ips[0]];

            // Remove Invalid IP's
            foreach ($ips as $index => $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                    unset($ips[$index]);
                }

                if (in_array($ip, $this->proxies)) {
                    unset($ips[$index]);
                }
            }

            $this->clientIp = count($ips) === 0 ? $fallback : array_reverse($ips);
        }

        return $this->clientIp[0];
    }


    public function getCookies() : Collection
    {
        return $this->cookies;
    }


    public function getDelete() : Collection
    {
        return $this->delete;
    }


    public function getDomain() : string
    {
        if (is_null($this->domain)) {
            $isSecure = $this->isSecure();
            $port = $this->getPort();
            $protocol = strtolower($this->server->get('SERVER_PROTOCOL'));

            // Prepend A Colon If The Port Is Non-Standard
            if ((!$isSecure && $port != '80') || ($isSecure && $port != '443')) {
                $port = ":$port";
            }
            else {
                $port = '';
            }

            $this->domain = mb_substr($protocol, 0, mb_strpos($protocol, '/')) . ($isSecure ? 's' : '') . '://' . $this->getHost();
        }

        return $this->domain;
    }


    public function getEnvironment() : Collection
    {
        return $this->environment;
    }


    public function getFiles() : Collection
    {
        return $this->files;
    }


    public function getFullUrl() : string
    {
        if (is_null($this->fullUrl)) {
            $isSecure = $this->isSecure();
            $port = $this->getPort();

            // Prepend A Colon If The Port Is Non-Standard
            if ((!$isSecure && $port != '80') || ($isSecure && $port != '443')) {
                $port = ":$port";
            }
            else {
                $port = '';
            }

            $this->fullUrl = $this->getDomain() . $port . $this->server->get('REQUEST_URI');
        }

        return $this->fullUrl;
    }


    public function getHeaders() : RequestHeaders
    {
        return $this->headers;
    }


    public function getHost() : string
    {
        if (is_null($this->host)) {
            $host = null;

            if ($this->isUsingProxy() && $this->headers->has($this->proxyHeaderNames['client-host'])) {
                $host = trim(end(
                    explode(',', $this->headers->get($this->proxyHeaderNames['client-host']))
                ));
            }

            if (is_null($host)) {
                $host = $this->headers->get('HOST');
            }

            if (is_null($host)) {
                $host = $this->server->get('SERVER_NAME');
            }

            // Return An Empty String By Default So We Can Manipulate It Later
            if (is_null($host)) {
                $host = $this->server->get('SERVER_ADDR', '');
            }

            // Remove Port Number
            $host = strtolower(preg_replace("/:\d+$/", '', trim($host)));

            // Check For Forbidden Characters
            // Credit: Symfony HTTPFoundation
            if (!empty($host) && !empty(preg_replace("/(?:^\[)?[a-zA-Z0-9-:\]_]+\.?/", '', $host))) {
                throw new Exception("Invalid HTTP Request Host '{$host}'");
            }

            $this->host = $host;
        }

        return $this->host;
    }


    public function getInput() : Collection
    {
        foreach (['delete', 'patch', 'post', 'put'] as $method) {
            if (!$this->isMethod($method)) {
                continue;
            }

            return $this->{'get' . ucfirst($method)}();
        }

        return $this->getQuery();
    }


    public function getJsonBody() : array
    {
        $json = json_decode($this->getRawBody(), true);

        if (is_null($json)) {
            throw new Exception('HTTP Request Could Not Decode Body As JSON');
        }

        return $json;
    }


    public function getMethod() : string
    {
        if (is_null($this->method)) {
            $method = strtoupper($this->server->get('REQUEST_METHOD', self::METHODS['get']));

            if ($method === self::METHODS['post']) {
                if ($override = $this->server->get('X-HTTP-METHOD-OVERRIDE', false)) {
                    $method = $override;
                }
            }

            if (!in_array($method, array_values(self::METHODS))) {
                throw new Exception("Invalid HTTP Request Method '{$method}'");
            }

            $this->method = $method;
        }

        return $this->method;
    }


    public function getPassword() : ?string
    {
        return $this->server->get('PHP_AUTH_PW');
    }


    public function getPatch() : Collection
    {
        return $this->patch;
    }


    public function getPath() : string
    {
        if (is_null($this->path)) {
            $uri = $this->server->get('REQUEST_URI');

            if (empty($uri)) {
                $this->path = '/';
            }
            else {
                $this->path = explode('?', $uri)[0];
            }
        }

        return $this->path;
    }


    public function getPort() : int
    {
        if ($this->isUsingProxy()) {
            if ($this->server->has($this->proxyHeaderNames['client-port'])) {
                return (int) $this->server->get($this->proxyHeaderNames['client-port']);
            }
            elseif ($this->server->get($this->proxyHeaderNames['client-proto']) === 'https') {
                return 443;
            }
        }

        return (int) $this->server->get('SERVER_PORT');
    }


    public function getPost() : Collection
    {
        return $this->post;
    }


    public function getPreviousUrl(bool $fallBackToReferer = true) : string
    {
        if (!is_null($this->previousUrl)) {
            return $this->previousUrl;
        }

        if ($fallBackToReferer) {
            return $this->headers->get('REFERER', '');
        }

        return '';
    }


    public function getPut() : Collection
    {
        return $this->put;
    }


    public function getQuery() : Collection
    {
        return $this->query;
    }


    public function getRawBody() : string
    {
        if (is_null($this->rawBody)) {
            $this->rawBody = file_get_contents('php://input');
        }

        return $this->rawBody;
    }


    public function getReferer() : string
    {
        return $this->headers->get('REFERER', '');
    }


    public function getServer() : Collection
    {
        return $this->server;
    }


    public function getUser() : ?string
    {
        return $this->server->get('PHP_AUTH_USER');
    }


    public function isAjax() : bool
    {
        return strtolower($this->headers->get('X_REQUESTED_WITH') ?? '') === 'xmlhttprequest';
    }


    public function isJson() : bool
    {
        return preg_match("/application\/json/i", $this->headers->get('CONTENT_TYPE', '')) === 1;
    }


    public function isMethod(string $method) : bool
    {
        return self::METHODS[strtolower($method)] === $this->getMethod();
    }


    public function isSecure() : bool
    {
        if (is_null($this->secure)) {
            if ($this->isUsingProxy() && $this->headers->has($this->proxyHeaderNames['client-proto'])) {
                $proto = explode(',', $this->headers->get($this->proxyHeaderNames['client-proto']));

                $this->secure = count($proto) > 0 && in_array(strtolower($proto[0]), ['https', 'ssl', 'on']);
            }
            else {
                $this->secure = $this->server->has('HTTPS') && $this->server->get('HTTPS') !== 'off';
            }
        }

        return $this->secure;
    }


    public function isUsingProxy() : bool
    {
        return in_array($this->server->get('REMOTE_ADDR'), $this->proxies);
    }


    public function setProxies($proxies) : void
    {
        $this->proxies = (array) $proxies;
    }


    public function setPreviousUrl(string $previousUrl) : void
    {
        $this->previousUrl = $previousUrl;
    }


    public function setProxyHeaderName(string $name, $value) : void
    {
        $this->proxyHeaderNames[$name] = $value;
    }
}
