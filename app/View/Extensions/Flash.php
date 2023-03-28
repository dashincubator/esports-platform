<?php

namespace App\View\Extensions;

use App\Flash as Manager;

class Flash
{

    private $data;

    private $flash;


    public function __construct(Manager $flash)
    {
        $this->flash = $flash;
    }


    private function get(string $key) : array
    {
        if (is_null($this->data)) {
            $this->data = $this->flash->toArray();
        }

        return $this->data[$key] ?? [];
    }


    public function getErrorMessages() : array
    {
        return $this->get('error');
    }


    public function getInput($key, $default = '')
    {
        return $this->get('input')[$key] ?? $default;
    }


    public function getSuccessMessages() : array
    {
        return $this->get('success');
    }
}
