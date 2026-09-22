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
    | Database table prefix
    |-------------------------------------------------------------------------
    |
    */
    'table_prefix' => 'nfield_',

    /*
    |-------------------------------------------------------------------------
    | Token Caching
    |-------------------------------------------------------------------------
    |
    */
    'cache' => [
        'enabled' => env('NFIELD_CACHE_KEY', true),
        'prefix' => env('NFIELD_CACHE_KEY_PREFIX', 'nfield_'),
        'ttl' => 60 * 10,
    ],

];
