<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Models\Post;

class PostController extends Controller{

	 private $modelo;		

    public function __construct(){
		$this->modelo = new Post();
		parent::__construct();
	}


public function index($parm){

//	$datos = $this->modelo->all();
$pagina = $parm["pagina"]??1;

	$sql = "select u.username, c.name, p.title, p.content FROM Posts p JOIN Categories c ON p.category_id = c.category_id JOIN Users u ON u.user_id = p.user_id ORDER BY p.user_id, p.category_id";
$sql = "";
	$datos = $this->modelo->sqlPaginado($sql, $pagina);
	$datos["baseUrl"] = "/post/paginar";
	$datos["titulo"] = "Todos los Post ingresados";

	$this->render("post/index", $datos );

}


}

