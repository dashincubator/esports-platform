<?php declare(strict_types=1);

namespace System\Validation\Rules;

class Min extends AbstractRule
{

    protected function validate($input, float $min) : bool
    {
        if (is_array($input)) {
            $input = count($input);
        }

        if (is_string($input) && !is_numeric($input)) {
            $input = mb_strlen($input);
        }

        return ($input >= $min);
    }
}
