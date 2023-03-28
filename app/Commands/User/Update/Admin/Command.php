<?php

namespace App\Commands\User\Update\Admin;

use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Admin\Position\Mapper as AdminPositionMapper;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(AdminPositionMapper $position, Filter $filter, UserMapper $user)
    {
        $this->filter = $filter;
        $this->mapper = compact('position', 'user');
    }


    protected function run(int $adminPosition, int $editor, array $users) : bool
    {
        foreach ($users as $index => $user) {
            if ($editor !== $user) {
                continue;
            }

            $this->filter->writeCannotModifyOwnAccountMessage();
        }

        if (!$this->filter->hasErrors()) {
            if (!count($users)) {
                return $this->booleanResult();
            }

            $users = $this->mapper['user']->findByIds(...$users);

            if ($users->isEmpty()) {
                $this->filter->writeUnknownErrorMessage();
            }
            // If Removing From Admin Set 'adminPosition' => 0
            // Else Validate Position
            elseif ($adminPosition > 0) {
                $position = $this->mapper['position']->findById($adminPosition);

                if ($position->isEmpty()) {
                    $this->filter->writeUnknownErrorMessage();
                }
            }
        }

        if (!$this->filter->hasErrors()) {
            foreach ($users as $user) {
                $user->fill(compact('adminPosition'));
            }

            $this->mapper['user']->update(...iterator_to_array($users));
        }

        return $this->booleanResult();
    }
}
