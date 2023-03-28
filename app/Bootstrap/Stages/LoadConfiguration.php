<?php

namespace App\Bootstrap\Stages;

use Contracts\Bootstrap\Application;
use Contracts\Cache\File as Cache;
use Contracts\Configuration\Configuration;
use Contracts\Container\Container;
use Contracts\Environment\Environment;
use Contracts\IO\FileSystem;
use Contracts\Time\Time;
use Closure;
use Exception;

class LoadConfiguration
{

    private const CACHE_KEY = 'config';


    private $config;

    private $container;

    private $time;


    public function __construct(Configuration $config, Container $container, Time $time)
    {
        $this->config = $config;
        $this->container = $container;
        $this->time = $time;
    }


    public function handle(Application $app, Closure $next)
    {
        foreach ($this->loadFromCache() as $key => $value) {
            $this->config->set($key, $value);
        }

        $this->time->setTimezone($this->config->get('app.timezone'));
        mb_internal_encoding('UTF-8');

        return $next($app);
    }


    private function loadFromCache() : array
    {
        $cache = $this->container->resolve(Cache::class, [$this->config->get('paths.cache.system')]);

        if ($this->config->get('app.debug')) {
            $cache->delete(self::CACHE_KEY);
        }

        return $cache->get(self::CACHE_KEY, function () use ($cache) {
            $data = $this->loadFromDirectory();

            $cache->set(self::CACHE_KEY, $data);

            return $data;
        });
    }


    private function loadFromDirectory() : array
    {
        $data = [];
        $environment = $this->container->resolve(Environment::class);
        $filesystem = $this->container->resolve(FileSystem::class);

        foreach ($this->config->get('app.config') as $subdirectory) {
            $files = $filesystem->getFiles($this->config->get('paths.config') . '/' . $subdirectory);
            $prefix = array_filter(explode('/', $subdirectory));

            foreach ($files as $file) {
                $key = implode('.', array_merge($prefix, [$filesystem->getFileName($file)]));

                // Current Configuration Contains The Boot Configuration Needed
                // For This Environment; Don't Overwrite
                if ($this->config->has($key)) {
                    continue;
                }

                $data[$key] = $filesystem->require($file, [
                    'config' => $this->config,
                    'env' => $environment
                ]);

                $this->config->set($key, $data[$key]);
            }
        }

        return $data;
    }
}
