<?php

/**
 * Definición de rutas de la aplicación
 * 
 * Formato:
 * $router->addRoute(método, ruta, 'Subnamespace\\Controlador@método', [middleware])
 * 
 * Asegúrate de que los nombres de los controladores incluyan correctamente su subnamespace.
 */

use App\Core\Auth;
use App\Middleware\AuthMiddleware;

// =========================
// RUTAS PÚBLICAS
// =========================

// API
$router->addRoute("POST", "/apicategorias", "ApiController@index");
$router->addRoute("GET", "/apicategorias", "ApiController@index");

// Página de inicio
$router->addRoute("GET", "/", "HomeController@index");

// Pagina Vender Servicio
$router->addRoute("GET", "/vender", "VenderController@index");      // formulario
$router->addRoute("POST", "/vender/crear", "VenderController@crear"); // procesar formulario
$router->addRoute("GET", "/buscar", "BuscarController@index");     // mostrar servicios

// Pagina de Servicio Individual
$router->addRoute("GET", "/servicio", "Servicios\\ServicioController@show");
$router->addRoute("GET", "/servicio/edit", "Servicios\\ServicioController@edit", [AuthMiddleware::class]);
$router->addRoute("POST", "/servicio/update", "Servicios\\ServicioController@update", [AuthMiddleware::class]);
$router->addRoute("POST", "/servicio/contactar", "Servicios\\ServicioController@contactar", );

// Pagina de Buscar Servicio
$router->addRoute("GET", "/buscar", "BuscarController@index");
$router->addRoute("POST", "/buscar/eliminar", "Servicios\\ServicioController@eliminar");

// Pagina de Posts
$router->addRoute("GET", "/post", "PostController@index");
$router->addRoute("GET", "/post/paginar/:pagina", "PostController@index", [AuthMiddleware::class]);

// Términos y Condiciones
$router->addRoute("GET", "/terminos", "LegalController@terminos");

// Login
$router->addRoute("GET", "/login", "Security\\AuthController@showLogin");
$router->addRoute("POST", "/login", "Security\\AuthController@login");
// Logout
$router->addRoute("GET", "/logout", "Security\\AuthController@logout");
// Registro
$router->addRoute("GET", "/register", "Security\\AuthController@showRegister");
$router->addRoute("POST", "/register", "Security\\AuthController@register");


// =========================
// RUTAS PROTEGIDAS POR MIDDLEWARE
// =========================

// Panel de usuario
$router->addRoute("GET", "/dashboard", "Usuarios\\DashboardController@index", [AuthMiddleware::class]);

// Mostrar algo en el dashboard con ID dinámico
$router->addRoute("GET", "/dashboard/:id", "Usuarios\\DashboardController@show", [AuthMiddleware::class]);

// Perfil de usuario
$router->addRoute("GET", "/profile", "Usuarios\\ProfileController@index", [AuthMiddleware::class]);
$router->addRoute("GET", "/profile/edit", "Usuarios\\ProfileController@edit", [AuthMiddleware::class]);
$router->addRoute("POST", "/profile/save", "Usuarios\\ProfileController@save", [AuthMiddleware::class]);

$router->addRoute("GET", "/usuarios", "Security\\UserController@index", [AuthMiddleware::class]);
$router->addRoute("GET", "/usuarios/show/:id", "Security\\UserController@show", [AuthMiddleware::class]);
$router->addRoute("GET", "/usuarios/edit/:id", "Security\\UserController@edit", [AuthMiddleware::class]);
$router->addRoute("POST", "/usuarios/save/:id", "Security\\UserController@save", [AuthMiddleware::class]);

// Notificaciones
$router->addRoute("GET", "/notificaciones", "NotificacionController@index", [AuthMiddleware::class]);
$router->addRoute("GET", "/notificaciones/check", "NotificacionController@check", [AuthMiddleware::class]);

// Panel para administradores
$router->addRoute("GET", "/paneladmin", "Admin\\AdminController@index", [AuthMiddleware::class]);
$router->addRoute("POST", "/paneladmin/deleteUser", "Admin\\AdminController@deleteUser", [AuthMiddleware::class]);
