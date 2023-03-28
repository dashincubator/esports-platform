<?php

namespace App\Http\Actions\Web\Ladder\Rules;

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
        $rules = [
            [
                'title' => 'Event Rules',
                'sections' => [
                    [
                        'title' => 'Event Rules',
                        'sections' => $ladder->getRules()
                    ]
                ]
            ],
            [
                'title' => 'General Rules',
                'sections' => $this->config->get('pages.ladder.rules')
            ]
        ];

        return $this->responder->handle(compact('ladder', 'rules'));
    }
}
