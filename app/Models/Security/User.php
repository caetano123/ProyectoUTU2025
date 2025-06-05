<?php
namespace App\Models\Security;

use Core\Database;
use PDO;

class User {
    // Buscar usuario por correo (campo 'Correo' en la BD)
    public static function findByEmail($email) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Usuarios WHERE Correo = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario (registro)
    public static function create($data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO Usuarios (Nombre, Apellido, Correo, ContrasenaHash, Verificado) 
                              VALUES (:nombre, :apellido, :correo, :contrasenahash, 0)");
        return $stmt->execute([
            ':nombre' => $data['Nombre'] ?? null,
            ':apellido' => $data['Apellido'] ?? null,
            ':correo' => $data['Correo'] ?? null,
            ':contrasenahash' => $data['ContrasenaHash'] ?? null
        ]);
    }

    // Obtener todos los usuarios (sin contraseñas)
    public static function all() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT ID_Usuarios, Nombre, Apellido, Correo, Verificado, FechaRegistro FROM Usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar usuario por ID
    public static function findById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT ID_Usuarios, Nombre, Apellido, Correo, Verificado, FechaRegistro FROM Usuarios WHERE ID_Usuarios = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar usuario (solo algunos campos permitidos)
    public static function update($id, $data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE Usuarios SET 
            Nombre = :nombre, 
            Apellido = :apellido
            WHERE ID_Usuarios = :id");
        return $stmt->execute([
            ':nombre' => $data['Nombre'] ?? null,
            ':apellido' => $data['Apellido'] ?? null,
            ':id' => $id
        ]);
    }

    // Verificar si un usuario tiene un rol específico
    public static function hasRole($userId, $roleName) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT 1 FROM UsuarioRol ur
            JOIN Roles r ON ur.ID_Rol = r.ID_Rol
            WHERE ur.ID_Usuario = :id AND r.Nombre = :rol
            LIMIT 1
        ");
        $stmt->execute([
            ':id' => $userId,
            ':rol' => $roleName
        ]);
        return $stmt->fetchColumn() !== false;
    }
}
