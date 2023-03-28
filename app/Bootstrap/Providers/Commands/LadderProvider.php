<?php

namespace App\Bootstrap\Providers\Commands;

use App\Commands\Ladder\Team\Invite\DeleteExpired\Command as DeleteExpiredInvitesCommand;
use App\Bootstrap\Providers\AbstractProvider;

class LadderProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerDeleteExpiredInvitesBinding();
    }


    private function registerDeleteExpiredInvitesBinding() : void
    {
        $this->container->bind(DeleteExpiredInvitesCommand::class, null, [$this->config->get('team.expire.invites')]);
    }
}
