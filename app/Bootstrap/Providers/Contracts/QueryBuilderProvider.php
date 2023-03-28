<?php

namespace App\Bootstrap\Providers\Contracts;

use App\Bootstrap\Providers\AbstractProvider;
use Contracts\Cache\Cache;
use Contracts\IO\FileSystem;
use Contracts\QueryBuilder\Driver;

class QueryBuilderProvider extends AbstractProvider
{

    private const CACHE_KEY = 'identifiers';


    public function boot() : void
    {
        $this->container->singleton(Driver::class, null, [true, $this->loadIdentifiersFromCache()]);
    }


    private function buildIdentifiers() : array
    {
        $filesystem = $this->container->resolve(FileSystem::class);
        $identifiers = [];

        $glob = ['*'];

        while (count($files = $filesystem->glob($this->buildPath($glob)))) {
            foreach ($files as $file) {
                $identifiers = array_merge($identifiers, $this->container->resolve($this->normalize($file))->getIdentifiers());
            }

            $glob[] = '*';
        }

        return array_unique($identifiers);
    }


    private function buildPath(array $glob) : string
    {
        return $this->config->get('paths.datasource') . '/' . implode('/', $glob) . '/Mapper.php';
    }


    private function loadIdentifiersFromCache() : array
    {
        $cache = $this->container->resolve(Cache::class);

        if ($this->config->get('app.debug')) {
            $cache->delete(self::CACHE_KEY);
        }

        return $cache->get(self::CACHE_KEY, function() use ($cache) {
            $data = $this->buildIdentifiers();

            $cache->set(self::CACHE_KEY, $data);

            return $data;
        });
    }


    private function normalize(string $file) : string
    {
        // Strip Unneccessary Path Details
        $classname = substr($file, strpos($file, 'app/'));
        $classname = rtrim($classname, '.php');

        // Prep For Container Resolution
        $classname = '/' . ucfirst($classname);
        $classname = str_replace('/', '\\', $classname);

        return $classname;
    }
}
