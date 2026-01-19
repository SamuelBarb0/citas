<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayPal Mode
    |--------------------------------------------------------------------------
    |
    | Specify the mode for PayPal: 'sandbox' for testing, 'live' for production.
    |
    */
    'mode' => env('PAYPAL_MODE', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | PayPal Credentials
    |--------------------------------------------------------------------------
    |
    | Your PayPal API credentials (Client ID and Secret).
    |
    */
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | PayPal Webhook ID
    |--------------------------------------------------------------------------
    |
    | The Webhook ID for verifying webhook signatures.
    |
    */
    'webhook_id' => env('PAYPAL_WEBHOOK_ID'),

    /*
    |--------------------------------------------------------------------------
    | PayPal API URLs
    |--------------------------------------------------------------------------
    |
    | API endpoints for sandbox and live environments.
    |
    */
    'api_url' => env('PAYPAL_MODE', 'sandbox') === 'live'
        ? 'https://api-m.paypal.com'
        : 'https://api-m.sandbox.paypal.com',

    /*
    |--------------------------------------------------------------------------
    | PayPal SDK URL
    |--------------------------------------------------------------------------
    |
    | SDK URL for frontend integration.
    |
    */
    'sdk_url' => env('PAYPAL_MODE', 'sandbox') === 'live'
        ? 'https://www.paypal.com/sdk/js'
        : 'https://www.sandbox.paypal.com/sdk/js',

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | Default currency for PayPal transactions.
    |
    */
    'currency' => 'EUR',

    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    |
    | Default locale for PayPal buttons.
    |
    */
    'locale' => 'es_ES',
];
