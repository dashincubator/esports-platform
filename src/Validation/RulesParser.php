<?php declare(strict_types=1);

namespace System\Validation;

use Contracts\Validation\RulesParser as Contract;
use Exception;

class RulesParser implements Contract
{

    // Ex: max:25
    private const NAME_PARAMETER_DELIMITER = ':';

    // Ex: between:25,200
    private const PARAMETER_DELIMITER = ',';


    private function explode(string $delimiter, string $str) : array
    {
        return array_filter(array_map('trim', explode($delimiter, $str)), function($value) {
            return $value !== null && $value !== false && $value !== '';
        });
    }


    private function error(string $field, array $rules) : void
    {
        throw new Exception("Filter containing '{$field}' is not formatted correctly; Rules: " . implode(',', $rules));
    }


    public function parse(string $field, array $rules) : array
    {
        $parsed = [];

        foreach ($rules as $rule) {
            if (!is_string($rule)) {
                $this->error($field, $rules);
            }

            $data = $this->explode(self::NAME_PARAMETER_DELIMITER, $rule);

            if (!isset($data[1])) {
                $data[1] = '';
            }

            if (!is_string($data[1])) {
                $this->error($field, $rules);
            }

            $parsed[$data[0]] = $this->explode(self::PARAMETER_DELIMITER, $data[1]);

            foreach ($parsed[$data[0]] as $index => $value) {
                if (is_numeric($value)) {
                    if (floatval($value) && intval($value) != floatval($value)) {
                        $value = (float) $value;
                    }
                    else {
                        $value = (int) $value;
                    }
                }

                $parsed[$data[0]][$index] = $value;
            }
        }

        return $parsed;
    }
}
