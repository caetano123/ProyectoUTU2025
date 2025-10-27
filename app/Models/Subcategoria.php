<?php 

namespace App\Models;
use App\Core\Model;
use PDO;

class Subcategoria extends Model{

	public function __construct(){
			$this->table = "Subcategorias";
			$this->primaryKey = "ID_Subcategoria";

			parent::__construct();
	}

	public function addSubcategoria($nombre, $id_categoria){
   			$sql = "INSERT INTO {$this->table} (Nombre, ID_Categoria) VALUES (:nombre, :id_categoria)";
    		$params = [
        	':nombre' => $nombre,
       		':id_categoria' => $id_categoria
			];
  		  $stmt = $this->executeRawQuery($sql, $params);

   		 if ($stmt && $stmt->rowCount() > 0) {
       		 return $this->db->lastInsertId();
    	} else {
        	return false;
    	}
	}

	public function findById($id) {
		$sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
		$stmt = $this->executeRawQuery($sql, [':id' => $id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

}
