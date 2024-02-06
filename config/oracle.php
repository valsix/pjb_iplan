<?php

return [
    // 'oracle' => [
    //     'driver'         => 'oracle',
    //     'tns'            => env('DB_TNS', ''),
    //     'host'           => env('DB_HOST', ''),
    //     'port'           => env('DB_PORT', '1521'),
    //     'database'       => env('DB_DATABASE', ''),
    //     'username'       => env('DB_USERNAME', ''),
    //     'password'       => env('DB_PASSWORD', ''),
    //     'charset'        => env('DB_CHARSET', 'AL32UTF8'),
    //     'prefix'         => env('DB_PREFIX', ''),
    //     'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
    //     'server_version' => env('DB_SERVER_VERSION', '11g'),
    // ],
    
    'oracle' => [
        'driver' => 'oracle',
        'host' => '192.168.3.205',
        'port' => '1521',
        'database' => 'xe',
        'service_name' => 'ellprd',
        'username' => 'mimsoe',
        'password' => 'mims',
        'charset' => '',
        'prefix' => '',
    ],
];
