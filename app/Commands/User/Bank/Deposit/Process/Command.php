<?php

namespace App\Commands\User\Bank\Deposit\Process;

use Contracts\Calculator\Calculator;
use Contract\Configuration\Configuration;
use App\Commands\AbstractCommand;
use App\DataSource\User\Bank\Mapper as BankMapper;
use App\DataSource\User\Bank\Deposit\Mapper as DepositMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Calculator $calculator, Configuration $config, BankMapper $bank, DepositMapper $deposit, Filter $filter)
    {
        $this->calculator = $calculator;
        $this->config = $config;
        $this->filter = $filter;
        $this->mapper = compact('bank', 'deposit');
    }


    protected function run(int $id) : bool
    {
        $deposit = $this->mapper['deposit']->findById($id);

        if ($deposit->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else if ($deposit->isAlreadyProcessed()) {
            $this->filter->writeAlreadyProcessedMessage($id);
        }

        if (!$this->filter->hasErrors()) {
            $bank = $this->mapper['bank']->findByOrganizationAndUser($deposit->getOrganization(), $deposit->getUser());

            $this->mapper['bank']->transaction(function() use ($bank, $deposit) {
                $deposit->processed();
                $updated = $this->mapper['deposit']->update($deposit);

                if ($updated) {
                    $bank->deposit($deposit->getAmount());
                    $this->mapper['bank']->replace($bank);

                    $this->filter->writeSuccessMessage();
                }
            });
        }

        return !$this->filter->hasErrors();
    }
}
