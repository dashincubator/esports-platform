<?php

namespace App\Http\Actions\Commands\Ladder\Match\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(string $platform, string $game, string $ladder) : Response
    {
        return $this->redirect->render('ladder.matchfinder', compact('platform', 'game', 'ladder'));
    }
}
