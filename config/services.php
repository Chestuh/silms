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
    | a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'secret' => env('MAILGUN_SECRET'),
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
        'api_key' => env('GOOGLE_API_KEY', env('OPENAI_API_KEY')),
    ],

    // Google Generative AI Configuration
    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'endpoint' => env('OPENAI_API_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent'),
        'model' => env('OPENAI_MODEL', 'gemini-pro'),
    ],

];
