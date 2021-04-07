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

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_CALLBACK_URL'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_CALLBACK_URL'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_URL'),
    ],

    'apple' => [
        'client_id' => "com.royoorders-apple-login",
        'client_secret' => "eyJraWQiOiIzRjhVQTZDN1ozIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiI2RlhVTVQ1WDIzIiwiYXVkIjoiaHR0cHM6Ly9hcHBsZWlkLmFwcGxlLmNvbSIsInN1YiI6ImNvbS5yb3lvb3JkZXJzLWFwcGxlLWxvZ2luIiwiaWF0IjoxNjE3Nzk0ODcyLCJleHAiOjE2MzMzNDY4NzJ9.KXuDGrLgwKaWSuFfiHlbjZ4aaA4AZnlgI0jT7EZMXBya7NkdDbIvCUD6LnRZGT_KtgfR3YM0gEYszxyGRgNS8A",
        'redirect' => "https://bahubali.royoorders.com/auth/callback/apple",
    ],


    

];
