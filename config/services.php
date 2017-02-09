<?php

return [

    'apns' => [
        'bundle_identifier' => env('APNS_BUNDLE_IDENTIFIER'),
        'certificate_path' => env('APNS_CERTIFICATE_PATH'),
        'url' => env('APNS_URL'),
    ],
    'fcm' => [
        'url' => env('FCM_URL'),
        'key' => env('FCM_KEY'),
    ]

];