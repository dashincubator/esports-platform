<?php declare(strict_types=1);

namespace System\Validation\Rules;

use Exception;

class Numeric extends AbstractRule
{

    protected function validate($input) : bool
    {
        return is_numeric($input);
    }
}
