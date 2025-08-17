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

public function index($param){
    $pagina = $param['pagina'] ?? 1;

    $datos = $this->modelo->getAllPosts($pagina);

    // Si no hay datos -> redirigir con flash
    if (empty($datos['datosTabla']) || count($datos['datosTabla']) === 0) {
        $this->session->flash('error', 'No hay posts disponibles.');
        // Podés redirigir al home o a otra ruta
        $this->redirect('/'); 
        return;
    }

    $datos["baseUrl"] = "/post/paginar";
    $datos["titulo"] = "Listado de Posts";

    $this->render("post/index", $datos);
}
}
