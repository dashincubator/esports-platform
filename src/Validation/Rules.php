<?php declare(strict_types=1);

namespace System\Validation;

use Contracts\Validation\{Rule, Rules as Contract};
use Exception;

class Rules implements Contract
{

    private $rules = [];


    public function get(string $name) : Rule
    {
        if (!isset($this->rules[$name])) {
            throw new Exception("'{$name}' Validator Rule Does Not Exist");
        }

        return $this->rules[$name];
    }


    public function set(string $name, Rule $rule) : void
    {
        $this->rules[$name] = $rule;
    }
}
 
