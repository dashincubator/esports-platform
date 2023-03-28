<?php

namespace App\DataSource\User;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $adminPosition = 0;

    protected $avatar = '';

    protected $banner = '';

    protected $bio = '';

    protected $createdAt;

    protected $email;

    protected $id;

    protected $locked = false;

    protected $lockedAt = 0;

    protected $membershipExpiresAt = 0;

    protected $organization = 0;

    protected $password;

    protected $signinToken = '';

    protected $slug;

    protected $timezone;

    protected $username;

    protected $wagers = false;


    protected function getCasts() : array
    {
        return [
            'locked' => 'bool',
            'wagers' => 'bool'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
