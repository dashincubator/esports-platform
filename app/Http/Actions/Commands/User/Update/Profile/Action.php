<?php

namespace App\Http\Actions\Commands\User\Update\Profile;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\User\Update\Profile\Command;
use Contracts\Http\{Request, Response};
use Contracts\Configuration\Configuration;

class Action extends AbstractAction
{

    private $accounts;

    private $command;

    private $user;


    public function __construct(Configuration $config, Command $command, Responder $responder, User $user)
    {
        $this->accounts = array_merge(array_keys($config->get('social.accounts')), array_keys($config->get('game.accounts')));
        $this->command = $command;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request) : Response
    {
        $data = $request->getInput()->intersect(['bio', 'email', 'timezone', 'wagers']);
        
        return $this->responder->handle($request, $this->execute($this->command, array_merge(
            $data,
            $request->getFiles()->intersect(['avatar', 'banner']),
            [
                'accounts' => $request->getInput()->intersect($this->accounts),
                'id' => $this->user->getId(),
                'wagers' => (array_key_exists('wagers', $data) ? ((bool) $data['wagers']) : null)
            ]
        ))->getResult());
    }
}
