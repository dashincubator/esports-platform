<?php

namespace App\Http\Actions;

use App\Flash;
use App\Commands\Response as CommandResponse;
use App\Http\Responders\{Html, Json, Redirect};

abstract class AbstractResponder
{

    protected $flash;

    protected $html;

    protected $json;

    protected $redirect;


    public function __construct(Flash $flash, Html $html, Json $json, Redirect $redirect)
    {
        $this->flash = $flash;
        $this->html = $html;
        $this->json = $json;
        $this->redirect = $redirect;
    }


    public function error($message) : void
    {
        $this->flash->error($message);
    }


    public function success($message) : void 
    {
        $this->flash->success($message);
    }


    public function flash(CommandResponse $response, array $input = []) : void
    {
        $this->flash->error($response->getErrorMessages());
        $this->flash->success($response->getSuccessMessages());

        if ($response->hasErrors() && $input) {
            $this->flash->input($input);
        }
    }
}
