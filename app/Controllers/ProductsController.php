<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Products;

class ProductsController extends Controller {

private $modelo;


public function __construct(){
$this->modelo = new Products();

parent::__construct();

}


        public function index() {
        $datos = $this->modelo->productosAll();


              return $this->render('products/index',
              [
            'title' => 'Productos:', "productos" => $datos
        ]);


        }


        public function ingresar() {
    $id = $this->input('id');
    $nombre = $this->input('nombre');
    $descripcion = $this->input('descripcion');

    if ($id && $nombre && $descripcion) {
        // Insertar producto
        $this->modelo->create([
            'producto_id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ]);
    } else {
        // Faltan datos, mostramos error
        $error = 'Faltan datos para ingresar el producto.';
    }

    // Siempre cargamos los productos para mostrar en la vista
    $datos = $this->modelo->productosAll();

    return $this->render('products/index', [
        'title' => 'Productos:',
        'productos' => $datos,
        'error' => $error ?? null  // Solo si hubo error
    ]);
}


}