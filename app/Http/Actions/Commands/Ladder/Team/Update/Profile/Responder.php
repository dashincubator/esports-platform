<?php

namespace App\Http\Actions\Commands\Ladder\Team\Update\Profile;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(bool $isAjax, string $platform, string $game, string $ladder, string $team, bool $updated) : Response
    {
        if ($isAjax) {
            return $this->json->render([
                'messages' => $this->flash->toArray(),
                'success' => $updated
            ]);
        }

        return $this->redirect->render('ladder.team', compact('platform', 'game', 'ladder', 'team'));
    }
}
