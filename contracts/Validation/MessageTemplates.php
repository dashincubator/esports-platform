<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Input Validation Error Message Templates
 *
 */

namespace Contracts\Validation;

interface MessageTemplates
{

    /**
     *  General Error Message For Invalid Input
     *
     *  @param string $field
     *  @return string
     */
    public function invalid(string $field) : string;


    /**
     *  Message Displayed If Input Failed 'max' Validation
     *  - Responsible For 'array', 'numeric', 'string' Validation
     *
     *  @param $field
     *  @param $value
     *  @return string
     */
    public function max($field, $value, int $max) : string;


    /**
     *  Message Displayed If Input Failed 'min' Validation
     *  - Responsible For 'array', 'numeric', 'string' Validation
     *
     *  @param $field
     *  @param $value
     *  @return string
     */
    public function min($field, $value, int $min) : string;


    /**
     *  Message Displayed If Input Failed 'required' Validation
     *
     *  @param string $field
     *  @return string
     */
    public function required(string $field) : string;


    /**
     *  Message Displayed If Input Failed 'string' Validation
     *
     *  @param string $field
     *  @return string
     */
    public function string(string $field) : string;
}
