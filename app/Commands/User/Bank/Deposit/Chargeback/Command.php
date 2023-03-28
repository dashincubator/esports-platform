<?php

namespace App\Commands\User\Bank\Deposit\Chargeback;

use App\Commands\AbstractCommand;
use App\Commands\User\Lock\Command as LockCommand;
use App\Commands\User\Bank\Deposit\Filter;
use App\DataSource\User\Bank\Deposit\Mapper;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(Filter $filter, LockCommand $command, Mapper $mapper)
    {
        $this->command = $command;
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(
        float $amount,
        string $email,
        float $fee,
        string $processor,
        array $processorTransaction,
        string $processorTransactionId,
        int $user
    ) : bool
    {
        $deposit = $this->mapper->create(compact($this->filter->getFields()));
        $deposit->chargeback();

        $this->delegate($this->command, [
            'content' => [
                "Disputed paypal transaction id '{$deposit->getProcessorTransactionId()}'"
            ],
            'id' => $user
        ]);

        if (!$this->filter->hasErrors()) {
            $this->mapper->insert($deposit);
        }

        return $this->booleanResult();
    }
}
