<?php

namespace App\Http\Responders;

use Contracts\Http\{Factory, Response};
use Contracts\Routing\Router;

class Redirect
{

    private $factory;

    private $router;


    public function __construct(Factory $factory, Router $router)
    {
        $this->factory = $factory;
        $this->router = $router;
    }


    public function render(string $url, array $data = [], int $status = 302) : Response
    {
        if ($this->router->has($url)) {
            $url = $this->router->uri($url, $data);
        }

        return $this->factory->createResponse('', ['Location' => $url], $status);
    }


    public function render404(string $url, array $data = []) : Response
    {
        return $this->render($url, $data, 404);
    }
}
