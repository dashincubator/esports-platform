<?php declare(strict_types=1);

namespace System\View\Extensions;

use Contracts\View\Extensions\Extension as Contract;
use Contracts\View\Factory;
use Contracts\Support\Arrayable;
use Exception;
use Iterator;

class Extension implements Contract
{

    private $factory;

    private $obj;


    public function __construct(Factory $factory, object $obj)
    {
        $this->factory = $factory;
        $this->obj = $obj;
    }


    public function __call(string $method, array $args)
    {
        return $this->wrap($this->obj->{$method}(...$args));
    }


    public function __invoke()
    {
        return $this->wrap(($this->obj)(...func_get_args()));
    }


    private function wrap($value)
    {
        // Make Sure All Data Is Escaped
        switch(gettype($value)) {
            case 'boolean':
            case 'integer':
            case 'double':
            case 'NULL':
                return $value;

            case 'array':
                return $this->factory->createData($value);

            case 'object':
                if ($value instanceof Data) {
                    return $value;
                }
                elseif ($value instanceof Arrayable) {
                    return $this->factory->createData($value->toArray());
                }
                break;

            case 'string':
                return $this->factory->createData([$value])[0];
        }

        throw new Exception("View Models Must Return '" . Data::class . "', '" . Arrayable::class . "' Or Primitive Values; '{$key}' View Function Returns An Unsupported Type");
    }
}
