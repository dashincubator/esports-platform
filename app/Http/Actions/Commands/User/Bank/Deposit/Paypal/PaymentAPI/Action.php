<?php

namespace App\Http\Actions\Commands\User\Bank\Deposit\Paypal\PaymentAPI;

use App\User;
use App\Organization;
use App\Http\Actions\AbstractAction;
use App\Commands\User\Bank\Deposit\Create\Command;
use Contracts\Configuration\Configuration;
use Contracts\Http\{Request, Response};
use Contracts\Routing\Router;

class Action extends AbstractAction
{

    private $command;

    private $config;

    private $router;

    private $user;


    public function __construct(Command $command, Configuration $config, Organization $organization, Responder $responder, Router $router, User $user)
    {
        $this->command = $command;
        $this->config = $config;
        $this->organization = $organization;
        $this->responder = $responder;
        $this->router = $router;
        $this->user = $user;
    }


    public function handle(Request $request) : Response
    {
        $amount = trim($request->getInput()->get('amount', 0), '$');
        $amount = number_format($amount + (($amount * 0.03) + 0.30), 2);

        $domain = str_replace('http:', 'https:', $request->getDomain());

        $data = [
            'amount' => $amount,
            'business' => $this->organization->getPaypal(),
            'cmd' => '_xclick',
            'currency_code' => $this->config->get('bank.currency'),
            'custom' => $this->user->getId(),
            'item_name' => "{$this->organization->getName()} Bank Deposit",
            'notify_url' => $domain . $this->router->uri('account.bank.paypal.webhook.command')
        ];

        return $this->responder->handle($this->execute($this->command, $data)->getResult(), $data);
    }
}
