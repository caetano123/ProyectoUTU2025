<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;

class AdminController extends Controller
{
    protected $view;
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->view = new View();
        $this->userModel = new User();
    }

    private function checkAdmin()
    {
        $user = $this->auth->user();

        if (!$user) {
            return $this->redirect('/login');
        }

        $userId = $user['ID_Usuarios'] ?? null;
        if (!$userId || !$this->userModel->hasRole($userId, 'ADMIN')) {
            return $this->redirect('/login');
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();

        return $this->view->render("Admin/dashboard", [
            "titulo" => "Panel de Administración"
        ]);
    }
}
