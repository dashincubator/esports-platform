<?php declare(strict_types=1);

namespace System\View\Extensions;

use Contracts\View\{Engine, Factory};
use Contracts\View\Extensions\Render as Contract;

class Render implements Contract
{

    // Data Passed From Root View File
    private $data;

    // View Renderer/Engine
    private $engine;

    // View Component Factory
    private $factory;


    public function __construct(Engine $engine, Factory $factory, array $data = [])
    {
        $this->data = $data;
        $this->engine = $engine;
        $this->factory = $factory;
    }


    public function __invoke(string $path, array $data = []) : string
    {
        return $this->engine->render(
            $this->factory->createTemplate($path, array_replace($this->data, $data))
        );
    }
}
