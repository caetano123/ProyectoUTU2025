<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Servicio;
use App\Models\Categoria;

class BuscarController extends Controller {

    private $modelo;
    private $categoriaModel;

    public function __construct() {
        parent::__construct();
        $this->modelo = new Servicio();
        $this->categoriaModel = new Categoria();
    }

  public function index() {
    $q = $_GET['query'] ?? '';
    $categoria = $_GET['categoria'] ?? '';
    $servicios = []; 

    if (!empty($q)) {
        $servicios = $this->modelo->search($q, $categoria);

    } elseif (!empty($categoria)) {
        $id_categoria = $this->categoriaModel->findByName($categoria);

        if ($id_categoria) {
            $servicios = $this->modelo->getByCategoryId($id_categoria);
        } else {
            $servicios = []; 
        }

    } else {
        $servicios = $this->modelo->all();
    }

    return $this->render("buscar", [
        "title" => "Buscar Servicios",
        "servicios" => $servicios
        
    ]);
}
}
