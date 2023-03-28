<?php

namespace App\Commands\User\Bank\Withdraw\Process;

use App\Commands\AbstractCommand;
use App\DataSource\User\Bank\Withdraw\Mapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(int $id, string $processorTransactionId) : bool
    {
        $withdraw = $this->mapper->findById($id);

        if ($withdraw->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif (!$this->mapper->isUniqueTransaction($processorTransactionId)) {
            $this->filter->writeDuplicateTransactionMessage();
        }

        if (!$this->filter->hasErrors()) {
            $fee = ($withdraw->getAmount() * 0.029) + 0.30;
            $fee = round($fee);
            $fee = number_format($fee, 2);

            $withdraw->processed($fee, $processorTransactionId);
            $this->mapper->update($withdraw);
        }

        return $this->booleanResult();
    }
}
