<?php
return [
    // mysql 相关配置
    'mysql' => [
        'default' => [
            'write' => [
                'host' => env('DB_WRITE_HOST'),
                'port' => env('DB_PORT', 3306),
                'database' => env('DB_DATABASE', ''),
                'username' => env('DB_USERNAME', ''),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8',
                'timezone' => '+08:00',
                'persistent' => true,
                'timeout' => 3
            ],
            'read' => [
                'host' => env('DB_WRITE_HOST'),
                'port' => env('DB_PORT', 3306),
                'database' => env('DB_DATABASE', ''),
                'username' => env('DB_USERNAME', ''),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8',
                'timezone' => '+08:00',
                'persistent' => true,
                'timeout' => 3
            ],
        ],
    ],
    // mongo 相关配置
    'mongo' => [
        'default' => [
            'dsn' => env('MONGODB_DSN', '127.0.0.1'),
            'database' => env('MONGODB_DATABASE', 'test'),
        ],
    ],
    // redis 相关配置
    'redis' => [
        'default' => [
            'host' => env('REDIS_DEFAULT_HOST'),
            'port' => env('REDIS_DEFAULT_PORT', 6379),
            'database' => env('REDIS_DEFAULT_DATABASE', 0),
            'password' => '',
        ],
        'queue' => [
            'host' => env('REDIS_QUEUE_HOST'),
            'port' => env('REDIS_QUEUE_PORT', 6379),
            'database' => env('REDIS_QUEUE_DATABASE', 0),
            'password' => '',
        ],
        'elk' => [
            'host' => env('REDIS_ELK_HOST'),
            'port' => env('REDIS_ELK_PORT', 6379),
            'database' => env('REDIS_ELK_DATABASE', 1),
            'password' => '',
        ]
    ],
];