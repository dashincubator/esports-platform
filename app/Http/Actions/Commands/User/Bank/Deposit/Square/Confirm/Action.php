<?php

namespace App\Http\Actions\Commands\User\Bank\Deposit\Square\Confirm;

use App\Organization;
use App\User;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};
use App\DataSource\User\Bank\Deposit\Mapper;

class Action extends AbstractAction
{

    public function __construct(
        Mapper $mapper,
        Organization $organization,
        Responder $responder,
        User $user
    ) {
        $this->mapper = $mapper;
        $this->organization = $organization;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request) : Response
    {
        $input = $request->getInput();
        $deposit = $this->mapper->findTransaction(
            $this->organization->getId(),
            'stripe',
            $input->get('transactionId'),
            $this->user->getId()
        );

        if ($deposit->isEmpty()) {
            $this->responder->error('Invalid Square transaction');
        }
        else {
            $deposit->verified();
            $this->mapper->update($deposit);

            $this->mapper->scheduleProcessBankDepositJob([
                'id' => $deposit->getId()
            ]);

            $this->responder->success('Square transaction processed successfully! Your bank balance will be updated shortly');
        }

        return $this->responder->handle();
    }
}
