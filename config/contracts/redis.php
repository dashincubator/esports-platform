<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Application Redis Configuration
 *
 */

return [
    'default' => [
        'password' => $env->get('REDIS_STORAGE_PASSWORD'),
        'socket' => $env->get('REDIS_STORAGE_SOCKET')
    ],
    'session' => [
        'password' => $env->get('REDIS_SESSION_PASSWORD'),
        'socket' => $env->get('REDIS_SESSION_SOCKET')
    ]
];
