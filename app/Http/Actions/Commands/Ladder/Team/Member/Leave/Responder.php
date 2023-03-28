<?php

namespace App\Http\Actions\Commands\Ladder\Team\Member\Leave;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(string $platform, string $game, string $ladder, string $team) : Response
    {
        return $this->redirect->render('ladder.team', compact('platform', 'game', 'ladder', 'team'));
    }
}
