<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Servicio;

class BuscarController extends Controller {

    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new Servicio();
    }

  public function index() {
    $q = $_GET['query'] ?? '';
    $categoria = $_GET['categoria'] ?? '';

    if ($q || $categoria) {
        $servicios = $this->modelo->search($q, $categoria);
    } else {
        $servicios = $this->modelo->all();
    }

    return $this->render("buscar", [
        "title" => "Buscar Servicios",
        "servicios" => $servicios
    ]);
}

}
