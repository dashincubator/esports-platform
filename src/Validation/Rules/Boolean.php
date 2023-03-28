<?php declare(strict_types=1);

namespace System\Validation\Rules;

class Boolean extends AbstractRule
{

    protected function validate($input) : bool
    {
        return is_bool(filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }
}
