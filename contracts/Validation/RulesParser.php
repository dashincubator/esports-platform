<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Validation Rule List Parser
 *
 */

namespace Contracts\Validation;

interface RulesParser
{

    /**
     *  Parse Input Rule List
     *
     *  Ex: min:10|max:20
     *
     *  @param string $field
     *  @param array $rules
     *  @return array Parsed Rule List
     */
    public function parse(string $field, array $rules) : array;
}
