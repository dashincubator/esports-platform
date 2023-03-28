<?php

namespace App\Bootstrap\Providers\Commands;

use App\Bootstrap\Providers\AbstractProvider;
use App\Commands\User\Bank\Deposit\Create\Command as CreateBankDepositCommand;
use App\Commands\User\Find\Command as FindUserCommand;
use App\Commands\User\ForgotPassword\DeleteExpired\Command as DeleteExpiredFPWCommand;
use App\Commands\User\ForgotPassword\Send\Command as SendForgotPasswordCommand;
use App\Commands\User\Membership\Purchase\Command as PurchaseMembershipCommand;

class UserProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerCreateBankDepositBinding();
        $this->registerDeleteExpiredForgotPasswordRequestsBinding();
        $this->registerFindUserBinding();
        $this->registerPurchaseMembershipBinding();
        $this->registerSendForgotPasswordBinding();
    }


    private function registerCreateBankDepositBinding() : void
    {
        $this->container->bind(CreateBankDepositCommand::class, null, [$this->config->get('bank.deposit.minimum')]);
    }


    private function registerDeleteExpiredForgotPasswordRequestsBinding() : void
    {
        $this->container->bind(DeleteExpiredFPWCommand::class, null, [$this->config->get('fpw.expire')]);
    }


    private function registerFindUserBinding() : void
    {
        $this->container->bind(FindUserCommand::class, null, [$this->config->get('app.name')]);
    }


    private function registerPurchaseMembershipBinding() : void
    {
        $this->container->bind(PurchaseMembershipCommand::class, null, [$this->config->get('membership.options')]);
    }


    private function registerSendForgotPasswordBinding() : void
    {
        $this->container->bind(SendForgotPasswordCommand::class, null, [
            $this->config->get('fpw.email.body'),
            $this->config->get('fpw.email.from.email'),
            $this->config->get('fpw.email.from.name'),
            $this->config->get('fpw.email.subject'),
        ]);
    }
}
