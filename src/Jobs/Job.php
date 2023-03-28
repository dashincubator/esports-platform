<?php declare(strict_types=1);

namespace System\Jobs;

use Contracts\Jobs\Job as Contract;

class Job implements Contract
{

    private $classname;

    private $method;

    private $parameters;

    private $useLock;


    public function __construct(string $classname, string $method, array $parameters, bool $useLock = false)
    {
        $this->classname = $classname;
        $this->method = $method;
        $this->parameters = $parameters;
        $this->useLock = $useLock;
    }


    public function getClassName() : string
    {
        return $this->classname;
    }


    public function getMethod() : string
    {
        return $this->method;
    }


    public function getParameters() : array
    {
        return $this->parameters;
    }


    public function toArray() : array
    {
        return [
            'class' => $this->classname,
            'method' => $this->method,
            'parameters' => $this->parameters,
            'useLock' => $this->useLock
        ];
    }


    public function useLock() : bool
    {
        return $this->useLock;
    }
}
