<?php declare(strict_types=1);

namespace System\Validation\Rules;

class Str extends AbstractRule
{

    protected function validate($input) : bool
    {
        return (is_string($input) && !is_numeric($input));
    }
}
