<?php

declare(strict_types=1);

return [

    /*
    |-------------------------------------------------------------------------
    | The credentials used to sign in.
    |-------------------------------------------------------------------------
    |
    */
    'domain' => env('NFIELD_DOMAIN', 'Nfield'),

    'username' => env('NFIELD_USERNAME', 'username'),

    'password' => env('NFIELD_PASSWORD', 'password'),

    'base_url' => env('NFIELD_BASE_URL', 'https://apiap.nfieldmr.com'),

    /*
    |-------------------------------------------------------------------------
    | Token Caching
    |-------------------------------------------------------------------------
    |
    */
    'cache' => [
        /*
        | Set to false to re-authenticate on every request.
        */
        'enabled' => env('NFIELD_CACHE_ENABLED', true),

        /*
        | The cache store used to hold tokens. Null uses the application's
        | default store; any driver works for a token string.
        */
        'store' => env('NFIELD_CACHE_STORE'),

        'prefix' => env('NFIELD_CACHE_KEY_PREFIX', 'nfield_'),

        /*
        | Upper bound on the token TTL, in seconds. The API's own `expiresIn`
        | caps it further.
        */
        'ttl' => 60 * 10,
    ],

];
