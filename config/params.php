<?php /** @noinspection SpellCheckingInspection */

return [
    'api_key'       => 'your:bot_api_key',
    // 'bot_username'  => 'username_bot', // Without "@"

    'admins'        => [
        249226472   // cept
    ],

    // When using the getUpdates method, this can be commented out
    'webhook'       => [
        'url' => 'https://your-domain/path/to/hook-or-manager.php',
        // Use self-signed certificate
        // 'certificate'     => __DIR__ . '/path/to/your/certificate.crt',
        // Limit maximum number of connections
        // 'max_connections' => 5,
    ],

    'uploadsDir'    => '/tmp/'

];
