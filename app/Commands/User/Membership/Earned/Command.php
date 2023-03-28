<?php

namespace App\Commands\User\Membership\Earned;

use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(int $days, array $users) : bool
    {
        $users = $this->mapper->findByIds(...$users);

        foreach ($users as $user) {
            $user->updateMembershipTime($days);
        }

        $this->mapper->update(...iterator_to_array($users));

        return $this->booleanResult();
    }
}
