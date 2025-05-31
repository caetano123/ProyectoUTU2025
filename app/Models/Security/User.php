<?php
namespace App\Models\Security;

use Core\Database;
use PDO;

class User {
    public static function findByEmail($email) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM Usuarios WHERE Email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO Usuarios (CI, Nombre, Apellido, Email, Telefono, Direccion, Contraseña, Tipo) 
                              VALUES (:ci, :nombre, :apellido, :email, :telefono, :direccion, :contrasena, :tipo)");
        return $stmt->execute([
            ':ci' => $data['ci'],
            ':nombre' => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':email' => $data['email'],
            ':telefono' => $data['telefono'],
            ':direccion' => $data['direccion'],
            ':contrasena' => $data['contrasena'],
            ':tipo' => $data['tipo']
        ]);
    }

    public static function all() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT CI, Nombre, Apellido, Email, Telefono, Direccion, Tipo FROM Usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($ci) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT CI, Nombre, Apellido, Email, Telefono, Direccion, Tipo FROM Usuarios WHERE CI = :ci");
        $stmt->execute([':ci' => $ci]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($ci, $data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE Usuarios SET 
            Nombre = :nombre, 
            Apellido = :apellido, 
            Telefono = :telefono, 
            Direccion = :direccion, 
            Tipo = :tipo
            WHERE CI = :ci");
        return $stmt->execute([
            ':nombre' => $data['Nombre'],
            ':apellido' => $data['Apellido'],
            ':telefono' => $data['Telefono'],
            ':direccion' => $data['Direccion'],
            ':tipo' => $data['Tipo'],
            ':ci' => $ci
        ]);
    }
}
