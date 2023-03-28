<?php

namespace App\Commands\User\Bank\Withdraw\Create;

use App\Organization;
use App\Commands\AbstractCommand;
use App\DataSource\User\Bank\Mapper as BankMapper;
use App\DataSource\User\Bank\Withdraw\Mapper as WithdrawMapper;

class Command extends AbstractCommand
{

    private $mapper;

    private $organization;


    public function __construct(BankMapper $bank, Filter $filter, Organization $organization, WithdrawMapper $withdraw)
    {
        $this->filter = $filter;
        $this->mapper = compact('bank', 'withdraw');
        $this->organization = $organization;
    }


    protected function run(float $amount, string $email, string $processor, int $user) : bool
    {
        $withdraw = $this->mapper['withdraw']->create(array_merge(compact($this->filter->getFields()), [
            'organization' => $this->organization->getId()
        ]));
        $bank = $this->mapper['bank']->findByOrganizationAndUser($this->organization->getId(), $user);

        if ($bank->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            if ($bank->withdraw($withdraw->getAmount())) {
                $this->mapper['bank']->transaction(function() use ($bank, $withdraw) {
                    $this->mapper['withdraw']->transaction(function() use ($withdraw) {
                        $this->mapper['withdraw']->insert($withdraw);
                    });

                    $this->mapper['bank']->replace($bank);
                });
            }
            else {
                $this->filter->writeChargeFailedMessage($withdraw->getAmount());
            }
        }

        return $this->booleanResult();
    }
}
