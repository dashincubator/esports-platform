<?php

/**
 *------------------------------------------------------------------------------
 *
 *  "Extend" Base Application Boot Configuration
 *
 */

$app = require realpath(__DIR__ . '/..') . '/app.php';

/**
 *------------------------------------------------------------------------------
 *
 *  HTTP Application Middleware
 *
 */

use App\Http\Middleware;

$app['middleware'] = [
    Middleware\Boot\SetCacheHeaders::class,
    Middleware\Boot\CookieEncryption::class,
    Middleware\Boot\Sessions::class,
    Middleware\Boot\Flash::class,

    Middleware\Guard\BlockXFrame::class,
    Middleware\Guard\CsrfTokens::class,
    Middleware\Guard\ForceHttps::class,
    Middleware\Guard\ValidatePostSize::class,

    Middleware\User\CheckLock::class,
    Middleware\User\SetTimezone::class,
    Middleware\BindViewExtensionWithRoute::class,
];

/**
 *------------------------------------------------------------------------------
 *
 *  Http Application Providers
 *
 */

use App\Bootstrap\Providers;

$app['providers'] = array_merge($app['providers'], [
    Providers\Http\Middleware\CookieEncryptionProvider::class,
    Providers\Http\Middleware\CsrfTokensProvider::class,
    Providers\Http\Middleware\DebugRequiredProvider::class,
    Providers\Http\Middleware\SessionsProvider::class,
    Providers\Http\UserProvider::class
]);

/**
 *------------------------------------------------------------------------------
 *
 *  Http Application Stages
 *
 */

use App\Bootstrap\Stages;

$app['stages'] = array_merge($app['stages'], [
    Stages\Http\ParseRequest::class,
    Stages\Http\SetHost::class,
    Stages\Http\VerifyOrganization::class,
    Stages\Http\MatchRoute::class,
    Stages\Http\Dispatch::class
]);

/**
 *------------------------------------------------------------------------------
 *
 *  Return Http Application Boot Configuration
 *
 */

return $app;
