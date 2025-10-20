<?php
Namespace App\Models;
use App\Core\Model;

class Post extends Model {
  
   public function __construct() {
	$this->table = 'Posts';
	$this->primaryKey = 'ID_Posts';
	parent::__construct();		

	}

	public function getAllPosts($pagina = 1){

$sql = "SELECT 
		CONCAT(per.Nombre, ' ', per.Apellido) AS usuario,
		c.Nombre AS categoria,
		p.Titulo,
		p.Contenido,
		p.FechaCreacion
		FROM Posts p
		JOIN Categorias c ON p.ID_Categoria = c.ID_Categoria
		JOIN Personas per ON per.ID_Persona = per.ID_Persona
		ORDER BY p.FechaCreacion DESC";

		return $this->sqlPaginado($sql, $pagina);
	}




}
