<?php declare(strict_types=1);

namespace System\Http;

use Contracts\Http\{Response as Contract, ResponseHeaders};
use DateTime;
use Exception;

class Response implements Contract
{

    // List Of Valid Protocol Versions
    private const PROTOCOL_VERSIONS = [
        '1.0',
        '1.1',
        '2.0',
        '2'
    ];

    // HTTP Status Codes/Messages
    private const STATUS = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required'
    ];


    // Content Of This Response
    private $content;

    // Response Headers
    private $headers;

    // Status Code Of This Response
    private $statusCode;

    // Status Message Of This Response
    private $statusText;

    // HTTP Version Of This Response
    private $version;


    public function __construct(
        ResponseHeaders $headers,
        $content = '',
        int $status = 200,
        string $version = '1.1'
    ) {
        $this->headers = $headers;

        $this->setContent($content);
        $this->setProtocolVersion($version);
        $this->setStatus($status);
    }


    public function getContent() : string
    {
        return $this->content;
    }


    public function getHeaders() : ResponseHeaders
    {
        return $this->headers;
    }


    public function getProtocolVersion() : string
    {
        return $this->version;
    }


    public function getStatusCode() : int
    {
        return $this->statusCode;
    }


    public function getStatusText() : string
    {
        return $this->statusText;
    }


    public function headersAlreadySent() : bool
    {
        return headers_sent();
    }


    public function send() : void
    {
        $this->sendHeaders();
        $this->sendContent();

        // Prevents Any Potential Output Buffering
        flush();
    }


    private function sendHeaders()
    {
        if ($this->headersAlreadySent()) {
            return;
        }

        header("HTTP/{$this->version} {$this->statusCode} {$this->statusText}", true, $this->statusCode);

        // Send Headers
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                header("{$name}:{$value}", false, $this->statusCode);
            }
        }

        // Send Cookies
        foreach ($this->headers->getCookies(true) as $cookie) {
            setcookie(
                $cookie->getName(),
                $cookie->getValue(),
                [
                    'domain' => $cookie->getDomain(),
                    'expires' => $cookie->getExpiration(),
                    'httponly' => $cookie->isHttpOnly(),
                    'path' => $cookie->getPath(),
                    'secure' => $cookie->isSecure(),
                    'samesite' => $cookie->getSameSite()
                ]
            );
        }
    }


    private function sendContent()
    {
        if ($this->headersAlreadySent()) {
            return;
        }

        echo $this->content;
    }


    public function setContent($content) : void
    {
        $this->content = $content;
    }


    public function setExpiration(DateTime $expiration) : void
    {
        $this->headers->set('Expires', $expiration->format('r'));
    }


    public function setProtocolVersion(string $version) : void
    {
        if (!in_array($version, self::PROTOCOL_VERSIONS)) {
            throw new Exception("HTTP Response Received Invalid Protocol Version '{$version}'");
        }

        $this->version = $version;
    }


    public function setStatus(int $code, string $text = '') : void
    {
        if (!array_key_exists($code, self::STATUS)) {
            throw new Exception("HTTP Response Received Invalid Status Code '{$code}'");
        }

        $this->statusCode = $code;
        $this->statusText = ($text === '' ? self::STATUS[$code] : $text);
    }
}
