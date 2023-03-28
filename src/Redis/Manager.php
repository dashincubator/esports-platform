<?php declare(strict_types=1);

namespace System\Redis;

use Contracts\Redis\Manager as Contract;
use Exception;

class Manager implements Contract
{

    // 'client name' => Redis/Predis/PhpRedis Instance
    private $clients = [];


    public function __construct(array $clients)
    {
        if (!array_key_exists('default', $clients)) {
            throw new Exception("Redis Manager Requires A 'default' Client");
        }

        $this->clients = $clients;
    }


    public function getClient(string $name = 'default')
    {
        if (!array_key_exists($name, $this->clients)) {
            throw new Exception("Redis Manager Does Not Have A Client With The Name '{$name}'");
        }

        return $this->clients[$name];
    }
}
