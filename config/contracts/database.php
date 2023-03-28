<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Database Credentials
 *
 */

return [
    'charset' => $env->get('MYSQL_CHARSET'),
    'host' => $env->get('MYSQL_HOST', 'localhost'),
    'name' => $env->get('MYSQL_DATABASE'),
    'password' => $env->get('MYSQL_PASSWORD'),
    'port' => $env->get('MYSQL_PORT', '3306'),
    'socket' => $env->get('MYSQL_SOCKET'),
    'user' => $env->get('MYSQL_USER'),

    'options' => [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];
