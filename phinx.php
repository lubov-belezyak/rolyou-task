<?php

// Подключаем конфигурацию базы данных
$dbConfig = require __DIR__ . '/config/database.php';

return [
    'paths' => [
        'migrations' => 'database/migrations',
        'seeds' => 'database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => $dbConfig['host'],
            'name' => $dbConfig['database'],
            'user' => $dbConfig['username'],
            'pass' => $dbConfig['password'],
            'port' => $dbConfig['port'],
            'charset' => 'utf8',
        ],
    ],
];
