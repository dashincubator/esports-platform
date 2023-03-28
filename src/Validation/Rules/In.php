<?php declare(strict_types=1);

namespace System\Validation\Rules;

class In extends AbstractRule
{

    protected function validate($input, ...$in) : bool
    {
        return in_array($input, $in);
    }
}
