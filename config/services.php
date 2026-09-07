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

    // WhatsApp Customer Service (floating button)
    'whatsapp' => [
        'cs_number' => env('VITE_WHATSAPP_CS_NUMBER'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping / Routing
    |--------------------------------------------------------------------------
    |
    | Routing endpoint used for road/driving distance calculations while
    | quoting ongkir. The request is made server-side only, never from the
    | frontend. Leave empty to fall back to Haversine (straight-line) distance.
    |
    | Example (self-hosted OSRM):
    |   SHIPPING_ROUTING_ENDPOINT=http://router.openstreetmap.de
    */

    'osrm' => [
        'endpoint' => env('SHIPPING_ROUTING_ENDPOINT'),
        'mode'     => env('SHIPPING_ROUTING_MODE', 'driving'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Geocoding
    |--------------------------------------------------------------------------
    |
    | Server-side geocoding used to resolve a checkout destination to
    | coordinates before the distance is computed. The request is made
    | server-side only. Leave empty to skip coordinate lookup (distance
    | then falls back to zone 1).
    */

    'nominatim' => [
        'endpoint' => env('SHIPPING_GEOCODING_ENDPOINT', 'https://nominatim.openstreetmap.org/search'),
        'user_agent' => env('APP_NAME', 'ALGO NATION'),
    ],

];
