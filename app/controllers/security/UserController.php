<?php
namespace App\Controllers\Security;

use App\Models\Security\User;
use Core\Session;
use Core\View;

class UserController {
    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    private function checkAuth() {
        Session::start();
        if (!Session::get('user')) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $this->checkAuth();

        $usuarios = User::all();
        $this->view->render("Security/auth/index", [
            "titulo" => "Listado de usuarios",
            "datos" => $usuarios
        ]);
    }

    public function show($param) {
        $this->checkAuth();

        $usuario = User::findById($param['id']);
        $this->view->render("Security/auth/show", [
            "titulo" => "Usuario ID {$param['id']}",
            "datos" => [$usuario]
        ]);
    }

    public function edit($param) {
        $this->checkAuth();

        $usuario = User::findById($param['id']);
        $this->view->render("Security/auth/edit", [
            "titulo" => "Editar usuario ID {$param['id']}",
            "datos" => $usuario
        ]);
    }

    public function save($param) {
        $this->checkAuth();

        $id = $param['id'];
        $data = [
            'Nombre' => $_POST['nombre'] ?? '',
            'Apellido' => $_POST['apellido'] ?? '',
            'Correo' => $_POST['correo'] ?? '',
            'Verificado' => isset($_POST['verificado']) ? 1 : 0 // checkbox
        ];

        $updated = User::update($id, $data);

        $mensaje = $updated ? "Se actualizó correctamente." : "No se pudo actualizar.";
        $this->view->render("Security/auth/save", [
            "titulo" => "Actualizar usuario",
            "datos" => [$mensaje]
        ]);
    }
}
