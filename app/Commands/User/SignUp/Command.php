<?php

namespace App\Commands\User\SignUp;

use App\Commands\AbstractCommand;
use App\Commands\User\Create\Command as CreateCommand;
use App\Commands\User\SignIn\Command as SigninCommand;

class Command extends AbstractCommand
{

    private $command;


    public function __construct(CreateCommand $create, Filter $filter, SigninCommand $signin)
    {
        $this->command = compact('create', 'signin');
        $this->filter = $filter;
    }


    protected function run(string $email, string $password, string $timezone, string $username) : bool
    {
        $result = $this->delegate($this->command['create'], compact($this->filter->getFields()));

        if ($result) {
            $this->command['signin']->execute([
                'identifier' => $email,
                'password' => $password
            ]);
        }

        return $result;
    }
}
