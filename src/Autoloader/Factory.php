<?php declare(strict_types=1);

require_once "Autoloader.php";

return function(array $namespaces = [])
{
    $loader = new System\Autoloader\Autoloader();

    foreach ($namespaces as $namespace => $path) {
        $loader->add($namespace, $path);
    }

    $loader->register();

    return $loader;
};
