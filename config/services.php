<?php
// config/services.php - tambahkan bagian xendit

return [
    // ... konfigurasi lain (mailgun, ses, dll)

    'xendit' => [
        'secret_key'    => env('XENDIT_SECRET_KEY'),
        'public_key'    => env('XENDIT_PUBLIC_KEY'),
        'webhook_token' => env('XENDIT_WEBHOOK_TOKEN'),
    ],
];
