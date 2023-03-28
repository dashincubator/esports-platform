<?php

use App\Http\Actions\Web;

/**
 *------------------------------------------------------------------------------
 *
 *  Frequently Asked Questions
 *
 */

$r->get('faq', '/faq', Web\Faq\Action::class);
