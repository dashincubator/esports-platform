<?php

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  Forgot Password Email Configuration
     *
     */

    'email' => [
        'body' => "
            Problems logging in? No worries, click the link below to reset your password.
            If you did not make this request, you can safely ignore this email.
            \n
            %s
        ",
        'from' => [
            'email' => 'noreply@gamrs.net',
            'name' => 'GAMRS'
        ],
        'subject' => 'GAMRS Forgot Password Request'
    ],

    /**
     *--------------------------------------------------------------------------
     *
     *  'expire' => Delete ForgotPassword Requests After x Minutes
     *
     */

    'expire' => 15
];
