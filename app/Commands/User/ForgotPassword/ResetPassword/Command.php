<?php

namespace App\Commands\User\ForgotPassword\ResetPassword;

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


    protected function run(string $code, int $id, string $password) : bool
    {
        $fpw = $this->mapper['fpw']->findById($id);

        if ($fpw->isEmpty()) {
            $this->filter->writeInvalidTokenMessage();
        }
        elseif (!$fpw->isValidCode($code)) {
            $this->filter->writeInvalidTokenMessage();
        }

        if (!$this->filter->hasErrors()) {
            $user = $this->mapper['user']->findById($fpw->getUser());
            $user->fill(compact('password'));

            $this->mapper['fpw']->transaction(function() use ($fpw, $user) {
                $this->mapper['fpw']->delete($fpw);

                $this->mapper['user']->transaction(function() use ($user) {
                    $this->mapper['user']->update($user);
                });
            });
        }

        return $this->booleanResult();
    }
}
