<?php

namespace App\Http\Actions\Commands\Ladder\Payout;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(int $id, bool $paid) : Response
    {
        if ($paid) {
            return $this->redirect->render('index', compact('id'));
        }

        return $this->redirect->render('admincp.ladder.edit', compact('id'));
    }
}
