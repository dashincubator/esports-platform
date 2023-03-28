<?php

namespace App\DataSource\User\ForgotPassword;

use App\DataSource\AbstractEntity;
use Contracts\UUID\RandomGenerator;
use Exception;

class Entity extends AbstractEntity
{

    private $uuid;


    protected $guarded = ['*'];


    public function __construct(RandomGenerator $uuid, Record $record)
    {
        parent::__construct($record);

        $this->uuid = $uuid;
    }


    public function alreadySent() : bool
    {
        return $this->get('emailedAt') > 0;
    }


    public function inserting() : void
    {
        $this->set('code', $this->uuid->generate());
        $this->set('createdAt', time());
    }


    public function isValidCode(string $code) : bool
    {
        return $this->get('code') === $code;
    }


    public function sent() : void
    {
        if ($this->get('emailedAt')) {
            throw new Exception('Forgot password email was already sent!');
        }

        $this->set('emailedAt', time());
    }
}
