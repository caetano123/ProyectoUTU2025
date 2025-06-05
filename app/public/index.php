<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../vendor/autoload.php';  // Corregí la ruta aquí

use Core\Router;

Router::startSession();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();     
$router->direct($uri, $method);

// Mostrar URL y método para depurar
var_dump("URI:", $uri);
var_dump("METHOD:", $method);

// Mostrar datos del usuario en sesión para depurar
var_dump("SESSION user:", $_SESSION['user'] ?? null);

exit;  // Detener ejecución aquí para que puedas ver la salida