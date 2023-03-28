<?php

namespace App\Bootstrap\Providers\Contracts;

use App\Organization;
use App\Bootstrap\Providers\AbstractProvider;
use Contracts\View\Engine;

class ViewProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerEngineBinding();
    }


    private function registerEngineBinding() : void
    {
        $concrete = $this->container->getAlias(Engine::class);

        $this->container->singleton(Engine::class, function() use ($concrete) {
            $engine = $this->container->resolve($concrete);

            foreach ($this->config->get('contracts.view.extensions') as $key => $classname) {
                $engine->addExtension($key, $classname);
            }

            foreach ($this->config->get("contracts.view.paths") as $key => $path) {
                $engine->addPath($key, $path);
            }

            return $engine;
        });
    }
}
