<?php

namespace App\View\Extensions;

$views = $config->get('paths.resources.root') . '/views';

/**
 *------------------------------------------------------------------------------
 *
 *  Application View Engine Configuration
 *
 */

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  View Extensions
     *
     *  Wrapped In A Proxy To Safely Escape User Generated Content
     *
     */

    'extensions' => [
        'anchor' => Anchor::class,
        'auth' => Auth::class,
        'avg' => Avg::class,
        'bank' => Bank::class,
        'config' => Config::class,
        'drawer' => Drawer::class,
        'flash' => Flash::class,
        'game' => Game::class,
        'header' => Header::class,
        'html' => Html::class,
        'leaderboard' => Leaderboard::class,
        'ladder' => Ladder::class,
        'ladderMatch' => LadderMatch::class,
        'modal' => Modal::class,
        'ordinal' => Ordinal::class,
        'organization' => Organization::class,
        'platform' => Platform::class,
        'roster' => Roster::class,
        'route' => Route::class,
        'seo' => Seo::class,
        'svg' => Svg::class,
        'time' => Time::class,
        'upload' => Upload::class,
        'user' => User::class
    ],


    /**
     *--------------------------------------------------------------------------
     *
     *  View Paths
     *
     *  During View Rendering Keys Are Replaced With Values ( Paths )
     *
     */

    'paths' => [
        'components' => "{$views}/components",
        'layouts' => "{$views}/layouts",
        'pages' => "{$views}/pages",
        'views' => $views
    ]
];
