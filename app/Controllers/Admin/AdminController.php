<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\Servicio;

class AdminController extends Controller
{
    protected $view;
    protected $userModel;
    protected $servicioModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->servicioModel = new Servicio();
    }

    private function checkAdmin() {
        $currentUser = $this->auth->user();
        $isAdmin = (isset($currentUser['Tipo']) && $currentUser['Tipo'] == 'ADMIN');

        if (!$isAdmin) {
            return $this->redirect('/login');
        }
        
        return null;
    }


    public function index() {
        
        $redirect = $this->checkAdmin();
        if ($redirect) {
            return $redirect;
        }

        $currentUser = $this->auth->user();
        $clientes = $this->userModel->findByRol('USUARIO');
        $servicios = $this->servicioModel->findAll();

        return $this->render('admin/index', [
            "title" => 'Panel de Administración ' . ($currentUser['Nombre'] ?? 'Usuario'),
            "clientes" => $clientes,
            "servicios" => $servicios
        ]);
    }

 public function deleteUser() {
    $redirect = $this->checkAdmin();
    if ($redirect) {
        return $redirect;
    }

    $userId = $_POST['id'] ?? null;

    if (!$userId || !is_numeric($userId)) {
        $this->session->flash('error', 'ID de usuario inválido.');
        return $this->redirect('/paneladmin');
    }

    $currentAdminId = $this->auth->user()['ID_Persona'] ?? null;
    if ($userId == $currentAdminId) {
        $this->session->flash('error', 'No puedes eliminar tu propia cuenta de administrador.');
        return $this->redirect('/paneladmin');
    }

    $user = $this->userModel->findById($userId);
    if (empty($user)) {
        $this->session->flash('error', 'El usuario que intentas eliminar no existe.');
        return $this->redirect('/paneladmin');
    }

    try {
        $this->userModel->delete(['ID_Persona' => $userId]);
        $this->session->flash('success', 'Usuario eliminado correctamente.');

    } catch (\Exception $e) {
        $this->session->flash('error', 'Ocurrió un error al eliminar el usuario.');
    }

    return $this->redirect('/paneladmin');
}
}