<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model {

    public function __construct() {
        parent::__construct();
        $this->table = "Personas";
        $this->primaryKey = "ID_Persona";
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE Correo = :email LIMIT 1";
        $stmt = $this->executeRawQuery($sql, [':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser(array $data) {
        $data['Verificado'] = 0; // Por defecto no verificado
        $data['Tipo'] = $data['Tipo'] ?? 'USUARIO'; // Por defecto usuario normal
        return $this->create([
            'Nombre' => $data['Nombre'] ?? null,
            'Apellido' => $data['Apellido'] ?? null,
            'Correo' => $data['Correo'] ?? null,
            'Telefono' => $data['Telefono'] ?? null,
            'ContrasenaHash' => $data['ContrasenaHash'] ?? null,
            'Verificado' => $data['Verificado'],
            'Tipo' => $data['Tipo']
        ]);
    }

    // Obtener todos los usuarios (sin contraseñas)
    public function getAllUsers() {
        $sql = "SELECT ID_Persona, Nombre, Apellido, Correo, Telefono, Verificado, FechaRegistro, Tipo FROM {$this->table}";
        $stmt = $this->executeRawQuery($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar usuario por ID
    public function findById($id) {
        $sql = "SELECT ID_Persona, Nombre, Apellido, Correo, Telefono, Verificado, FechaRegistro, Tipo 
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

    // Obtener usuarios por rol (ADMIN o USUARIO)
    public function findByRol($rolNombre) {
        $sql = "SELECT * FROM {$this->table} WHERE Tipo = :rol";
        $stmt = $this->executeRawQuery($sql, [':rol' => strtoupper($rolNombre)]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRolByUserId($userId) {
        $sql = "SELECT Tipo FROM {$this->table} WHERE ID_Persona = :id LIMIT 1";
        $stmt = $this->executeRawQuery($sql, [':id' => $userId]);
        $tipo = $stmt->fetchColumn();
        return [$tipo]; // Retorna un array con el rol
    }

}
