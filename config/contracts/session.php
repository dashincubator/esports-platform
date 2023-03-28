<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Application Session Configuration
 *
 */

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  Session Cache Settings
     *
     *  'client' => Cache Client Name
     *  'prefix' => Cache Key Prefix
     *
     */

    'cache' => [
        'client' => 'session',
        'prefix' => $config->get('app.name', '')
    ],


    /**
     *--------------------------------------------------------------------------
     *
     *  Session Cookie Settings
     *
     *  'domain' => Domain Of The Cookie
     *  'isHttpOnly' => Whether Or Not The Cookie Is HTTP-Only
     *  'isSecure' => Requires HTTPS, Only Sent When HTTPS Connection Is Used
     *  'path' => Path Of The Cookie
     *
     */

    'cookie' => [
        'domain' => $env->get('SESSION_COOKIE_DOMAIN', ''),
        'isHttpOnly' => true,
        'isSecure' => $env->get('SESSION_COOKIE_IS_SECURE', false),
        'path' => $env->get('SESSION_COOKIE_PATH', '/')
    ],


    /**
     *--------------------------------------------------------------------------
     *
     *  Encryption Settings
     *
     *  'key' => Encryption Secret
     *  'use' => Whether Or Not Session Data Should Be Encrypted When Stored
     *
     */

    'encryption' => [
        'key' => $env->get('SESSION_ENCRYPTION_KEY', ''),
        'use' => false
    ],


    /**
     *--------------------------------------------------------------------------
     *
     *  Lifetime In Seconds
     *  - Currently 6 Hours
     *
     */

    'lifetime' => (60 * 60) * 6,


    /**
     *--------------------------------------------------------------------------
     *
     *  Session Name
     *
     */

    'name' => 'SID',


    /**
     *--------------------------------------------------------------------------
     *
     *  CSRF Cookie Settings
     *
     *  'lifetime' => Lifetime In Seconds
     *
     */

    'csrf' => [
        'lifetime' => 7200
    ]
];
