<?php declare(strict_types=1);

namespace System\View;

use Contracts\View\Buffer as Contract;
use Exception;

class Buffer implements Contract
{

    // Map Of Ob Level => Key
    private $stack = [];


    public function end() : array
    {
        $level = ob_get_level();

        return [
            'content' => trim(ob_get_clean()),
            'key' => $this->get($level)
        ];
    }


    private function get($level) : string
    {
        if (!$this->has($level)) {
            throw new Exception("View Buffer Is Missing A 'ob_get_level' Key");
        }

        return $this->stack[$level];
    }


    private function has($level) : bool
    {
        return isset($this->stack[$level]);
    }


    public function start(string $key = '') : void
    {
        ob_start();

        $this->stack[ob_get_level()] = $key;
    }
}
