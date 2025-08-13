<?php
/**
 * Punto de entrada principal de la aplicación
 * Inicializa componentes esenciales y despacha las peticiones
 */



// Definir la ruta base
define("BASE_PATH", dirname(__DIR__));

// Suponiendo que tu proyecto corre en http://localhost:81
define('BASE_URL', 'http://localhost:81');


// Cargar autoloader de Composer
require BASE_PATH."/../vendor/autoload.php";


// Cargar funciones globales
require BASE_PATH . "/Helpers/functions.php";

// Manejo de errores para entorno de desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Inicializar contenedor de dependencias y registrar componentes esenciales
$session = new App\Core\Session();
App\Core\Container::set('session', $session);

$db = App\Core\Database::getInstance();
App\Core\Container::set('db', $db);

$userModel = new App\Models\User();
App\Core\Container::set('userModel', $userModel);

$auth = new App\Core\Auth($session, $userModel);
App\Core\Container::set('auth', $auth);

$view = new App\Core\View();
App\Core\Container::set('view', $view);

// Preparar datos de la solicitud
$request = [
    "get" => $_GET,
    "post" => $_POST,
    "uri" => isset($_GET["url"]) ? trim($_GET["url"], "/") : "home",
    "method" => $_SERVER["REQUEST_METHOD"] ?? "GET"
];
App\Core\Container::set('request', $request);

// Compartir datos globales con todas las vistas
$view->share([
    "auth" => $auth,
    "currentUrl" => parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),
    "flash" => [
        "error" => $session->flash("error"),
        "success" => $session->flash("success")
    ]
]);


// Iniciar el enrutador
$router = new App\Core\Router($view, $request);
App\Core\Container::set('router', $router);

// Cargar y configurar rutas
require BASE_PATH."/../config/routes.php";

// Procesar la solicitud actual
$router->dispatch();
