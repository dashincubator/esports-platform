<?php

namespace App;

use App\Organization;
use App\DataSource\User\Bank\{Entity as BankEntity, Mapper as BankMapper};
use App\DataSource\User\Entity as UserEntity;
use App\DataSource\User\Account\{Entities as AccountEntities, Mapper as AccountMapper};
use App\DataSource\User\Admin\Position\Entity as AdminPositionEntity;
use Contracts\Support\Arrayable;
use Exception;

class User implements Arrayable
{

    // Account Entities
    private $accounts;

    private $bank;

    private $mapper;

    private $passthrough = [
        'position' => [
            'can',
            'getOrganization',
            'managesGame'
        ],
        'user' => [
            'isAdmin',
            'isGuest',
            'isLocked',
            'isMembershipActive'
        ]
    ];

    private $organization;

    private $position;

    private $user;


    public function __call(string $method, array $args)
    {
        if (in_array($method, $this->passthrough['position'])) {
            return $this->position->{$method}(...$args);
        }

        if (mb_substr($method, 0, 3) === 'get' || in_array($method, $this->passthrough['user'])) {
            return $this->user->{$method}(...$args);
        }

        throw new Exception("'{$method}' Is Not A Valid '" . User::class . "' Method");
    }


    public function __construct(BankMapper $bank, AccountMapper $account, Organization $organization, UserEntity $user, AdminPositionEntity $position)
    {
        $this->mapper = compact('account', 'bank');
        $this->organization = $organization;
        $this->position = $position;
        $this->user = $user;
    }


    private function getAccounts() : AccountEntities
    {
        if (is_null($this->accounts)) {
            $this->accounts = $this->mapper['account']->findByUser($this->user->getId());
        }

        return $this->accounts;
    }


    public function getAccountsByName(string $name) : AccountEntities
    {
        return $this->getAccounts()->findByName($name);
    }


    public function getAdminPosition() : AdminPositionEntity
    {
        return $this->position;
    }


    private function getBank() : BankEntity
    {
        if (is_null($this->bank) && !$this->user->isGuest()) {
            $this->bank = $this->mapper['bank']->findByOrganizationAndUser($this->organization->getId(), $this->user->getId());
        }

        return $this->bank;
    }


    public function getBankBalance() : float
    {
        return $this->getBank()->getBalance();
    }


    public function getBankWithdrawable() : float
    {
        return $this->getBank()->getWithdrawable();
    }


    public function toArray() : array
    {
        return $this->user->toArray();
    }
}
