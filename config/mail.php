<?php

return [


    'driver' => env('MAIL_DRIVER', 'smtp'),

    'host' => env('MAIL_HOST', 'smtp.hostinger.com'),

    'port' => env('MAIL_PORT', 465),

    

    'from' => [
        'address' => 'camtool.novonordisk@hqr.com.co',
        'name' => "Novo-Nordisk Colombia",
    ],

   

    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),

    
    'username' => env('MAIL_USERNAME','camtool.novonordisk@hqr.com.co'),

    'password' => env('MAIL_PASSWORD','Novo123*'),

    'sendmail' => '/usr/sbin/sendmail -bs',

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

    'log_channel' => env('MAIL_LOG_CHANNEL'),

];

