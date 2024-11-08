<?php

namespace Src\Services;

use PDO;
class Database
{
    private $pdo;

    public function __construct()
    {
            $config = require __DIR__ . '/../../config/database.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];


            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}