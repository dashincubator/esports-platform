<?php

namespace App\DataSource\Game\Api\Match;

use App\DataSource\AbstractEntity;
use Contracts\Slug\Generator;

class Entity extends AbstractEntity
{

    protected $fillable = [
        'api', 'data', 'endedAt', 'startedAt', 'username'
    ];


    private function createHash() : void
    {
        $keys = [];
        $keys[] = $this->get('api');
        $keys[] = $this->get('data')['matchID'] ?? 0;
        $keys[] = $this->get('username');

        $this->set('hash', md5(implode('', $keys)));
    }


    public function expire() : void
    {
        $this->set('createdAt', 1);
    }


    public function inserting() : void
    {
        $this->createHash();
        $this->set('createdAt', time());
    }


    public function isExpired() : bool
    {
        return $this->get('createdAt') === 1;
    }
}
