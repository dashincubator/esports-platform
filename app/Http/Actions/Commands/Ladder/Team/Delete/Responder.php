<?php

namespace App\Http\Actions\Commands\Ladder\Team\Delete;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(bool $deleted, string $platform, string $game, string $ladder, string $team) : Response
    {
        if ($deleted) {
            return $this->redirect->render('ladder', compact('platform', 'game', 'ladder'));
        }

        return $this->redirect->render('ladder.team', compact('platform', 'game', 'ladder', 'team'));
    }
}
