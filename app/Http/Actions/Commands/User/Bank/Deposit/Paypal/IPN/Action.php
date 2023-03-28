<?php

namespace App\Http\Actions\Commands\User\Bank\Deposit\Paypal\IPN;

use App\Organization;
use App\Http\Actions\AbstractAction;
use App\Commands\User\Bank\Deposit\{Chargeback\Command as ChargebackCommand, Valid\Command as ValidCommand};
use Contracts\Http\{Request, Response};
use Contracts\Paypal\IPN as PaypalIPNService;

class Action extends AbstractAction
{

    private const PAYMENT_PROCESSOR = 'paypal';

    private const STATUS_CHARGEBACK = 'Reversed';

    private const STATUS_COMPLETE = 'Completed';


    private $command;

    private $ipn;

    private $organization;


    public function __construct(ChargebackCommand $chargeback, Organization $organization, PaypalIPNService $ipn, Responder $responder, ValidCommand $valid)
    {
        $this->command = compact('chargeback', 'valid');
        $this->ipn = $ipn;
        $this->organization = $organization;
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        $input = $request->getInput();

        if ($this->ipn->isValid()) {
            $data = [
                'amount' => $input->get('mc_gross', ''),
                'email' => $input->get('payer_email', ''),
                'fee' => $input->get('mc_fee', ''),
                'organization' => $this->organization->getId(),
                'processor' => self::PAYMENT_PROCESSOR,
                'processorTransaction' => $input->toArray(),
                'processorTransactionId' => $input->get('txn_id', ''),
                'user' => $input->get('custom', '')
            ];
            $status = $input->get('payment_status', '');

            if ($status === self::STATUS_COMPLETE) {
                $this->command['valid']->execute($data);
            }
            else if ($status === self::STATUS_CHARGEBACK) {
                $this->command['chargeback']->execute(array_merge($data, [
                    'processorTransactionId' => $input->get('parent_txn_id', $data['processorTransactionId'])
                ]));
            }
        }

        return $this->responder->handle();
    }
}
