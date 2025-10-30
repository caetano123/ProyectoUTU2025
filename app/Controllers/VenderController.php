<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Servicio;
use App\Models\Subcategoria;

class VenderController extends Controller {

    private $modelo;
    private $subcategoriaModelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new Servicio();
        $this->subcategoriaModelo = new Subcategoria();

        $this->checkAuth();
    }
    
    public function index() {
        return $this->render('vender', [
            'title' => 'Vender un Servicio'
        ]);
    }

    public function crear() {
        $currentUser = $this->auth->user();

        $titulo = trim($_POST['titulo'] ?? '');

        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = $_POST['precio'] ?? null;
        $id_categoria = $_POST['categoria'] ?? null;
        $subcategoria = $_POST['subcategoria'] ?? null;
        
        $id_persona = $currentUser['ID_Persona'];
        $id_zona = $_POST['zona'] ?? null;

        if (empty($titulo)) {
            $this->session->flash("error", "El título del servicio es obligatorio.");
            header("Location: /vender");
            exit;
        }

        if (!is_numeric($precio)) {
            $this->session->flash("error", "El precio ingresado no es válido. Por favor, introduce solo números.");
            header("Location: /vender");
            exit;
        }

        if (isset($subcategoria) && !empty($subcategoria)) {
            $id_subcategoria = $this->subcategoriaModelo->addSubcategoria($subcategoria, $id_categoria);
        }

        try {
            $this->modelo->create([
                'Nombre' => $titulo,
                'Descripcion' => $descripcion,
                'Precio' => $precio, 
                'ID_Categoria' => $id_categoria,
                'ID_Subcategoria' => $id_subcategoria,
                'ID_Persona' => $id_persona,
                'ID_Zona' => $id_zona,
                'FechaPublicacion' => date('Y-m-d H:i:s')
            ]);

            $this->session->flash("success","¡Servicio agregado correctamente!");
            header("Location: /profile"); 
            exit;
        } catch (\Exception $e) {

            error_log("Error al crear servicio: " . $e->getMessage());
            $this->session->flash('error', 'Ocurrió un error al guardar el servicio. Inténtalo de nuevo.');
            header("Location: /vender");
            exit;
        }
    }

}