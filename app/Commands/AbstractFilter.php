<?php

namespace App\Commands;

use Contracts\Validation\{MessageTemplates, Validator};

abstract class AbstractFilter
{

    // Ex: max:25
    private const NAME_PARAMETER_DELIMITER = ':';


    private $errors = [];

    private $success = [];

    private $validator;


    protected $templates;


    public function __construct(MessageTemplates $templates, Validator $validator)
    {
        $this->templates = $templates;
        $this->validator = $validator;
    }


    public function error($messages) : void
    {
        $this->errors = array_filter(array_unique(array_merge($this->errors, (array) $messages)));
    }


    public function getErrorMessages() : array
    {
        return $this->errors;
    }


    public function getFields(array $skip = []) : array
    {
        $fields = [];
        $keys = array_keys($this->getRules());

        foreach ($keys as $key) {
            $fields[] = explode('.', $key, 2)[0];
        }

        return array_diff(array_unique($fields), $skip);
    }


    protected function getRules(array $data = []) : array
    {
        return [];
    }


    protected function getSuccessMessage() : string
    {
        return '';
    }


    public function getSuccessMessages() : array
    {
        return $this->success;
    }


    public function hasErrors() : bool
    {
        return count($this->errors) > 0;
    }


    public function isValid(array $input) : bool
    {
        $this->errors = $this->validate($input);
        $this->success = [];

        return count($this->errors) === 0;
    }


    protected function success($messages) : void
    {
        $this->success = array_filter(array_unique(array_merge($this->success, (array) $messages)));
    }


    private function validate(array $input) : array
    {
        $messages = [];
        $rules = [];

        foreach ($this->getRules($input) as $field => $rulelist) {
            $messages[$field] = [];
            $rules[$field] = array_keys($rulelist);

            foreach ($rulelist as $rule => $message) {
                $messages[$field][explode(self::NAME_PARAMETER_DELIMITER, $rule, 2)[0]] = $message;
            }
        }

        return $this->validator->validate($messages, $rules, $input);
    }


    public function writeSuccessMessage(...$args) : void
    {
        $this->success($this->getSuccessMessage(...$args));
    }


    public function writeUnknownErrorMessage() : void
    {
        $this->error('Unknown error ocurred, please try again. If this continues please contact a site administrator.');
    }
}
