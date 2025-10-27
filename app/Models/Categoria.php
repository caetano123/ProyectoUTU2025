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

	public function findByName($name) {
		$sql = "SELECT {$this->primaryKey} FROM {$this->table} WHERE Nombre = :name";
		$stmt = $this->executeRawQuery($sql, [':name' => $name]);
		return $stmt->fetchColumn();
	}
}
