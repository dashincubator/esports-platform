<?php

namespace App\Services\Api;

use App\DataSource\Game\Api\Match\{Entities as GameApiMatchEntities, Entity as GameApiMatchEntity};
use App\Services\Api\ModernWarfare\Manager as ModernWarfareManager;
use Exception;

class Managers
{

    private $managers;


    public function __construct(ModernWarfareManager $mw)
    {
        $this->managers = [$mw];
    }


    public function calculateScore(GameApiMatchEntities $matches, string $competition, int $members) : int
    {
        foreach ($this->managers as $manager) {
            if (!$manager->managesCompetition($competition)) {
                continue;
            }

            return $manager->calculateScore($matches, $competition, $members);
        }

        throw new Exception("API Managers Recieved Invalid Competition Format: {$competition}");
    }


    public function fetch(string $api, GameApiMatchEntity ...$matches) : array
    {
        $found = [];

        foreach ($this->managers as $manager) {
            if (!$manager->manages($api)) {
                continue;
            }

            $stats = $manager->fetch($api, ...$matches);

            if (!$stats) {
                continue;
            }

            foreach ($stats as $username => $matches) {
                if (!$matches) {
                    continue;
                }

                foreach ($matches as $match) {
                    $found[] = array_merge($match, compact('api', 'username'));
                }
            }
        }

        return $found;
    }


    public function getApiManagingAccount(string $account) : string
    {
        foreach ($this->managers as $manager) {
            if (!$manager->managesAccount($account)) {
                continue;
            }

            return $manager->getApi($account);
        }

        throw new Exception("API Managers Could Not Find An API Managing Account: {$account}");
    }


    public function getCompetitionFormatOptions() : array
    {
        $options = [];

        foreach ($this->managers as $manager) {
            $options = array_merge($options, $manager->getCompetitionFormatOptions());
        }

        return $options;
    }


    public function getInvalidAccountUsernames(string $api) : array
    {
        foreach ($this->managers as $manager) {
            if (!$manager->manages($api)) {
                continue;
            }

            return $manager->getInvalidAccountUsernames();
        }

        throw new Exception("API Managers Could Not Find An API Managing Account: {$api}");
    }
}
