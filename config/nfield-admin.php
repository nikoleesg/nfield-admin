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

    /*
    |-------------------------------------------------------------------------
    | Cache Authentication Key return /v1/SignIn
    |-------------------------------------------------------------------------
    |
    */
    'cache_key' => true,

    /*
    |-------------------------------------------------------------------------
    | Key to store the token in Cache
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
    'table_prefix' => 'nfield_'

];
