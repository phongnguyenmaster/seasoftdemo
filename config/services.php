<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => '651887181964-kik85q00askj28rkm5fmkm107ll8ghl2.apps.googleusercontent.com',
        'client_secret' => 'hkmSnJ6Iwt_P2Z7nprGrscr0',
        'redirect' => env('APP_URL') . '/auth/social/callback/google',
    ],
    'facebook' => [
        'client_id' => '796012184542830',
        'client_secret' => 'c0c6c447a73887e30ed2c6ec7a3cff84',
        'redirect' => env('APP_URL') . '/auth/social/callback/facebook',
    ],
    'github' => [
        'client_id' => 'Iv1.9cedac6c2b8367c3',
        'client_secret' => 'f863abedcc53ebcd904616f6688b8498f391d109',
        'redirect' => env('APP_URL') . '/auth/social/callback/github',
    ],
    'linkedin' => [
        'client_id' => '860g8jdlb87njj',
        'client_secret' => '09owaSHRuA46YZiv',
        'redirect' => env('APP_URL') . '/auth/social/callback/linkedin',
    ],
];
