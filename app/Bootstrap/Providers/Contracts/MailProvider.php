<?php

namespace App\Bootstrap\Providers\Contracts;

use App\Bootstrap\Providers\AbstractProvider;
use Contracts\Mail\Mailer;

class MailProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerMailerBinding();
    }


    private function registerMailerBinding() : void
    {
        $this->container->singleton(Mailer::class, null, [
            $this->config->get('contracts.mail.host'),
            (bool) $this->config->get('contracts.mail.persist'),
            (int) $this->config->get('contracts.mail.port'),
            (string) $this->config->get('contracts.mail.password'),
            (string) $this->config->get('contracts.mail.username')
        ]);
    }
}
