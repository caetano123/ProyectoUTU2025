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
        $pagina = $parm["pagina"] ?? 1;

        $sql = "SELECT 
                    CONCAT(u.Nombre, ' ', u.Apellido) AS usuario,
                    c.Nombre AS categoria,
                    p.Titulo,
                    p.Contenido
                FROM Posts p
                JOIN Categorias c ON p.ID_Categoria = c.ID_Categoria
                JOIN Usuarios u ON u.ID_Usuarios = p.ID_Usuario
                ORDER BY p.ID_Usuario, p.ID_Categoria";

        $datos = $this->modelo->sqlPaginado($sql, $pagina);
        $datos["baseUrl"] = "/post/paginar";
        $datos["titulo"] = "Todos los Posts ingresados";

        $this->render("post/index", $datos );
    }
}
