<?php

namespace App\View\Extensions;

use Contracts\Routing\Router;

class Route
{

    private $name;

    private $router;


    public function __construct(Router $router, string $name = '')
    {
        $this->name = $name;
        $this->router = $router;
    }


    public function activate(string $name) : string
    {
        return $this->is($name) ? '--active' : '';
    }


    public function getName() : string
    { 
        return $this->name;
    }


    public function has(string $name) : bool
    {
        return mb_strpos($this->name, $name) !== false;
    }


    public function in(array $names) : bool
    {
        return in_array($this->name, $names);
    }


    public function is(string $name) : bool
    {
        return $this->name === $name;
    }


    public function startsWith(string $name) : bool
    {
        return (mb_substr($this->name, 0, count($name)) === $name);
    }


    public function uri(string $name, array $variables = []) : string
    {
        return $this->router->uri($name, $variables);
    }
}
