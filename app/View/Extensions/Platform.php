<?php

namespace App\View\Extensions;

use App\DataSource\Game\Platform\{Entities, Entity, Mapper};
use Contracts\Configuration\Configuration;

class Platform
{

    private $config;

    private $platforms;

    private $mapper;


    public function __construct(Configuration $config, Mapper $mapper)
    {
        $this->config = $config;
        $this->mapper = $mapper;
    }


    public function get(int $id) : Entity
    {
        return $this->getAll()->get('id', $id);
    }


    public function getAccountOptions() : array
    {
        return array_merge([
            '' => 'Account Will Be Defined By Game'
        ], $this->config->get('game.accounts', []));
    }


    public function getAll() : Entities
    {
        if (is_null($this->platforms)) {
            $this->platforms = $this->mapper->findAll();
        }

        return $this->platforms;
    }


    public function getViewOptions() : array
    {
        return $this->config->get('game.platform.view', []);
    }
}
