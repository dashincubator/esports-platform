<?php

use Contracts\Bootstrap\Application;

/**
 *  @param array $app Application Configuration ( @see '/config/app.php' )
 *  @return Application
 */
return function(array $app) : Application
{
    /** 
     *--------------------------------------------------------------------------
     *
     *  Step 1: Define Paths
     *
     */

    $paths = require realpath(__DIR__ . '/..') . '/config/paths.php';

    /**
     *--------------------------------------------------------------------------
     *
     *  Step 2: Register Autoloader
     *
     */

    (require "{$paths['src']}/Autoloader/Factory.php")($app['namespaces']);

    /**
     *--------------------------------------------------------------------------
     *
     *  Step 3: Build Application
     *
     */

    return new $app['bindings'][Application::class]($app, $app['bindings'], $paths);
};
