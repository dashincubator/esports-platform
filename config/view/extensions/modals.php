<?php

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  Include The Following Modals On Every Render
     *
     */

    'global' => [
        '@components/game/modals/events',
    ],

    /**
     *--------------------------------------------------------------------------
     *
     *  Include The Following Modals When User Is Guest
     *
     */

    'guest' => [
        '@components/account/modals/forgot-password',
        '@components/account/modals/sign'
    ],

    /**
     *--------------------------------------------------------------------------
     *
     *  Include The Following Modals When User Is Logged In
     *
     */

    'user' => [
        '@components/account/modals/bank-deposit',
        //'@components/account/modals/bank-withdraw'
    ]
];
