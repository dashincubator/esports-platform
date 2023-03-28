<?php declare(strict_types=1);

namespace System\Validation\Rules;

use Contracts\Validation\Rule as Contract;
use Exception;

abstract class AbstractRule implements Contract
{

    public function passes(...$args) : bool
    {
        if (!method_exists($this, 'validate')) {
            throw new Exception(
                "'" . get_called_class() . "' Does Not Provide A 'validate' Method That Is Accessible By 'System/Validation/AbstractRule'"
            );
        }

        return $this->validate(...$args);
    }
}
