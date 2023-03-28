<?php declare(strict_types=1);

namespace Contracts\Mail;

interface Factory
{

    /**
     *  @return Mail
     */
    public function createMail() : Mail;
}
