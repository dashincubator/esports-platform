<?php

namespace App\DataSource\User\Admin\Position;

use App\DataSource\AbstractEntity;
use Contracts\Collections\Sequential as Collection;
use Exception;

class Entity extends AbstractEntity
{

    protected $guarded = [
        'id', 'games', 'permissions'
    ];

    protected $whitelist = [];


    public function __construct(Collection $whitelist, Record $record)
    {
        parent::__construct($record);

        $this->whitelist = $whitelist;
    }


    public function can(string $permission) : bool
    {
        if (!$this->whitelist->has($permission)) {
            throw new Exception("'{$permission}' Is Not A Valid Admin Permission");
        }

        return in_array($permission, $this->get('permissions'));
    }


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function managesGame(int $game) : bool
    {
        return in_array($game, $this->get('games'));
    }


    public function updateGames(int ...$games) : void
    {
        $this->set('games', $games);
    }


    public function updatePermissions(string ...$permissions) : void
    {
        foreach ($permissions as $permission) {
            if (!$this->whitelist->has($permission)) {
                throw new Exception("'{$permission}' Is Not A Valid Admin Permission");
            }
        }

        $this->set('permissions', $permissions);
    }
}
