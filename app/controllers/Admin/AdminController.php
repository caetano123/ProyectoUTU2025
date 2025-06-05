<?php
namespace App\Controllers\Admin;

use Core\Session;
use Core\View;
use App\Models\Security\User;

class AdminController {
    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    private function checkAdmin() {
        Session::start();
        $user = Session::get('user');

        if (!$user) {
            header('Location: /login');
            exit;
        }

        $userId = $user['ID_Usuarios'] ?? null;
        if (!$userId || !User::hasRole($userId, 'admin')) {
            header('Location: /login');
            exit;
        }
    }

    public function dashboard() {
        $this->checkAdmin();

        $this->view->render("Admin/dashboard", [
            "titulo" => "Panel de Administración"
        ]);
    }
}
