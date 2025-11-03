<?php

return [
    'driver' => 'mysql',
    'host' => 'db',
    'database' => 'ServiciOs',
    'username' => 'root',
    'password' => 'cae2007',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
