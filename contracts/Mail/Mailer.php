<?php declare(strict_types=1);

namespace Contracts\Mail;

interface Mailer
{

    /**
     *  @param Mail $mail
     *  @return bool True If Sent, Otherwise False
     */
    public function send(Mail $mail) : bool;
}
