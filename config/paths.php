<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Application Paths
 *
 */

$root = realpath(__DIR__ . '/..');

$public = "{$root}/public";
$resources = "{$root}/resources";
$storage = "{$root}/storage";
$uploads = "{$public}/uploads";

return [

    /**
     *---------------------------------------------------------------------------
     *
     *  App
     *
     */

    'app' => "{$root}/app",


    /**
     *--------------------------------------------------------------------------
     *
     *  Cache
     *
     */

    'cache' => [
        'app' => "{$storage}/app/cache",
        'system' => "{$storage}/system/cache"
    ],


    /**
     *--------------------------------------------------------------------------
     *
     *  Config
     *
     */

    'config' => "{$root}/config",


    /**
     *--------------------------------------------------------------------------
     *
     *  Contracts
     *
     */

    'contracts' => "{$root}/contracts",


    /**
     *--------------------------------------------------------------------------
     *
     *  DataSource
     *
     */

    'datasource' => "{$root}/app/DataSource",


    /**
     *--------------------------------------------------------------------------
     *
     *  Logs
     *
     */

    'logs' => "{$storage}/logs",


    /**
     *--------------------------------------------------------------------------
     *
     *  Public
     *
     */

    'public' => [
        'svg' => "{$public}/svg"
    ],


    /**
     *--------------------------------------------------------------------------
     *
     *  Resources
     *
     */

    'resources' => [
        'root' => $resources,

        'assets' => "{$resources}/assets",
        'svg' => "{$resources}/assets/svg",
        'views' => "{$resources}/views"
    ],


    /**
     *--------------------------------------------------------------------------
     *
     *  Root Directory
     *
     */

    'root' => $root,


    /**
     *--------------------------------------------------------------------------
     *
     *  Routes Directory
     *
     */

    'routes' => "{$root}/routes",


    /**
     *--------------------------------------------------------------------------
     *
     *  Src
     *
     */

    'src' => "{$root}/src",


    /**
     *--------------------------------------------------------------------------
     *
     *  Storage
     *
     */

    'storage' => $storage,


    /**
     *--------------------------------------------------------------------------
     *
     *  Upload Directories
     *
     */

    'uploads' => [
        'root' => $uploads,
        'game' => [
            'banner' => "{$uploads}/game/banner",
            'card' => "{$uploads}/game/card"
        ],
        'ladder' => [
            'banner' => "{$uploads}/ladder/banner",
            'card' => "{$uploads}/ladder/card",

            'team' => [
                'avatar' => "{$uploads}/ladder/team/avatar",
                'banner' => "{$uploads}/ladder/team/banner"
            ]
        ],
        'user' => [
            'avatar' => "{$uploads}/user/avatar",
            'banner' => "{$uploads}/user/banner"
        ]
    ],


    /**
     *--------------------------------------------------------------------------
     *
     *  Vendor Directory
     *
     */

    'vendor' => "{$root}/vendor"
];
