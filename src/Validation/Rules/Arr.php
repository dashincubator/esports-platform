<?php declare(strict_types=1);

namespace System\Validation\Rules;

class Arr extends AbstractRule
{

    protected function validate($input) : bool
    {
        return is_array($input);
    }
}
