<?php declare(strict_types=1);

namespace System\Validation\Rules;

use Contracts\Container\Container;

class Exists extends AbstractRule
{

    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    protected function validate($input, string $class, string $method = 'exists') : bool
    {
        if (!count((array) $input)) {
            return false;
        }

        return $this->container->resolve($class)->{$method}($input);
    }
}
