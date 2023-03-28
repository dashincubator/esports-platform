<?php declare(strict_types=1);

namespace System\View;

use Contracts\View\{Factory as Contract, View};
use Contracts\View\Extensions\{Data, Extension, Render, Section, Template};
use Contracts\Container\Container;
use Contracts\Support\Arrayable;
use Closure;
use Exception;

class Factory implements Contract
{

    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function createData(array $data) : Data
    {
        return $this->container->resolve(Data::Class, [$data]);
    }


    public function createExtension(string $classname) : Extension
    {
        return $this->container->resolve(Extension::Class, [
            $this->container->resolve($classname)
        ]);
    }


    public function createRender(array $data = []) : Render
    {
        return $this->container->resolve(Render::class, [$data]);
    }


    public function createSection() : Section
    {
        return $this->container->resolve(Section::class);
    }


    public function createTemplate(string $path = '', array $data = []) : Template
    {
        return $this->container->resolve(Template::Class, [$path, $data]);
    }


    public function createView(string $path = '', array $data = []) : View
    {
        return $this->container->resolve(View::Class, [$path, $data]);
    }
}
