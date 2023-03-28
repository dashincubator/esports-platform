<?php

namespace App\Http\Actions\Commands\Ladder\Team\Lock;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(bool $locked, string $platform, string $game, string $ladder, string $team) : Response
    {
        return $this->redirect->render('ladder.team', compact('platform', 'game', 'ladder', 'team'));
    }
}
