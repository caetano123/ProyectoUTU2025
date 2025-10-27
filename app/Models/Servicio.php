<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class Servicio extends Model {

    public function __construct() {
        $this->table = "Servicios";  
        $this->primaryKey = "ID_Servicio";
        parent::__construct();
    }

    public function createServicio($data) {
        return $this->create([
            'Nombre' => $data['Nombre'] ?? null,
            'Descripcion' => $data['Descripcion'] ?? null,
            'Precio' => $data['Precio'] ?? null,
            'ID_Categoria' => $data['ID_Categoria'] ?? null,
            'ID_Subcategoria' => $data['ID_Subcategoria'] ?? null,
            'ID_Persona' => $data['ID_Persona'] ?? null,
            'ID_Zona' => $data['ID_Zona'] ?? null,
            'FechaPublicacion' => date('Y-m-d H:i:s')
        ]);
    }

    public function search($q = '', $categoria = '') {
    $sql = "SELECT * FROM {$this->table} WHERE 1=1";
    $params = [];

    if (!empty($q)) {
        $sql .= " AND Nombre LIKE :q";
        $params[':q'] = "%$q%";
    }

    if (!empty($categoria)) {
        $sql .= " AND ID_Categoria = :cat";
        $params[':cat'] = $categoria;
    }

    return $this->executeRawQueryArray($sql, $params);
    }


    public function getByUserId($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE ID_Persona = :userId";
        $params = [':userId' => $userId];

        return $this->executeRawQueryArray($sql, $params);
    }

    public function updateById($id, array $data) {
        if (empty($data)) {
            return false;
        }

        $sets = [];
        $values = [];
        
        foreach ($data as $column => $value) {
            $sets[] = "`$column` = ?";
            $values[] = $value;
        }

        $values[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (\PDOException $e) {
            error_log("Error actualizando el servicio: " . $e->getMessage());
            return false;
        }
    }

  public function findById($id) {
        
        if (is_array($id)) {
            $id = $id[0];
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $params = [':id' => $id];

        $result = $this->executeRawQueryArray($sql, $params);

        if (empty($result) || !is_array($result)) {
            return null;
        }

        return $result[0];
    }

    public function getByCategoryId($categoryId) {
        $sql = "SELECT * FROM {$this->table} WHERE ID_Categoria = :catId";
        $params = [':catId' => $categoryId];

        return $this->executeRawQueryArray($sql, $params);
    }
}   
