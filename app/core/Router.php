<?php
namespace Core;

use App\controllers\security\AuthController;

class Router {
    public function direct($uri, $method) {
    // Normalizar la URI
    $uri = parse_url($uri, PHP_URL_PATH);
    $uri = rtrim($uri, '/');

    $controller = new AuthController();

    switch ($uri) {
        case '':
        case '/':
            // Podés llamar index para mostrar listado usuarios o home
            $controller->index();
            break;

        case '/login':
            if ($method === 'GET') {
                $controller->showLogin();
            } elseif ($method === 'POST') {
                $controller->login();
            }
            break;

        case '/register':
            if ($method === 'GET') {
                $controller->showRegister();
            } elseif ($method === 'POST') {
                $controller->register();
            }
            break;

        case '/logout':
            $controller->logout();
            break;

        default:
            // Rutas con parámetros para usuario
            if (preg_match('#^/user/(\d+)$#', $uri, $matches) && $method === 'GET') {
                $controller->show(['id' => $matches[1]]);
            } elseif (preg_match('#^/user/(\d+)/edit$#', $uri, $matches) && $method === 'GET') {
                $controller->edit(['id' => $matches[1]]);
            } elseif (preg_match('#^/user/(\d+)/save$#', $uri, $matches) && $method === 'POST') {
                $controller->save(['id' => $matches[1]]);
            } else {
                http_response_code(404);
                echo "Página no encontrada";
            }
            break;
    }
}


    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
