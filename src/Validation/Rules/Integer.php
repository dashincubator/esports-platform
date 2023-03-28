<?php declare(strict_types=1);

namespace System\Validation\Rules;

class Integer extends AbstractRule
{

    protected function validate($input) : bool
    {
        return (filter_var($input, FILTER_VALIDATE_INT) !== false);
    }
}
