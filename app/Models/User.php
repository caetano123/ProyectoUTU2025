<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model {
    protected $table = "Usuarios"; // Define la tabla usada por este modelo

    // Buscar usuario por correo (campo 'Correo' en la BD)
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE Correo = :email LIMIT 1";
        $stmt = $this->executeRawQuery($sql, [':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario (registro)
    public function createUser(array $data) {
        $data['Verificado'] = 0; // Por defecto no verificado
        return $this->create([
            'Nombre' => $data['Nombre'] ?? null,
            'Apellido' => $data['Apellido'] ?? null,
            'Correo' => $data['Correo'] ?? null,
            'ContrasenaHash' => $data['ContrasenaHash'] ?? null,
            'Verificado' => $data['Verificado']
        ]);
    }

    // Obtener todos los usuarios (sin contraseñas)
    public function getAllUsers() {
        $sql = "SELECT ID_Usuarios, Nombre, Apellido, Correo, Verificado, FechaRegistro FROM {$this->table}";
        $stmt = $this->executeRawQuery($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar usuario por ID
    public function findById($id) {
        $sql = "SELECT ID_Usuarios, Nombre, Apellido, Correo, Verificado, FechaRegistro 
                FROM {$this->table} 
                WHERE ID_Usuarios = :id LIMIT 1";
        $stmt = $this->executeRawQuery($sql, [':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar usuario (solo algunos campos permitidos)
    public function updateUser($id, array $data) {
        return $this->update(['ID_Usuarios' => $id], [
            'Nombre' => $data['Nombre'] ?? null,
            'Apellido' => $data['Apellido'] ?? null
        ]);
    }

    // Verificar si un usuario tiene un rol específico
    public function hasRole($userId, $roleName) {
        $sql = "
            SELECT 1 FROM UsuarioRol ur
            JOIN Roles r ON ur.ID_Rol = r.ID_Rol
            WHERE ur.ID_Usuario = :id AND r.Nombre = :rol
            LIMIT 1
        ";
        $stmt = $this->executeRawQuery($sql, [
            ':id' => $userId,
            ':rol' => $roleName
        ]);
        return $stmt->fetchColumn() !== false;
    }

public function findByRol($rolNombre) {
    $sql = "
        SELECT u.*
        FROM Usuarios u
        JOIN UsuarioRol ur ON u.ID_Usuarios = ur.ID_Usuario
        JOIN Roles r ON ur.ID_Rol = r.ID_Rol
        WHERE r.Nombre = :rol
    ";

    $stmt = $this->db->query($sql, [':rol' => $rolNombre]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

}
