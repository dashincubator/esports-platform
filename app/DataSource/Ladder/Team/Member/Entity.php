<?php

namespace App\DataSource\Ladder\Team\Member;

use App\DataSource\Event\Team\Member\AbstractEntity;

class Entity extends AbstractEntity
{

    public function lock(string $account) : void
    {
        $this->set('account', $account);
    }


    public function updateGameStatsScore(int $score) : bool
    {
        // Most Likely Have An Issue With The Game API
        if ($score < 1) {
            return false;
        }

        $this->set('score', $score);
        $this->set('scoreModifiedAt', time());

        return true;
    }
}
