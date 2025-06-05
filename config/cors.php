<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:65293', // Flutter dev server
        'http://192.168.1.14:64830',  // optional, local IP fallback
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // ✅ VERY IMPORTANT FOR SANCTUM
];
