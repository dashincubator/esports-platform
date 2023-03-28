<?php

return [
    'routes' => [

        /**
         *----------------------------------------------------------------------
         *
         *  Skip CSRF Token Check On Following Route Names
         *
         */

        'skip' => [
            'account.bank.paypal.webhook.command',
            'assets.css',
            'assets.images',
            'assets.js',
            'assets.uploads'
        ]
    ]
];
