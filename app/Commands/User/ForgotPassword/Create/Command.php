<?php

namespace App\Commands\User\ForgotPassword\Create;

use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\ForgotPassword\Mapper as ForgotPasswordMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, ForgotPasswordMapper $fpw, UserMapper $user)
    {
        $this->filter = $filter;
        $this->mapper = compact('fpw', 'user');
    }


    // Error Messages Are Not Thrown When Email Does Not Exist, If They Were It
    // Would Make Phishing For Emails => Accounts Easier.
    protected function run(string $email) : bool
    {
        $user = $this->mapper['user']->findByEmail($email);

        if (!$user->isEmpty()) {
            $this->mapper['fpw']->findByUserOrCreate($user->getId());
        }

        return $this->booleanResult();
    }
}
