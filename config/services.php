<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', rtrim(env('APP_URL', 'http://localhost'), '/') . '/auth/google/callback'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Realtime Database - MomSpire Chat
    |--------------------------------------------------------------------------
    |
    | Konfigurasi Firebase untuk fitur konsultasi real-time
    | Project: momspire (Firebase Console: https://console.firebase.google.com/u/0/project/momspire/settings/general)
    |
    */

    'firebase' => [
        'api_key' => env('FIREBASE_API_KEY', 'AIzaSyCPg3wsZCxK6Bjc1_YibQmi6jir_3zhG1Q'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'momspire.firebaseapp.com'),
        'database_url' => env('FIREBASE_DATABASE_URL', 'https://momspire-default-rtdb.firebaseio.com'),
        'project_id' => env('FIREBASE_PROJECT_ID', 'momspire'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'momspire.firebasestorage.app'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', '14853541777'),
        'app_id' => env('FIREBASE_APP_ID', '1:14853541777:web:e41071557361f2ad159430'),
        'measurement_id' => env('FIREBASE_MEASUREMENT_ID', 'G-8W9M5WNYL7'),
    ],

];
