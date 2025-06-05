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

    public function showLogin() {
        $this->view->render("Security/auth/login", ["titulo" => "Iniciar sesión"]);
    }

    public function login() {
        Session::start();
        $correo = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($correo);

        if ($user && password_verify($password, $user['ContrasenaHash'])) {
            // Podés cargar roles luego si querés.
            Session::set('user', [
                'ID' => $user['ID_Usuarios'],
                'Nombre' => $user['Nombre'],
                'Apellido' => $user['Apellido'],
                'Correo' => $user['Correo'],
                'Verificado' => $user['Verificado']
            ]);

            header('Location: /');
            exit;
        } else {
            $this->view->render("Security/auth/login", [
                "titulo" => "Iniciar sesión",
                "error" => "Correo o contraseña incorrectos"
            ]);
        }
    }

    public function showRegister() {
        $this->view->render("Security/auth/register", ["titulo" => "Registro de usuario"]);
    }

    public function register() {
        Session::start();

        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $correo = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $errors = [];

        if (!$nombre) $errors[] = "Nombre es obligatorio";
        if (!$apellido) $errors[] = "Apellido es obligatorio";
        if (!$correo) $errors[] = "Correo es obligatorio";
        if (!$password) $errors[] = "Contraseña es obligatoria";
        if ($password !== $password_confirm) $errors[] = "Las contraseñas no coinciden";
        if (User::findByEmail($correo)) $errors[] = "Ya existe un usuario con ese correo";

        if (!empty($errors)) {
            $this->view->render("Security/auth/register", [
                "titulo" => "Registro de usuario",
                "errors" => $errors
            ]);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        User::create([
            'Nombre' => $nombre,
            'Apellido' => $apellido,
            'Correo' => $correo,
            'ContrasenaHash' => $hashedPassword,
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
}
