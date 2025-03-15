<?php

return [
    'env' => env('MWALONI_ENV', 'sandbox'), // sandbox or production
    'debug' => env('MWALONI_DEBUG', true),
    'encryption_key' => env('MWALONI_ENCRYPTION_KEY', ''),
    'sandbox' => [
        'api_key' => env('MWALONI_SANDBOX_API_KEY', ''),
        'service_id' => env('MWALONI_SANDBOX_SERVICE_ID', ''),
        'username' => env('MWALONI_SANDBOX_USERNAME', ''),
        'password' => env('MWALONI_SANDBOX_PASSWORD', ''),
    ],
    'production' => [
        'api_key' => env('MWALONI_API_KEY', ''),
        'service_id' => env('MWALONI_SERVICE_ID', ''),
        'username' => env('MWALONI_USERNAME', ''),
        'password' => env('MWALONI_PASSWORD', ''),
    ],
];
