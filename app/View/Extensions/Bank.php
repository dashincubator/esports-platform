<?php

namespace App\View\Extensions;

use Contracts\View\Extensions\Data;

class Bank
{

    private $time;


    public function __construct(Time $time)
    {
        $this->time = $time;
    }


    public function toDepositsTableItemArray(Data $deposit) : array
    {
        return array_merge($this->toTableItemArray($deposit), [
            'email' => $deposit['email'],
            'status' => ($deposit['processedAt'] > 0 ? 'Deposit Complete' : 'Processing'),
            'transactionId' => ($deposit['processorTransactionId'] ? $deposit['processorTransactionId'] : '-')
        ]);
    }


    private function toTableItemArray(Data $data) : array
    {
        return [
            'amount' => '$' . number_format($data['amount'], 2),
            'date' => $this->time->toBankFormat($data['createdAt']),
            'fee' => ($data['fee'] > 0 ? '$' . number_format($data['fee'], 2) : '-')
        ];
    }


    public function toTransactionsTableItemArray(Data $transaction) : array
    {
        return array_merge($this->toTableItemArray($transaction), [
            'memo' => $transaction['memo'],
            'status' => ($transaction['refundedAt'] ? 'Refunded' : 'Transaction Complete')
        ]);
    }


    public function toWithdrawsTableItemArray(Data $withdraw) : array
    {
        return array_merge($this->toTableItemArray($withdraw), [
            'email' => $withdraw['email'],
            'status' => ($withdraw['processedAt'] > 0 ? 'Withdraw Complete' : 'Processing'),
            'transactionId' => ($withdraw['processorTransactionId'] ? $withdraw['processorTransactionId'] : '-')
        ]);
    }
}
