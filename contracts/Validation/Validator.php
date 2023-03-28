<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Input Validator
 *
 */

namespace Contracts\Validation;

interface Validator
{

    /**
     *  Returns True If Validation Succeeded Without Errors, Otherwise False
     *
     *  @param array $messages Array Of Error Messages By '{fieldname}.{rule}'
     *  @param array $rules List Of Rules To Apply To Field Value
     *  @param array $values Form Field Values [fieldname => value]
     *  @return array Empty If No Errors Found, Otherwise Error Messages
     */
    public function validate(array $messages, array $rules, array $values) : array;
}
