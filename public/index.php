<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Src\Services\Database;

$db = new Database();
require_once __DIR__ . '/../routes/api.php';