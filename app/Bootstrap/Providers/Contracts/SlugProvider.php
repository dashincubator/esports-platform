<?php

namespace App\Bootstrap\Providers\Contracts;

use Contracts\Cache\Cache;
use Contracts\Collections\Associative as Collection;
use Contracts\IO\FileSystem;
use Contracts\Slug\Generator;
use App\Bootstrap\Providers\AbstractProvider;

class SlugProvider extends AbstractProvider
{

    private const CACHE_KEY = 'rules';


    public function register() : void
    {
        $this->registerGeneratorBinding();
    }


    private function loadRulesFromCache() : Collection
    {
        $cache = $this->container->resolve(Cache::class);

        if ($this->config->get('app.debug')) {
            $cache->delete(self::CACHE_KEY);
        }

        $data = $cache->get(self::CACHE_KEY, function() use ($cache) {
            $data = $this->loadRulesFromDirectory();

            $cache->set(self::CACHE_KEY, $data);

            return $data;
        });

        return $this->container->resolve(Collection::class, [$data]);
    }


    private function loadRulesFromDirectory() : array
    {
        $filesystem = $this->container->resolve(FileSystem::class);
        $files = $filesystem->getFiles($this->config->get('contracts.slug.rules.directory'));
        $rules = [];

        foreach ($files as $file) {
            $rules[$filesystem->getFileName($file)] = $filesystem->require($file);
        }

        return $rules;
    }


    private function registerGeneratorBinding() : void
    {
        $concrete = $this->container->getAlias(Generator::class);

        $this->container->singleton(Generator::class, function() use ($concrete) {
            $generator = $this->container->resolve($concrete, [$this->loadRulesFromCache()]);

            $generator->addRule("'", "");
            $generator->addRule(",", "");

            return $generator;
        });
    }
}
