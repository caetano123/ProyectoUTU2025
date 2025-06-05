<?php
namespace Core;

use App\Controllers\Security\AuthController;
use App\Controllers\Security\UserController;
use App\Controllers\Admin\AdminController;

class Router {
    public function direct($uri, $method) {
        // Normalizar la URI
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');

        switch ($uri) {
            case '':
            case '/':
                $userController = new UserController();
                $userController->index();
                break;

            case '/login':
                $authController = new AuthController();
                if ($method === 'GET') {
                    $authController->showLogin();
                } elseif ($method === 'POST') {
                    $authController->login();
                }
                break;

            case '/register':
                $authController = new AuthController();
                if ($method === 'GET') {
                    $authController->showRegister();
                } elseif ($method === 'POST') {
                    $authController->register();
                }
                break;

            case '/logout':
                $authController = new AuthController();
                $authController->logout();
                break;

            case '/dashboard':
                $adminController = new AdminController();
                $adminController->dashboard();
                break;

            default:
                // Rutas con parámetros para usuario
                if (preg_match('#^/user/(\d+)$#', $uri, $matches) && $method === 'GET') {
                    $userController = new UserController();
                    $userController->show(['id' => $matches[1]]);
                } elseif (preg_match('#^/user/(\d+)/edit$#', $uri, $matches) && $method === 'GET') {
                    $userController = new UserController();
                    $userController->edit(['id' => $matches[1]]);
                } elseif (preg_match('#^/user/(\d+)/save$#', $uri, $matches) && $method === 'POST') {
                    $userController = new UserController();
                    $userController->save(['id' => $matches[1]]);
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
