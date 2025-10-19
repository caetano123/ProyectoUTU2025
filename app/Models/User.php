<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model {
    protected $table = "Personas"; // Define la tabla usada por este modelo

    // Buscar usuario por correo (campo 'Correo' en la BD)
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE Correo = :email LIMIT 1";
        $stmt = $this->executeRawQuery($sql, [':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario (registro)
    public function createUser(array $data) {
        $data['Verificado'] = 0; // Por defecto no verificado
        $data['Tipo'] = $data['Tipo'] ?? 'USUARIO'; // Por defecto usuario normal
        return $this->create([
            'Nombre' => $data['Nombre'] ?? null,
            'Apellido' => $data['Apellido'] ?? null,
            'Correo' => $data['Correo'] ?? null,
            'ContrasenaHash' => $data['ContrasenaHash'] ?? null,
            'Verificado' => $data['Verificado'],
            'Tipo' => $data['Tipo']
        ]);
    }

    // Obtener todos los usuarios (sin contraseñas)
    public function getAllUsers() {
        $sql = "SELECT ID_Persona, Nombre, Apellido, Correo, Verificado, FechaRegistro, Tipo FROM {$this->table}";
        $stmt = $this->executeRawQuery($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar usuario por ID
    public function findById($id) {
        $sql = "SELECT ID_Persona, Nombre, Apellido, Correo, Verificado, FechaRegistro, Tipo 
                FROM {$this->table} 
                WHERE ID_Persona = :id LIMIT 1";
        $stmt = $this->executeRawQuery($sql, [':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar usuario (solo algunos campos permitidos)
    public function updateUser($id, array $data) {
        return $this->update(['ID_Persona' => $id], [
            'Nombre' => $data['Nombre'] ?? null,
            'Apellido' => $data['Apellido'] ?? null
        ]);
    }
    
 // Verificar si un usuario tiene un rol (con el campo Tipo)
    public function hasRole($userId, $roleName) {
        $sql = "SELECT Tipo FROM {$this->table} WHERE ID_Persona = :id LIMIT 1";
        $stmt = $this->executeRawQuery($sql, [':id' => $userId]);
        $tipo = $stmt->fetchColumn();
        return strtoupper($tipo) === strtoupper($roleName);
    }

    // Obtener usuarios por rol (ADMIN o USUARIO)
    public function findByRol($rolNombre) {
        $sql = "SELECT * FROM {$this->table} WHERE Tipo = :rol";
        $stmt = $this->executeRawQuery($sql, [':rol' => strtoupper($rolNombre)]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRolesByUserId(int $userId): array
    {
        $sql = "SELECT Tipo FROM {$this->table} WHERE ID_Persona = :id LIMIT 1";
        
        $stmt = $this->executeRawQuery($sql, [':id' => $userId]);
        $tipo = $stmt->fetchColumn();
        
        return $tipo ? [strtolower($tipo)] : [];
    }
}

