<?php

namespace App\Commands\User\Update\Accounts;

use App\Commands\AbstractCommand;
use App\DataSource\Game\Api\Match\Mapper as GameApiMatchMapper;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Account\Mapper as AccountMapper;
use Contracts\Configuration\Configuration;

class Command extends AbstractCommand
{

    private $mapper;

    private $unique;

    private $whitelist;

    private $normalize;


    public function __construct(AccountMapper $account, Configuration $config, Filter $filter, GameApiMatchMapper $match, UserMapper $user)
    {
        $this->filter = $filter;
        $this->mapper = compact('account', 'match', 'user');
        $this->normalize = $config->get('social.normalize');
        $this->unique = array_keys($config->get('game.accounts'));
        $this->whitelist = array_keys(array_merge($config->get('game.accounts'), $config->get('social.accounts')));
    }


    protected function run(array $accounts, int $id) : bool
    {
        $user = $this->mapper['user']->findById($id);

        if ($user->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $existing = $this->mapper['account']->findByUser($user->getId());

            if ($existing->matches($accounts)) {
                return $this->booleanResult();
            }

            $delete = [];
            $insert = [];

            foreach ($accounts as $name => $value) {
                if (!in_array($name, $this->whitelist)) {
                    continue;
                }

                $filtered = $existing->findByName($name);

                foreach ($filtered as $entity) {
                    if ($entity->getValue() === $value) {
                        continue;
                    }

                    $delete[] = $entity;
                }

                if ($value && !in_array($value, $filtered->column('value'))) {
                    if (in_array($name, $this->unique) && !$this->mapper['account']->isUnique($name, $value)) {
                        $this->filter->writeAccountAlreadyInUseMessage($name);
                    }

                    if ($this->normalize[$name] ?? false) {
                        $value = array_reverse(explode($this->normalize[$name], $value, 2))[0];
                    }

                    $insert[] = $this->mapper['account']->create(array_merge(compact('name', 'value'), [
                        'user' => $user->getId()
                    ]));
                }
            }
        }

        if (!$this->filter->hasErrors()) {
            $this->mapper['account']->delete(...$delete);
            $this->mapper['account']->insert(...$insert);

            $this->mapper['match']->scheduleCreateJob($accounts);
        }

        return $this->booleanResult();
    }
}
