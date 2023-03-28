<?php

namespace App\Commands\Game\Api\Match\Create;

use App\DataSource\Game\Api\Match\Mapper;
use App\Commands\AbstractCommand;
use App\Services\Api\Managers;

class Command extends AbstractCommand
{

    private $managers;

    private $mapper;


    public function __construct(Filter $filter, Managers $managers, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->managers = $managers;
        $this->mapper = $mapper;
    }


    // TODO: Use Same Format As User Profile Updater
    protected function run(?string $activision, ?string $playstation, ?string $xbox) : bool
    {
        $insert = [];

        foreach ($this->filter->getFields() as $account) {
            if (!${$account}) {
                continue;
            }

            $api = $this->managers->getApiManagingAccount($account);
            $username = ${$account};

            if ($this->mapper->existsByApiAndUsername($api, $username)) {
                continue;
            }

            $stat = $this->mapper->create(compact('api', 'username'));
            $stat->expire();

            $insert[] = $stat;
        }

        if ($insert) {
            $this->mapper->insert(...$insert);
        }

        return $this->booleanResult();
    }
}
