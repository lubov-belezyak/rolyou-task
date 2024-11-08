<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',  // Если переменная не найдена, используем '127.0.0.1'
    'database' => getenv('DB_DATABASE') ?: 'test',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
];