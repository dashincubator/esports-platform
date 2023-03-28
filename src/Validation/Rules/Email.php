<?php declare(strict_types=1);

namespace System\Validation\Rules;

class Email extends AbstractRule
{

    protected function validate($input) : bool
    {
        return (is_string($input) && filter_var($input, FILTER_VALIDATE_EMAIL) !== false);
    }
}
