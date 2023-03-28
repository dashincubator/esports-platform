<?php

namespace App\Commands\User\Eligibility;

use App\Organization;
use App\Commands\AbstractCommand;
use App\DataSource\Game\{Entity as GameEntity, Mapper as GameMapper};
use App\DataSource\Game\Api\Match\Mapper as GameApiMatchMapper;
use App\DataSource\User\Bank\Mapper as BankMapper;
use App\DataSource\User\{Entities as UserEntities, Mapper as UserMapper};
use App\DataSource\User\Account\{Entities as AccountEntities, Mapper as AccountMapper};
use App\Services\Api\Managers;
use Exception;

class Command extends AbstractCommand
{

    private $managers;

    private $mapper;

    private $organization;


    public function __construct(
        AccountMapper $account,
        BankMapper $bank,
        Filter $filter,
        GameMapper $game,
        GameApiMatchMapper $gameMatch,
        Managers $managers,
        Organization $organization,
        UserMapper $user
    ) {
        $this->filter = $filter;
        $this->managers = $managers;
        $this->mapper = compact('account', 'bank', 'game', 'gameMatch', 'user');
        $this->organization = $organization;
    }


    protected function run(?int $accountModifiedAt, ?float $amount, int $game, ?bool $gameStat, ?bool $membership, array $users, ?bool $wagers) : bool
    {
        $game = $this->mapper['game']->findById($game);
        $users = $this->mapper['user']->findByIds(...$users);

        if ($game->isEmpty() || $users->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $accounts = $this->mapper['account']->findByNameAndUsers($game->getAccount(), ...$users->column('id'));

            if ($amount > 0) {
                $this->verifyBankBalances($users, $amount);
            }

            if ($membership) {
                $this->verifyMemberships($users);
            }

            if ($wagers) {
                $this->verifyWagerSettings($users);
            }

            $this->verifyGameAccounts($accounts, $game, $users, $accountModifiedAt);

            if ($gameStat && !$this->filter->hasErrors()) {
                $this->verifyGameStats($accounts, $game, $users);
            }
        }

        return !$this->filter->hasErrors();
    }


    private function verifyBankBalances(UserEntities $users, float $amount) : void
    {
        $banks = $this->mapper['bank']->findByOrganizationAndUsers($this->organization->getId(), ...$users->column('id'));

        foreach ($banks as $bank) {
            if ($bank->isChargeable($amount)) {
                continue; 
            }

            $this->filter->writeInsufficientFundsMessage($users->get('id', $bank->getUser())->getUsername());
        }
    }


    private function verifyGameAccounts(AccountEntities $accounts, GameEntity $game, UserEntities $users, ?int $accountModifiedAt) : void
    {
        foreach ($users as $user) {
            $account = $accounts->findByUser($user->getId());

            if (!$account->isEmpty()) {
                if ($accountModifiedAt && $account->createdAfter($accountModifiedAt)) {
                    $this->filter->writeModifiedGameAccountMessage($game->getAccount(), $user->getUsername());
                }

                continue;
            }

            $this->filter->writeMissingGameAccountMessage($game->getAccount(), $user->getUsername());
        }
    }


    private function verifyGameStats(AccountEntities $accounts, GameEntity $game, UserEntities $users) : void
    {
        $api = $this->managers->getApiManagingAccount($game->getAccount());
        $matches = $this->mapper['gameMatch']->findByApiAndUsernames($api, time(), 0, ...$accounts->column('value'));

        foreach ($users as $user) {
            if ($matches->has('username', $accounts->findByUser($user->getId())->getFirstValue())) {
                continue;
            }

            $this->filter->writeInvalidGameAccountMessage($game->getAccount(), $user->getUsername());
        }
    }


    private function verifyMemberships(UserEntities $users) : void
    {
        foreach ($users as $user) {
            if ($user->isMembershipActive()) {
                continue;
            }

            $this->filter->writeExpiredMembershipMessage($user->getUsername());
        }
    }


    private function verifyWagerSettings(UserEntities $users) : void
    {
        foreach ($users as $user) {
            if ($user->getWagers()) {
                continue;
            }

            $this->filter->writeWagersNotAllowedMessage($user->getUsername());
        }
    }
}
