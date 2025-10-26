<?php 

namespace App\Models;
use App\Core\Model;
use PDO;

class Zona extends Model{

	public function __construct(){
			$this->table = "Zonas";
			$this->primaryKey = "ID_Zona";

			parent::__construct();
	}

	public function findById($id) {
		$sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
		$stmt = $this->executeRawQuery($sql, [':id' => $id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

}

