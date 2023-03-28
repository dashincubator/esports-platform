<?php

namespace App\Services\Api\ModernWarfare;

use App\DataSource\Game\Api\Match\{Entity as GameApiMatchEntity, Entities as GameApiMatchEntities};
use App\Services\Api\ModernWarfare\{Api, Calculator};
use Exception;

class Manager
{

    // Supported Accounts
    private $accounts = [
        'activision',
        'battle',
        'playstation',
        'xbox'
    ];

    private $api;

    private $calculator;

    // 'competition' => 'method'
    private $competitions = [
        'Warzone Kills' => 'kills',
        'Warzone Kills + Wins' => 'killsWins',
        //'Warzone Plunder Cash Collected' => 'plunder',
        'Warzone Wins' => 'wins'
    ];


    private $id = 'ModernWarfare';


    public function __construct(Api $api, Calculator $calculator)
    {
        $this->api = $api;
        $this->calculator = $calculator;
    }


    public function calculateScore(GameApiMatchEntities $matches, string $competition, int $members = 1) : int
    {
        return $this->calculator->{$this->getCalculatorMethod($competition)}($matches, $members);
    }


    public function fetch(string $api, GameApiMatchEntity ...$match)
    {
        return $this->api->{$this->getApiMethod($api)}(...$match);
    }


    // Unique Key Used In DB
    public function getApi(string $account) : string
    {
        return "{$this->id}:{$account}";
    }


    private function getApiMethod(string $api) : string
    {
        foreach ($this->accounts as $account) {
            if ($this->getApi($account) !== $api) {
                continue;
            }

            return $account;
        }

        throw new Exception(get_called_class() . " Received An Invalid API Key: '{$api}'");
    }


    private function getCalculatorMethod(string $competition) : string
    {
        if (!array_key_exists($competition, $this->competitions)) {
            throw new Exception(get_called_class() . " Received An Invalid Competition Format: '{$competition}'");
        }

        return $this->competitions[$competition];
    }


    public function getCompetitionFormatOptions() : array
    {
        return array_keys($this->competitions);
    }


    public function getInvalidAccountUsernames() : array
    {
        return $this->api->getInvalidAccountUsernames();
    }


    public function manages(string $api) : bool
    {
        foreach ($this->accounts as $account) {
            if ($this->getApi($account) === $api) {
                return true;
            }
        }

        return false;
    }


    public function managesAccount(string $account) : bool
    {
        return in_array($account, $this->accounts);
    }


    public function managesCompetition(string $competition) : bool
    {
        return array_key_exists($competition, $this->competitions);
    }
}
