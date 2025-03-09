<?php

return [
    'env' => env('MWALONI_ENV', 'sandbox'), // sandbox or production
    'debug' => env('MWALONI_DEBUG', true),
    'sandbox' => [
        'base_url' => env('MWALONI_BASE_URL', 'https://wallet-stg.mwaloni.com/api/'),
        'service_id' => env('MWALONI_SANDBOX_SERVICE_ID', ''),
        'username' => env('MWALONI_SANDBOX_USERNAME', ''),
        'password' => env('MWALONI_SANDBOX_PASSWORD', ''),
    ],
    'production' => [
        'base_url' => env('MWALONI_BASE_URL', 'https://wallet.mwaloni.com/api/'),
        'service_id' => env('MWALONI_SERVICE_ID', ''),
        'username' => env('MWALONI_USERNAME', ''),
        'password' => env('MWALONI_PASSWORD', ''),
    ],
];