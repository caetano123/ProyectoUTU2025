<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return [
    'driver' => $_ENV['DB_DRIVER'], 
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['SERVICIOS_DB_DATABASE'],
    'username' => $_ENV['SERVICIOS_DB_USER'],
    'password' => $_ENV['SERVICIOS_DB_PASS'],
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
