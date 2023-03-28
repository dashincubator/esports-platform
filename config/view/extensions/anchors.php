<?php

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  Include The Following Anchors On Every Render
     *
     */

    'global' => [
        '@components/anchors/alerts/errors',
        '@components/anchors/alerts/success',
        '@components/anchors/scrolltop'
    ],
  
    /**
     *--------------------------------------------------------------------------
     *
     *  Include The Following Anchors When User Is Guest
     *
     */

    'guest' => [],

    /**
     *--------------------------------------------------------------------------
     *
     *  Include The Following Anchors When User Is Logged In
     *
     */

    'user' => []
];
