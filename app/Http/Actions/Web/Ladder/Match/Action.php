<?php

namespace App\Http\Actions\Web\Ladder\Match;

use App\Http\Actions\AbstractAction;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\Services\Match\MatchUI as MatchUIService;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $mapper;

    private $service;


    public function __construct(LadderMapper $ladder, MatchUIService $match, Responder $responder)
    {
        $this->mapper = compact('ladder');
        $this->responder = $responder;
        $this->service = compact('match');
    }


    public function handle(Request $request) : Response
    {
        $match = $request->getAttributes()->get('match');
        $ladder = $this->mapper['ladder']->findById($match->getladder());

        return $this->responder->handle(array_merge(
            $this->service['match']->ladder($match, [
                'accountModifiedAt' => ($match->isActive() ? $match->getStartedAt() : null),
                'game' => $ladder->getGame(),
                'membership' => ($match->isActive() ? $ladder->isMembershipRequired() : null)
            ], $ladder->getGame()),
            compact('ladder', 'match')
        ));
    }
}
