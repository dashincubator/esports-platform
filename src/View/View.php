<?php declare(strict_types=1);

namespace System\View;

use Closure;
use Contracts\View\View as Contract;

class View implements Contract
{

    // Data Passed To View File
    private $data = [];

    // View Path ( Excluding Directory )
    private $path = '';


    public function __construct(string $path = '', array $data = [])
    {
        $this->setData($data);
        $this->setPath($path);
    }


    public function getData() : array
    {
        return $this->data;
    }


    public function getDirectory() : string
    {
        $path = explode('/', $this->getPath());

        array_pop($path);

        return implode('/', $path);
    }


    public function getPath() : string
    {
        return $this->path;
    }


    protected function setData(array $data) : void
    {
        $this->data = array_merge($this->data, $data);
    }


    protected function setPath(string $path) : void
    {
        $this->path = $path;
    }
}
