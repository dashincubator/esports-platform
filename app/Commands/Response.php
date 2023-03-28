<?php

namespace App\Commands;

class Response
{

    private $errors = [];

    private $input = [];

    private $result;

    private $success = [];


    public function __construct(array $errors = [], array $input = [], $result = null, array $success = [])
    {
        $this->errors = $errors;
        $this->input = $input;
        $this->result = $result;
        $this->success = $success;
    }


    public function getErrorMessages() : array
    {
        return $this->errors;
    }


    public function getInput() : array
    {
        return $this->input;
    }


    public function getResult()
    {
        return $this->result;
    }


    public function getSuccessMessages() : array
    {
        return $this->success;
    }


    public function hasErrors() : bool
    {
        return (bool) count($this->errors);
    }
}
