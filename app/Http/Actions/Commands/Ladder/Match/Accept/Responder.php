<?php

namespace App\Http\Actions\Commands\Ladder\Match\Accept;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(string $platform, string $game, string $ladder, int $match = 0) : Response
    {
        return $this->redirect->render((($match > 0) ? 'ladder.match' : 'ladder.matchfinder'), compact('platform', 'game', 'ladder', 'match'));
    }
}
