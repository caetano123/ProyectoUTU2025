<?php
class User {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllUsers() {
        $sql = "SELECT id, nombre, email, pass FROM usuarios";
        return $this->conn->query($sql);
    }

    public function getUserById($id) {
        $sql = "SELECT id, nombre, email, pass FROM usuarios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('si', $email, $excludeId);
        } else {
            $sql = "SELECT id FROM usuarios WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $email);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function createUser($nombre, $password, $email, $confirmPassword) {
        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
        }

        if ($this->emailExists($email)) {
            return ['success' => false, 'message' => 'El email ya está registrado.'];
        }

        $sql = "INSERT INTO usuarios (nombre, pass, email) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sss', $nombre, $password, $email);

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Error al crear el usuario.'];
        }
    }

    public function updateUser($id, $nombre, $password, $email, $confirmPassword) {
        if ($this->emailExists($email, $id)) {
            return ['success' => false, 'message' => 'El email ya está registrado por otro usuario.'];
        }

        if (!empty($password)) {
            if ($password !== $confirmPassword) {
                return ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
            }
            $sql = "UPDATE usuarios SET nombre = ?, pass = ?, email = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sssi', $nombre, $password, $email, $id);
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssi', $nombre, $email, $id);
        }

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar el usuario.'];
        }
    }
}
?>
