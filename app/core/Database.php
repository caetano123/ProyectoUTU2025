<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            try {
                self::$instance = new PDO(
                    "mysql:host={$config['host']};dbname={$config['database']};charset=utf8",
                    $config['username'],
                    $config['password']
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error conexión DB: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
