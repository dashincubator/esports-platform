<?php

namespace App\Http\Actions\Web\Account\Bank;

use App\User;
use App\DataSource\User\Bank\Deposit\Mapper as DepositMapper;
use App\DataSource\User\Bank\Transaction\Mapper as TransactionMapper;
use App\DataSource\User\Bank\Withdraw\Mapper as WithdrawMapper;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private const PER_PAGE = 25;


    private $mapper;

    private $user;


    public function __construct(
        DepositMapper $deposit,
        Responder $responder,
        TransactionMapper $transaction,
        User $user,
        WithdrawMapper $withdraw
    ) {
        $this->mapper = compact('deposit', 'transaction', 'withdraw');
        $this->responder = $responder;
        $this->user = $user;
    }


    public function deposits(Request $request, int $page = 1) : Response
    {
        return $this->handle($request, $page, 'deposits');
    }


    private function handle(Request $request, int $page, string $type) : Response
    {
        $limit = self::PER_PAGE;
        $total = $this->mapper[trim($type, 's')]->countByUser($this->user->getId());
        $pages = ceil($total / $limit);

        if ($page > $pages) {
            $page = 1;
        }

        $data = compact('limit', 'page', 'pages', 'total', 'type');
        $data[$type] = $this->mapper[trim($type, 's')]->findByUser($this->user->getId(), $limit, $page);

        return $this->responder->handle($data);
    }


    public function transactions(Request $request, int $page = 1) : Response
    {
        return $this->handle($request, $page, 'transactions');
    }


    public function withdraws(Request $request, int $page = 1) : Response
    {
        return $this->handle($request, $page, 'withdraws');
    }
}
