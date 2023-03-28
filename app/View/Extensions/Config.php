<?php

namespace App\View\Extensions;

use Contracts\Configuration\Configuration;
use Exception;

class Config
{

    private $config;

    private $whitelist = [
        'arena',
        'bank',
        'app.debug',
        'app.name',
        'app.timezone',
        'game',
        'links',
        'membership',
        'pages',
        'social'
    ];


    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }


    public function get(string $key)
    {
        if (!in_array($key, $this->whitelist) && !in_array(explode('.', $key)[0], $this->whitelist)) {
            throw new Exception("Configuration Key '{$key}' Is Not Accessible By View Files");
        }

        return $this->config->get($key);
    }


    public function has(string $key) : bool
    {
        return $this->config->has($key);
    }
}
