<?php

use App\Http\Actions\Web;
use Contracts\Routing\Router;

$r->group(
    $r->name('legal.'),
    function (Router $r) {
        $r->get('deposit-terms', '/deposit-terms', Web\Legal\DepositTerms\Action::class);
        $r->get('privacy-policy', '/privacy-policy', Web\Legal\PrivacyPolicy\Action::class);
        $r->get('terms-of-service', '/terms-of-service', Web\Legal\TermsOfService\Action::class);
    }
);
