<?php

namespace App\Commands\User\Membership\Purchase;

use App\Commands\AbstractCommand;
use App\Commands\User\Bank\Transaction\Charge\Command as BankChargeCommand;
use App\DataSource\User\Mapper;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;

    private $memberships;


    public function __construct(BankChargeCommand $charge, Filter $filter, Mapper $mapper, array $memberships)
    {
        $this->command = compact('charge');
        $this->filter = $filter;
        $this->mapper = $mapper;
        $this->memberships = $memberships;
    }


    protected function run(int $days, int $user) : bool
    {
        $index = array_search($days, array_column($this->memberships, 'days'));
        $membership = [];

        if ($index === false) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $membership = $this->memberships[$index];
            $memo = "Purchased {$membership['text']} ({$membership['days']} Days) Membership";

            $this->delegate($this->command['charge'], [
                'amount' => $membership['price'],
                'memo' => $memo,
                'users' => [$user]
            ]);
        }

        if (!$this->filter->hasErrors()) {
            $user = $this->mapper->findById($user);
            $user->updateMembershipTime($membership['days']);

            $this->mapper->update($user);
        }

        return $this->booleanResult(($membership['days'] ?? 0), ($membership['text'] ?? ''));
    }
}
