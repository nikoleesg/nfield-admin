<?php

return [

    /*
    |-------------------------------------------------------------------------
    | The credentials used to sign in.
    |-------------------------------------------------------------------------
    |
    */
    'Domain' => env('NFIELD_DOMAIN', 'Nfield'),

    'Username' => env('NFIELD_USERNAME', 'username'),

    'Password' => env('NFIELD_PASSWORD', 'password'),

    'base_url' => env('NFIELD_BASE_URL', 'https://apiap.nfieldmr.com'),

    /*
    |-------------------------------------------------------------------------
    | Cache Authentication Key return /v1/SignIn
    |-------------------------------------------------------------------------
    |
    */
    'cache_key' => env('NFIELD_CACHE_KEY', true),

    /*
    |-------------------------------------------------------------------------
    | Cache Key Prefix for authentication tokens
    |-------------------------------------------------------------------------
    |
    */
    'cache_key_prefix' => env('NFIELD_CACHE_KEY_PREFIX', 'nfield_'),

    /*
    |-------------------------------------------------------------------------
    | Key to store the token in Cache (Deprecated - use cache_key_prefix)
    |-------------------------------------------------------------------------
    |
    */
    'key_name' => 'nfield-api-authentication-token',

    /*
    |-------------------------------------------------------------------------
    | Token expire time in seconds
    |-------------------------------------------------------------------------
    |
    */
    'expire_seconds' => 60 * 10,

    /*
    |-------------------------------------------------------------------------
    | Database table prefix
    |-------------------------------------------------------------------------
    |
    */
    'table_prefix' => 'nfield_',

    /*
    |-------------------------------------------------------------------------
    | Logging
    |-------------------------------------------------------------------------
    |
    */
    'logging' => [
        'enable' => true,

        'channel' => env('NFIELD_LOG_CHANNEL', 'stack')

    ],

    /*
    |-------------------------------------------------------------------------
    | Survey Sample
    |-------------------------------------------------------------------------
    |
    */
    'sample_files_store_path' => 'nfield/surveys/{surveyId}/samples',

    'sample_files_retention_days' => 30,

    /*
    |-------------------------------------------------------------------------
    | Background Activity
    |-------------------------------------------------------------------------
    |
    */
    'persist_activity_id' => true,

    'persist_drive' => 'database',

    'persist_model' => Nikoleesg\NfieldAdmin\Models\BackgroundActivity::class,

];
