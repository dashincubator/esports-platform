<?php declare(strict_types=1);

namespace System\Database;

use Contracts\Database\Server as Contract;

class Server implements Contract
{

    // Server Character Set
    private $charset;

    // Host Of This Server
    private $host;

    // Database Name
    private $name;

    // Database Password
    private $password;

    // Database Port
    private $port;

    // Database Socket
    private $socket;

    // Database User
    private $user;


    public function __construct(
        string $charset,
        string $host,
        string $name,
        string $password,
        int $port,
        string $socket,
        string $user
    ) {
        $this->charset = $charset;
        $this->host = $host;
        $this->name = $name;
        $this->password = $password;
        $this->port = $port;
        $this->socket = $socket;
        $this->user = $user;
    }


    public function getCharset() : string
    {
        return $this->charset;
    }


    public function getHost() : string
    {
        return $this->host;
    }


    public function getName() : string
    {
        return $this->name;
    }


    public function getPassword() : string
    {
        return $this->password;
    }


    public function getPort() : int
    {
        return $this->port;
    }


    public function getSocket() : string
    {
        return $this->socket;
    }


    public function getUser() : string
    {
        return $this->user;
    }
}
