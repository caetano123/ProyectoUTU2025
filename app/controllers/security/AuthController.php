<?php
namespace App\Controllers\Security;

use App\Models\Security\User;
use Core\Session;
use Core\View;

class AuthController {
    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    // Método privado para verificar si hay sesión iniciada
    private function checkAuth() {
        Session::start();
        if (!Session::get('user')) {
            header('Location: /login');
            exit;
        }
    }

    public function showLogin() {
        $this->view->render("Security/auth/login", ["titulo" => "Iniciar sesión"]);
    }

    public function login() {
        Session::start();
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['Contraseña'])) {
            Session::set('user', [
                'CI' => $user['CI'],
                'Nombre' => $user['Nombre'],
                'Apellido' => $user['Apellido'],
                'Email' => $user['Email'],
                'Tipo' => $user['Tipo']
            ]);
            header('Location: /');
            exit;
        } else {
            $this->view->render("Security/auth/login", [
                "titulo" => "Iniciar sesión",
                "error" => "Email o contraseña incorrectos"
            ]);
        }
    }

    public function showRegister() {
        $this->view->render("Security/auth/register", ["titulo" => "Registro de usuario"]);
    }

    public function register() {
        Session::start();

        $ci = $_POST['ci'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $tipo = $_POST['tipo'] ?? '';

        $errors = [];

        if (!$ci) $errors[] = "CI es obligatorio";
        if (!$nombre) $errors[] = "Nombre es obligatorio";
        if (!$apellido) $errors[] = "Apellido es obligatorio";
        if (!$email) $errors[] = "Email es obligatorio";
        if (!$password) $errors[] = "Contraseña es obligatoria";
        if ($password !== $password_confirm) $errors[] = "Las contraseñas no coinciden";
        if (!in_array($tipo, ['Cliente', 'Proveedor', 'Ambos'])) $errors[] = "Tipo inválido";
        if (User::findByEmail($email)) $errors[] = "Ya existe un usuario con ese email";

        if (!empty($errors)) {
            $this->view->render("Security/auth/register", [
                "titulo" => "Registro de usuario",
                "errors" => $errors
            ]);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        User::create([
            'CI' => $ci,
            'Nombre' => $nombre,
            'Apellido' => $apellido,
            'Email' => $email,
            'Telefono' => $telefono,
            'Direccion' => $direccion,
            'Contraseña' => $hashedPassword,
            'Tipo' => $tipo
        ]);

        header('Location: /login');
        exit;
    }

    public function logout() {
        Session::start();
        Session::destroy();
        header('Location: /login');
        exit;
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
            "titulo" => "Usuario CI {$param['id']}",
            "datos" => [$usuario]
        ]);
    }

    public function edit($param) {
        $this->checkAuth();

        $usuario = User::findById($param['id']);
        $this->view->render("Security/auth/edit", [
            "titulo" => "Editar usuario CI {$param['id']}",
            "datos" => $usuario
        ]);
    }

    public function save($param) {
        $this->checkAuth();

        $ci = $param['id'];
        $data = [
            'Nombre' => $_POST['nombre'] ?? '',
            'Apellido' => $_POST['apellido'] ?? '',
            'Telefono' => $_POST['telefono'] ?? '',
            'Direccion' => $_POST['direccion'] ?? '',
            'Tipo' => $_POST['tipo'] ?? ''
        ];

        $updated = User::update($ci, $data);

        $mensaje = $updated ? "Se actualizó correctamente." : "No se pudo actualizar.";
        $this->view->render("Security/auth/save", [
            "titulo" => "Actualizar usuario",
            "datos" => [$mensaje]
        ]);
    }
}
