<?php

return [
    'env' => env('MWALONI_ENV', 'sandbox'), // sandbox or production
    'debug' => env('MWALONI_DEBUG', true),
    'sandbox' => [
        'service_id' => env('MWALONI_SANDBOX_SERVICE_ID', ''),
        'username' => env('MWALONI_SANDBOX_USERNAME', ''),
        'password' => env('MWALONI_SANDBOX_PASSWORD', ''),
    ],
    'production' => [
        'service_id' => env('MWALONI_SERVICE_ID', ''),
        'username' => env('MWALONI_USERNAME', ''),
        'password' => env('MWALONI_PASSWORD', ''),
    ],
];