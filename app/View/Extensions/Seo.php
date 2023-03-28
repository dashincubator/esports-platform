<?php

namespace App\View\Extensions;

use Contracts\Configuration\Configuration;
use Contracts\View\Extensions\Data;

class Seo
{

    private $config;

    private $default;

    private $description;

    private $title;


    public function __construct(Configuration $config, string $default = 'default')
    {
        $this->config = $config;
        $this->default = $default;
    }


    public function description() : string
    {
        return $this->description ?? $this->config->get("seo.{$this->default}.description");
    }


    public function ladder(Data $game, Data $ladder, Data $platform) : void
    {
        $this->use('ladder', [
            '{game}' => $game['name'],
            '{ladder}' => $ladder['name'],
            '{platform}' => $platform['name']
        ]);
    }


    public function league(Data $game, Data $league, Data $platform) : void
    {
        $this->use('league', [
            '{game}' => $game['name'],
            '{league}' => $league['name'],
            '{platform}' => $platform['name']
        ]);
    }


    public function title() : string
    {
        return $this->title ?? $this->config->get("seo.{$this->default}.title");
    }


    private function use(string $key, array $replace = []) : void
    {
        $this->description = str_replace(
            array_keys($replace),
            array_values($replace),
            $this->config->get("seo.{$key}.description", $this->config->get("seo.{$this->default}.description"))
        );
        $this->title = str_replace(
            array_keys($replace),
            array_values($replace),
            $this->config->get("seo.{$key}.title", $this->config->get("seo.{$this->default}.title"))
        );
    }
}
