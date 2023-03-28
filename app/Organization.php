<?php

namespace App;

use App\DataSource\Organization\Entity as OrganizationEntity;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Account\{Entities as AccountEntities, Mapper as AccountMapper};
use Contracts\Support\Arrayable;
use Exception;

class Organization implements Arrayable
{

    private $accounts;

    private $mapper;

    private $organization;

    private $user;


    public function __call(string $method, array $args)
    {
        return $this->organization->{$method}(...$args);
    }


    public function __construct(AccountMapper $account, OrganizationEntity $organization, UserMapper $user)
    {
        $this->mapper = compact('account', 'user');
        $this->organization = $organization;
    }


    public function getAccounts() : AccountEntities
    {
        if (is_null($this->accounts)) {
            $this->accounts = $this->mapper['account']->findByUser($this->organization->getUser());
        }

        return $this->accounts;
    }


    public function getAvatar() : string
    {
        return $this->getUser()->getAvatar();
    }


    public function getBanner() : string
    {
        return $this->getUser()->getBanner();
    }


    private function getUser()
    {
        if (is_null($this->user)) {
            $this->user = $this->mapper['user']->findById($this->organization->getUser());
        }

        return $this->user;
    }


    public function toArray() : array
    {
        return $this->organization->toArray();
    }
}
