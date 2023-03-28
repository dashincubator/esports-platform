<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Application Cookie Configuration
 *
 */

return [

    /**
     *--------------------------------------------------------------------------
     *
     *  Encryption Settings
     *
     *  'key' => Encryption Secret
     *  'use' => Whether Or Not Session Data Should Be Encrypted When Stored
     *  'skip' => Cookie Names To Skip Decryption/Encryption Process
     *
     */

    'encryption' => [
        'key' => $env->get('COOKIE_ENCRYPTION_KEY', ''),
        'use' => false,

        'skip' => [

        ]
    ]
];
