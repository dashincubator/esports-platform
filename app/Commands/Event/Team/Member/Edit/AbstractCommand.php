<?php

namespace App\Commands\Event\Team\Member\Edit;

use App\Commands\AbstractCommand as AbstractParent;
use App\Commands\Event\Team\Member\Kick\AbstractCommand as KickCommand;
use App\Commands\Event\Team\Member\Update\AbstractCommand as UpdateCommand;

abstract class AbstractCommand extends AbstractParent
{

    private $command;


    public function __construct(Filter $filter, KickCommand $kick, UpdateCommand $update)
    {
        $this->command = compact('kick', 'update');
        $this->filter = $filter;
    }


    // Editors Cannot Modify Their Own Permissions
    // TODO: Replace With Admin Permissions Like System
    protected function run(int $editor, array $kick, array $permissions, int $team) : bool
    {
        foreach ($kick as $user) {
            $user = (int) $user;

            if ($editor === $user) {
                continue;
            }

            $this->delegate($this->command['kick'], compact('team', 'user'));
        }

        foreach ($permissions as $user => $fill) {
            $user = (int) $user;

            if ($editor === $user || in_array($user, $kick)) {
                continue;
            }

            $this->delegate($this->command['update'], array_merge($fill, compact('team', 'user')));
        }

        return $this->booleanResult();
    }
}
