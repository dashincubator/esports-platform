<?php

namespace App\Commands\User\Update\Password;

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


    protected function run(string $current, int $id, string $new) : bool
    {
        $user = $this->mapper->findById($id);

        if ($user->isEmpty() || !$user->isValidPassword($current)) {
            $this->filter->writeInvalidPasswordMessage();
        }

        if (!$this->filter->hasErrors()) {
            $user->fill(['password' => $new]);
            $this->mapper->update($user);
        }

        return $this->booleanResult();
    }
}
