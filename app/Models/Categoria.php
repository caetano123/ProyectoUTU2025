<?php 

namespace App\Models;
use App\Core\Model;
use PDO;

class Categoria extends Model{

	public function __construct(){
			$this->table = "Categorias";
			$this->primaryKey = "ID_Categoria";

			parent::__construct();
	}

	public function findById($id) {
		$sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
		$stmt = $this->executeRawQuery($sql, [':id' => $id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function findByName($nombre)
{
    $sql = "SELECT ID_Categoria FROM Categorias WHERE Nombre = :nombre LIMIT 1";
    $params = [':nombre' => $nombre];
    
    $result = $this->db->query($sql, $params)->fetch(\PDO::FETCH_ASSOC);
    
    return $result ? $result['ID_Categoria'] : null;
}
}
