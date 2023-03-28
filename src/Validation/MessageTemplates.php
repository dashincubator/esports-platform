<?php

namespace System\Validation;

use Contracts\Validation\MessageTemplates as Contract;

class MessageTemplates implements Contract
{

    public function invalid(string $field) : string
    {
        return 'Please provide a valid ' . trim($field);
    }


    public function max($field, $value, int $max) : string
    {
        if (is_array($value)) {
            return "{$field} requires {$max} element" . ($max > 1 ? 's' : '') . " or less";
        }

        if (is_numeric($value)) {
            return "{$field} cannot be more than {$max}";
        }

        return ucfirst(trim($field)) . " cannot be longer than {$max} characters";
    }


    public function min($field, $value, int $min) : string
    {
        if (is_array($value)) {
            return "{$field} requires at least {$min} element" . ($min > 1 ? 's' : '');
        }

        if (is_numeric($value)) {
            return "{$field} cannot be less than {$min}";
        }

        return ucfirst(trim($field)) . " cannot be shorter than {$min} characters";
    }


    public function required(string $field) : string
    {
        return ucfirst(trim($field)) . ' is required to continue';
    }


    public function string(string $field) : string
    {
        return ucfirst(trim($field)) . ' must contain at least 1 letter';
    }
}
