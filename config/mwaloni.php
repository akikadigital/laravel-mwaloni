<?php

return [
    'env' => env('MWALONI_ENV', 'sandbox'), // sandbox or production
    'debug' => env('MWALONI_DEBUG', true),
    'url' => [
        'production' => env('MWALONI_URL_PRODUCTION', 'https://wallet.mwaloni.com/api/'),
        'sandbox' => env('MWALONI_URL_SANDBOX', 'https://wallet-stg.mwaloni.com/api/'),
    ]
];
