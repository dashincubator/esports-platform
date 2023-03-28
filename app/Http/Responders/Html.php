<?php

namespace App\Http\Responders;

use Contracts\Http\{Factory as HttpFactory, Response};
use Contracts\View\{Engine, Factory as ViewFactory};

class Html
{

    private $engine;

    private $factory;


    public function __construct(Engine $engine, HttpFactory $http, ViewFactory $view)
    {
        $this->engine = $engine;
        $this->factory = compact('http', 'view');
    }


    public function render(string $path, array $data = [], int $status = 200) : Response
    {
        return $this->factory['http']->createResponse(
            $this->engine->render($this->factory['view']->createView($path, $data)),
            ['Content-Type' => 'text/html; charset=utf-8'],
            $status
        );
    }


    public function render404(array $data = []) : Response
    {
        return $this->render('@pages/error/404', $data, 404);
    }
}
