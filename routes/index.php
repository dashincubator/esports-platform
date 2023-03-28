<?php

use App\Http\Actions\Web;

/**
 *------------------------------------------------------------------------------
 *
 *  Homepage
 *
 */

$r->get('index', '/', Web\Index\Action::class);
$r->get('temp', '/temp', Web\Temp\Action::class);
