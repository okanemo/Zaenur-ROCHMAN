<?php

return [
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => 'http://127.0.0.1:8080/login/github/callback',
    ],
];
