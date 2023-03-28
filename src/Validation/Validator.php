<?php declare(strict_types=1);

namespace System\Validation;

use Contracts\Validation\{RulesParser, Rules, Validator as Contract};
use Exception;

class Validator implements Contract
{

    private const RULE_VALIDATION_METHOD = 'passes';


    private $errors = [];

    private $rules;


    public function __construct(RulesParser $parser, Rules $rules)
    {
        $this->parser = $parser;
        $this->rules = $rules;
    }


    public function validate(array $messages, array $rules, array $values) : array
    {
        $this->errors = [];

        foreach ($rules as $field => $rulelist) {
            $this->validateLoop($field, explode('.', $field), $messages, $rulelist, $values);
        }

        return array_unique($this->errors);
    }


    private function validateField(string $field, array $messages, array $rules, $value) : void
    {
        // Move Required Validation To Front Of The List
        if (array_key_exists('required', $rules)) {
            $rules = array_merge(['required' => []], $rules);
        }
        // Bail If Not Required And Empty
        elseif (is_null($value) || (is_array($value) && count($value) === 0) || $value === '') {
            return;
        }

        foreach ($rules as $name => $parameters) {
            if ($this->rules->get($name)->{self::RULE_VALIDATION_METHOD}(...array_merge([$value], $parameters))) {
                continue;
            }

            $message = $messages[$name] ?? false;

            if (!$message) {
                $message = "{$field}.{$name} failed";
            }

            $this->errors[] = $message;

            return;
        }
    }


    private function validateLoop(string $field, array $keys, array $messages, array $rulelist, $values) : void
    {
        if (!count($keys) || is_null($values)) {
            $this->validateField($field, ($messages[$field] ?? []), $this->parser->parse($field, $rulelist), $values);
        }
        elseif ($key = array_shift($keys)) {
            if ($key === '*') {
                foreach ((array) $values as $v) {
                    $this->validateLoop($field, $keys, $messages, $rulelist, $v);
                }
            }
            else {
                $this->validateLoop($field, $keys, $messages, $rulelist, ($values[$key] ?? null));
            }
        }
    }
}
