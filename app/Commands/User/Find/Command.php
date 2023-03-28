<?php

namespace App\Commands\User\Find;

use App\Commands\AbstractCommand;
use App\DataSource\Game\Mapper as GameMapper;
use App\DataSource\User\{Entity as UserEntity, Mapper as UserMapper};
use App\DataSource\User\Account\Mapper as AccountMapper;
use Contracts\Configuration\Configuration;

class Command extends AbstractCommand
{

    private $accounts;

    private $mapper;

    private $name;


    public function __construct(AccountMapper $account, Configuration $config, Filter $filter, GameMapper $game, UserMapper $user, string $name)
    {
        $this->accounts = $config->get('game.accounts');
        $this->filter = $filter;
        $this->mapper = compact('account', 'game', 'user');
        $this->name = ucfirst($name);
    }


    public function buildOptions() : array
    {
        $options = $this->accounts;
        $options['id'] = "{$this->name} ID";

        return $options;
    }


    protected function run(string $column, $value) : UserEntity
    {
        $user = $this->mapper['user']->create();

        if ($column === 'id' && is_numeric($value)) {
            $user = $this->mapper['user']->findById($value);
        }
        else {
            $account = $this->mapper['account']->findByNameAndValue($column, $value);

            if (!$account->isEmpty()) {
                $user = $this->mapper['user']->findById($account->getUser());
            }
        }

        if ($user->isEmpty()) {
            $this->filter->writeUserNotFoundMessage();
        }

        return $user;
    }
}
