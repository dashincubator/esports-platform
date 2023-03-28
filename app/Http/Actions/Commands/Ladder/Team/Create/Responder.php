<?php

namespace App\Http\Actions\Commands\Ladder\Team\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(string $platform, string $game, string $ladder, string $team = '') : Response
    {
        $route = 'ladder';

        if ($team) {
            $route = 'ladder.team';
        }

        return $this->redirect->render($route, compact('platform', 'game', 'ladder', 'team'));
    }
}
