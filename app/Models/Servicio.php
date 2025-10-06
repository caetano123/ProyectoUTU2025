<?php
namespace App\Models;
use App\Core\Model;

class Servicio extends Model {

    public function __construct() {
        $this->table = "Servicios";  
        $this->primaryKey = "ID_Servicio";
        parent::__construct();
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


}


