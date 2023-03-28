<?php

namespace App\DataSource\Game\Api\Match;

use App\DataSource\AbstractEntities;

class Entities extends AbstractEntities
{

    public function filterWarzone() : Entities
    {
        return $this->filter(function($match) {
            return mb_strpos($match->getData()['mode'], 'br_dmz') === false;
        });
    }


    public function filterPlunder() : Entities
    {
        return $this->filter(function($match) {
            return mb_strpos($match->getData()['mode'], 'br_dmz') !== false;
        });
    }
}
