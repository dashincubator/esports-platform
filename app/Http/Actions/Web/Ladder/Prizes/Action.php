<?php

namespace App\Http\Actions\Web\Ladder\Prizes;

use App\Http\Actions\AbstractAction;
use Contracts\Configuration\Configuration;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $config;


    public function __construct(Configuration $config, Responder $responder)
    {
        $this->config = $config;
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        $ladder = $request->getAttributes()->get('ladder');
        $info = array_merge($ladder->getInfo(), $this->config->get('pages.ladder.prizes'));

        return $this->responder->handle(compact('info', 'ladder'));
    }
}
