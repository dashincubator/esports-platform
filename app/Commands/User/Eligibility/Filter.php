<?php

namespace App\Commands\User\Eligibility;

use App\Commands\AbstractFilter;
use Contracts\Configuration\Configuration;
use Contracts\Validation\{MessageTemplates, Validator};

class Filter extends AbstractFilter
{

    private $accounts;


    public function __construct(Configuration $config, MessageTemplates $templates, Validator $validator)
    {
        parent::__construct($templates, $validator);
        $this->accounts = $config->get('game.accounts');
    }


    protected function getRules(array $data = []) : array
    {
        return [
            'accountModifiedAt' => [
                'int' => $this->templates->invalid('game account modified at time')
            ],
            'amount' => [
                'numeric' => $this->templates->invalid('amount')
            ],
            'game' => [
                'int' => $this->templates->invalid('game'),
                'required' => $this->templates->required('game')
            ],
            'gameStat' => [
                'bool' => $this->templates->invalid('gameStat'),
            ],
            'membership' => [
                'bool' => $this->templates->invalid('membership switch')
            ],
            'users' => [
                'array' => $this->templates->invalid('users list'),
                'required' => $this->templates->required('users list')
            ],
            'users.*' => [
                'int' => $this->templates->invalid('users list value'),
                'required' => $this->templates->required('users list value')
            ],
            'wagers' => [
                'bool' => $this->templates->invalid('wagers switch')
            ]
        ];
    }


    public function writeExpiredMembershipMessage(string $username) : void
    {
        $this->error("{$username} cannot participate without a membership");
    }


    public function writeInsufficientFundsMessage(string $username) : void
    {
        $this->error("{$username} does not have enough funds to participate");
    }


    public function writeInvalidGameAccountMessage(string $account, string $username) : void
    {
        $this->error("{$username} did not provide a valid {$this->accounts[$account]}, please update the {$this->accounts[$account]} and try again.");
    }


    public function writeMissingGameAccountMessage(string $account, string $username) : void
    {
        $this->error("{$username} must add a {$this->accounts[$account]} to participate");
    }


    public function writeModifiedGameAccountMessage(string $account, string $username) : void
    {
        $this->error("{$username} modified {$this->accounts[$account]} after the team was locked, and is no longer eligible to compete");
    }


    public function writeWagersNotAllowedMessage(string $username) : void
    {
        $this->error("{$username} does not have wagers enabled in their user settings");
    }
}
