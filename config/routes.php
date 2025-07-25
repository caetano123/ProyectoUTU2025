<?php
/**
 * Definición de rutas de la aplicación
 * 
 * Formato:
 * $router->addRoute(método, ruta, 'Subnamespace\\Controlador@método', [middleware])
 * 
 * Asegúrate de que los nombres de los controladores incluyan correctamente su subnamespace.
 */

use App\Middleware\AuthMiddleware;

// =========================
// RUTAS PÚBLICAS
// =========================

// Página de inicio
$router->addRoute("GET", "/", "HomeController@index");
//Pagina Vender Servicio
$router->addRoute("GET", "/vender", "VenderController@index");
//Pagina de Buscar Servicio
$router->addRoute("GET", "/buscar", "BuscarController@index");

// Login
$router->addRoute("GET", "/login", "Security\\AuthController@showLogin");
$router->addRoute("POST", "/login", "Security\\AuthController@login");
// Logout
$router->addRoute("GET", "/logout", "Security\\AuthController@logout");
// Registro
$router->addRoute("GET", "/register", "Security\\AuthController@showRegister");
$router->addRoute("POST", "/register", "Security\\AuthController@register");

// Vistas de prueba o rutas públicas adicionales
$router->addRoute("GET", "/clientes", "Servicios\\ClientesController@index");

// =========================
// RUTAS PROTEGIDAS POR MIDDLEWARE
// =========================

// Panel de usuario
$router->addRoute("GET", "/dashboard", "Usuarios\\DashboardController@index", [AuthMiddleware::class]);

// Mostrar algo en el dashboard con ID dinámico
$router->addRoute("GET", "/dashboard/:id", "Usuarios\\DashboardController@show", [AuthMiddleware::class]);

// Perfil de usuario
$router->addRoute("GET", "/profile", "Usuarios\\ProfileController@index", [AuthMiddleware::class]);
$router->addRoute("POST", "/profile", "Usuarios\\ProfileController@update", [AuthMiddleware::class]);


$router->addRoute("GET", "/usuarios", "Security\\UserController@index", [AuthMiddleware::class]);
$router->addRoute("GET", "/usuarios/show/:id", "Security\\UserController@show", [AuthMiddleware::class]);
$router->addRoute("GET", "/usuarios/edit/:id", "Security\\UserController@edit", [AuthMiddleware::class]);
$router->addRoute("POST", "/usuarios/save/:id", "Security\\UserController@save", [AuthMiddleware::class]);
