<?php

// TODO: Cleanup + Replace Ticket Based System With Bank System
namespace App\Http\Actions\Commands\User\Bank\Deposit\Square\Checkout;

use App\User;
use App\Organization;
use App\Http\Actions\AbstractAction;
use App\Commands\User\Bank\Deposit\TempStripe\Command;
use Contracts\Configuration\Configuration;
use Contracts\Http\{Request, Response};
use Contracts\Routing\Router;
use Contracts\UUID\RandomGenerator;

use System\Payment\Square\Checkout as Square;

class Action extends AbstractAction
{

    private $command;

    private $config;

    private $router;

    private $square;

    private $uuid;

    private $user;


    public function __construct(
        Command $command,
        Configuration $config,
        Organization $organization,
        RandomGenerator $uuid,
        Responder $responder,
        Router $router,
        Square $square,
        User $user
    ) {
        $this->command = $command;
        $this->config = $config;
        $this->organization = $organization;
        $this->responder = $responder;
        $this->router = $router;
        $this->square = $square;
        $this->uuid = $uuid;
        $this->user = $user;
    }


    public function handle(Request $request) : Response
    {
        $amount = 0;
        $tickets = floor(trim($request->getInput()->get('amount', 0), '$'));
        $domain = str_replace('http:', 'https:', $request->getDomain());

        $amount = $this->config->get('ticket.cost') * $tickets;
        $amount = number_format($amount + (($amount * 0.03) + 0.30), 2);

        $data = [
            'idempotency_key' => $this->uuid->generate(),
            'ask_for_shipping_address' => false,
            'merchant_support_email' => $this->config->get('square.email'),
            'redirect_url' => $domain . $this->router->uri('account.bank.square.webhook.command'),
            'order' => [
                'idempotency_key' => $this->uuid->generate(),
                'location_id' => $this->config->get('square.location'),
                'order' => [
                    'location_id' => $this->config->get('square.location'),
                    'line_items' => [
                        [
                            'quantity' => "{$tickets}",
                            'base_price_money' => [
                                'amount' => $this->config->get('ticket.cost') * 100,
                                'currency' => $this->config->get('bank.currency')
                            ],
                            'name' => "{$this->organization->getName()} Bank Deposit"
                        ]
                    ]
                ]
            ]
        ];

        // TODO: Replace With Production
        $data = $this->square->useSandbox()->create($data);
        $success = false;
        $url = $data['checkout']['checkout_page_url'] ?? '';

        if (!$url) {
            $this->responder->error('Square payment checkout failed to process deposit');
        }
        else {
            $success = $this->execute($this->command, [
                'amount' => $amount,
                'organization' => $this->organization->getId(),
                'processor' => 'stripe',
                'processorTransaction' => $data,
                'processorTransactionId' => $data['checkout']['order']['id'],
                'user' => $this->user->getId(),
            ])->getResult();
        }

        return $this->responder->handle(($url && $success) ? $url : 'index');
    }
}
