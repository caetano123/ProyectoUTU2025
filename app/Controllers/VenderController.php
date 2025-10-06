<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Servicio;

class VenderController extends Controller {

    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new Servicio();
    }

    public function index() {
        return $this->render('vender', [
            'title' => 'Venta de Servicios'
        ]);
    }

    public function crear() {
    

        $this->modelo->create([
            'Nombre' => $_POST['titulo'] ?? '',
            'ID_Categoria' => $_POST['id_categoria'] ?? null,
            'Descripcion' => $_POST['descripcion'] ?? '',
            'Precio' => $_POST['precio'] ?? 0,
        
        ]);

        $this->session->flash("success","Servicio agregado");
        header("Location: /vender");
        exit;
    }

      public function eliminar($id) {
            
              $id = $_GET['id'] ?? 0;
           $this->modelo->delete(['ID_Servicio' => $id]);

             $this->session->flash("success", "Producto eliminado");
        
             header("Location: /buscar");
         exit;
}
}
