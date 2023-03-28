<?php declare(strict_types=1);

namespace System\Database\Adapters\Pdo\Mysql;

use Contracts\Database\{Driver as Contract, Server};

class Driver implements Contract
{

    public function buildDsn(Server $server, array $options = []) : string
    {
        $dsn = [
            "mysql:dbname={$server->getName()}",
            "charset={$server->getCharset()}"
        ];
        $socket = $server->getSocket();

        if ($socket) {
            $dsn[] = "unix_socket={$socket}";
        }
        else {
            $dsn = array_merge($dsn, [
                "host={$server->getHost()}",
                "port={$server->getPort()}"
            ]);
        }

        return implode(';', $dsn) . ';';
    }
}
