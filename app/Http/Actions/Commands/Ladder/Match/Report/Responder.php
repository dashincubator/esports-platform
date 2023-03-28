<?php

namespace App\Http\Actions\Commands\Ladder\Match\Report;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(string $platform, string $game, string $ladder, int $match) : Response
    {
        return $this->redirect->render('ladder.match', compact('platform', 'game', 'ladder', 'match'));
    }
}
