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

    /*
    |-------------------------------------------------------------------------
    | HTTP Client Configuration
    |-------------------------------------------------------------------------
    |
    */
    'http' => [
        /*
        | Total request timeout in seconds.
        */
        'timeout' => env('NFIELD_HTTP_TIMEOUT', 30),

        /*
        | Connection timeout in seconds.
        */
        'connect_timeout' => env('NFIELD_HTTP_CONNECT_TIMEOUT', 10),

        /*
        | Maximum number of attempts for transient errors (429, 5xx, connection).
        | Set to 1 to disable retries.
        */
        'retries' => env('NFIELD_HTTP_RETRIES', 3),

        /*
        | Base delay between retries in milliseconds. Uses exponential backoff
        | unless the server responds with a Retry-After header.
        */
        'retry_delay' => env('NFIELD_HTTP_RETRY_DELAY', 100),
    ],

];
