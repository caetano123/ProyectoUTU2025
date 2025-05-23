<?php

require_once __DIR__ . '/../models/User.php';

class UserController {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function showAllUsers($mensaje = '') {
        $usuarios = $this->userModel->getAllUsers();
        $this->render('usersView', ['usuarios' => $usuarios, 'usuarioFiltrado' => null, 'mensaje' => $mensaje]);
    }

    public function searchUserById($id, $mensaje = '') {
        $usuarioFiltrado = null;

        if (is_numeric($id)) {
            $result = $this->userModel->getUserById(intval($id));
            $usuarioFiltrado = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;
        }

        $usuarios = $this->userModel->getAllUsers();
        $this->render('usersView', ['usuarios' => $usuarios, 'usuarioFiltrado' => $usuarioFiltrado, 'mensaje' => $mensaje]);
    }

    public function saveUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $id = $_POST['id'] ?? null;
            $nombre = trim($_POST['nombre'] ?? '');
            $password = $_POST['password'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $confirmPassword = $_POST['confirm_password'] ?? $password;

            if ($nombre === '' || $email === '') {
                $this->showAllUsers("Nombre y email son obligatorios.");
                return;
            }

            if ($id) {
                // Actualizar usuario
                $result = $this->userModel->updateUser($id, $nombre, $password, $email, $confirmPassword);
            } else {
                // Crear nuevo usuario
                $result = $this->userModel->createUser($nombre, $password, $email, $confirmPassword);
            }

            if ($result['success']) {
                $this->showAllUsers($id ? "Usuario actualizado correctamente." : "Usuario creado correctamente.");
            } else {
                // Si es un update, mostrar usuario filtrado para edición
                if ($id) {
                    $this->searchUserById($id, $result['message']);
                } else {
                    $this->showAllUsers($result['message']);
                }
            }
        } else {
            $this->showAllUsers();
        }
    }

    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/{$view}.php";
    }
}
