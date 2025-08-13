<?php

namespace App\Models;
use App\Core\Model;

class Api extends Model{

	public function __construct(){
			$this->table = "Categorias";
			$this->primaryKey = "ID_Categoria";

			parent::__construct();
	}



}
