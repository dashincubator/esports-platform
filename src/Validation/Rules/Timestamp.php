<?php declare(strict_types=1);

namespace System\Validation\Rules;

use Exception;

class Timestamp extends AbstractRule
{

    protected function validate($input) : bool
    {
        try {
            if (strtotime(date('d-m-Y H:i:s', $input)) === (int) $input) {
                return true;
            }
        }
        catch(Exception $e) { }

        return false;
    }
}
