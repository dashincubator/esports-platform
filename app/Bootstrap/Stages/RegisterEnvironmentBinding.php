<?php

namespace App\Bootstrap\Stages;

use Contracts\Bootstrap\Application;
use Contracts\Configuration\Configuration;
use Contracts\Container\Container;
use Contracts\Environment\{Environment, Parser};
use Contracts\IO\FileSystem;
use Closure;

class RegisterEnvironmentBinding
{

    private const ENVIRONMENT_FILE = '.env';


    private $config;

    private $container;


    public function __construct(Configuration $config, Container $container)
    {
        $this->config = $config;
        $this->container = $container;
    }


    /**
     *--------------------------------------------------------------------------
     *
     *  If Env Variables Are Only Used In Config Files We Can Skip
     *  Loading/Setting Variables Every Request.
     *
     */

    public function handle(Application $app, Closure $next)
    {
        $concrete = $this->container->getAlias(Environment::class);

        $this->container->singleton(Environment::class, function(Container $container) use ($concrete) {
            $environment = $container->resolve($concrete, [$this->config->get('app.debug')]);
            $variables = $container->resolve(Parser::class)->parse(
                $this->config->get('paths.root') . '/' . self::ENVIRONMENT_FILE
            );

            foreach ($variables as $key => $value) {
                $environment->set($key, $value);
            }

            return $environment;
        });

        return $next($app);
    }
}
