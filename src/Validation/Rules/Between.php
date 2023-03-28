<?php declare(strict_types=1);

namespace System\Validation\Rules;

class Between extends AbstractRule
{

    protected function validate($input, int $min, int $max) : bool
    {
        if (is_string($input) && !is_numeric($input)) {
            $input = mb_strlen($input);
        }

        return ($input >= $min && $input <= $max);
    }
}
