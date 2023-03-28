<?php declare(strict_types=1);

namespace System\Environment;

use Contracts\Environment\Environment as Contract;
use Exception;

class Environment implements Contract
{

    // Overwrite Env Variables If True
    private $overwrite = false;


    public function __construct(bool $overwrite = false)
    {
        $this->overwrite = $overwrite;
    }


    public function get(string $name, $default = null)
    {
        if (array_key_exists($name, $_ENV)) {
            return $_ENV[$name];
        }

        if (array_key_exists($name, $_SERVER)) {
            return $_SERVER[$name];
        }

        $value = getenv($name, true);

        if ($value === false) {
            if (is_null($default)) {
                throw new Exception("'{$name}' Environment Variable Does Not Exist");
            }

            return $default;
        }

        return $value;
    }


    public function set(string $name, $value) : void
    {
        if ($this->overwrite === false) {
            if (array_key_exists($name, $_ENV)) {
                return;
            }

            if (array_key_exists($name, $_SERVER)) {
                return;
            }

            if (getenv($name, true) !== false) {
                return;
            }
        }

        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}
