<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Servicio;

class VenderController extends Controller {

    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new Servicio();

        $this->checkAuth();
    }
    
    public function index() {
        return $this->render('vender', [
            'title' => 'Vender un Servicio'
        ]);
    }

    public function crear() {
        $currentUser = $this->auth->user();

        $titulo = trim($_POST['Titulo'] ?? '');
        $id_categoria = $_POST['ID_Categoria'] ?? null;
        $id_zona = $_POST['ID_Zona'] ?? null;
        $precio = $_POST['Precio'] ?? null;
        $descripcion = trim($_POST['Descripcion'] ?? '');

        $id_persona = $currentUser['ID_Persona'];

        if (!is_numeric($precio) || $precio < 0) {
            $this->session->flash("error", "El precio ingresado no es válido. Por favor, introduce solo números.");
    
            header("Location: /vender");
            exit;
        }

        if (empty($titulo)) {
            $this->session->flash("error", "El título del servicio es obligatorio.");
            header("Location: /vender");
            exit;
        }

        try {
            $this->modelo->create([
                'Nombre' => $titulo,
                'ID_Categoria' => $id_categoria,
                'ID_Zona' => $id_zona,
                'Descripcion' => $descripcion,
                'Precio' => (float)$precio, 
                'ID_Persona' => $id_persona
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

    public function eliminar($id) {

        $currentUser = $this->auth->user();
        $servicio = $this->modelo->findById($id);

        if (!$servicio || $servicio['ID_Persona'] !== $currentUser['ID_Persona']) {
            $this->session->flash("error", "No tienes permiso para eliminar este servicio.");
            header("Location: /buscar");
            exit;
        }
        
        $this->modelo->delete(['ID_Servicio' => $id]);
        $this->session->flash("success", "Servicio eliminado correctamente.");
        header("Location: /buscar"); 
        exit;
    }
}

