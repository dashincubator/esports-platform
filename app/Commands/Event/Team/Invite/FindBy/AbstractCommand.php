<?php

namespace App\Commands\Event\Team\Invite\FindBy;

use App\Commands\AbstractCommand as AbstractParent;
use App\Commands\User\Find\Command as FindCommand;
use App\Commands\Event\Team\Invite\Create\AbstractCommand as CreateCommand;

abstract class AbstractCommand extends AbstractParent
{

    private $command;


    public function __construct(CreateCommand $create, Filter $filter, FindCommand $find)
    {
        $this->command = compact('create', 'find');
        $this->filter = $filter;
    }


    protected function run(string $column, int $team, $value) : bool
    {
        $user = $this->delegate($this->command['find'], compact('column', 'value'));

        if (!$this->filter->hasErrors()) {
            $this->delegate($this->command['create'], [
                'team' => $team,
                'user' => $user->getId()
            ]);
        }

        return $this->booleanResult($user->getUsername());
    }
}
