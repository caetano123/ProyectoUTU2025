<?php
namespace App\Models;
use App\Core\Model;

class Servicio extends Model {

    public function __construct() {
        $this->table = "Servicios";  
        $this->primaryKey = "ID_Servicio";
        parent::__construct();
    }

    public function createServicio($data) {
        return $this->create([
            'NombreServicio' => $data['Nombre'] ?? null,
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

}


