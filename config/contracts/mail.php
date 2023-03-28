<?php

return [
    'host' => $env->get('MAIL_HOST'),
    'persist' => true,
    'port' => $env->get('MAIL_PORT'),
    'protocol' => $env->get('MAIL_PROTOCOL'),
    
    'password' => $env->get('MAIL_PASSWORD'),
    'username' => $env->get('MAIL_USERNAME')
];
