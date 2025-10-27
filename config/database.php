<?php

return [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'ServiciOs',
    'username' => 'serviciosuser',
    'password' => 'cae2007c',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];

