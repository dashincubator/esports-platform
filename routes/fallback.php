<?php

use App\Http\Actions\Web;

/**
 *------------------------------------------------------------------------------
 *
 *  Fallback Route
 *  - Typically 404
 *
 */

$r->get('fallback', '', Web\Error\NotFound\Action::class);
