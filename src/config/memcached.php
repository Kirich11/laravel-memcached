<?php

return [
    'servers' => [
        'primary' => [
            'host' => env('MEMCACHED_PRIMARY_HOST', 'localhost'),
            'port' => env('MEMCACHED_PRIMARY_PORT', 11211),
            'weight' => 100
        ],
        'backup' => [
            'host' => env('MEMCACHED_BACKUP_HOST', 'localhost'),
            'port' => env('MEMCACHED_BACKUP_PORT', 11212),
            'weight' => 0
        ],
    ],
    'ttl' => 300
];