<?php

return [
    'driver' => env('LARAVEL_SMS_DRIVER', 'ajuratech'),
    'services' => [
        'ajuratech' => [
            'handler' => \Nazmulpcc\LaravelSms\Services\Ajuratech::class,
            'api_key' => env('AJURATECH_API_KEY'),
            'secret_key' => env('AJURATECH_SECRET_KEY'),
            'caller_id' => env('AJURATECH_CALLER_ID'),
        ]
    ]
];
