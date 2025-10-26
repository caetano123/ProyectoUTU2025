<?php
namespace App\Controllers\Servicios;

use App\Core\Controller;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Zona;
use App\Models\User;

class ServicioController extends Controller {

    protected $servicioModel;
    protected $categoriaModel;
    protected $subcategoriaModel;
    protected $zonaModel;
    protected $usuarioModel;

    public function __construct(){
        $this->servicioModel = new Servicio();
        $this->categoriaModel = new Categoria();
        $this->subcategoriaModel = new Subcategoria();
        $this->zonaModel = new Zona();
        $this->usuarioModel = new User();

        parent::__construct();
    }

    public function show(){
        $id = $_GET['id'] ?? null;

        $servicio = $this->servicioModel->findById($id);

        if (!$servicio) {
            return $this->render('errors/404', ['title' => 'Servicio No Encontrado']);
        }

        $categoria = $this->categoriaModel->findById($servicio['ID_Categoria']);
        $subcategoria = $this->subcategoriaModel->findById($servicio['ID_Subcategoria']);
        $zona = $this->zonaModel->findById($servicio['ID_Zona']);
        $usuario = $this->usuarioModel->findById($servicio['ID_Persona']);
        $id_usuario = $usuario['ID_Persona'];


        return $this->render('servicio/show', [
            'title' => $servicio['Nombre'],
            'servicio' => $servicio,
            'categoria' => $categoria,
            'subcategoria' => $subcategoria,
            'zona' => $zona,
            'usuario' => $usuario,
            'id_usuario' => $id_usuario
        ]);
    }
}   
       