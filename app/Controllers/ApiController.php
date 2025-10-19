<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Models\Api;

class ApiController extends Controller {

    private $modelo;

    public function __construct() {
        $this->modelo = new Api();
        parent::__construct();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['miDato'])) {

            // Limpiar cualquier salida previa
            while (ob_get_level()) ob_end_clean();

            header('Content-Type: application/json; charset=utf-8');

            try {
                $miDato = trim($_POST['miDato']);
                $categoria = $this->modelo->find(['Nombre' => $miDato]);

                if ($categoria) {
                    $sql = "SELECT p.ID_Posts, p.Titulo, p.Contenido, u.Nombre AS Usuario, c.Nombre AS Categoria
                            FROM Posts p
                            JOIN Usuarios u ON p.ID_Usuario = u.ID_Usuarios
                            JOIN Categorias c ON p.ID_Categoria = c.ID_Categoria
                            WHERE p.ID_Categoria = ?";
                    
                    $posts = $this->modelo->executeRawQueryArray($sql, [$categoria['ID_Categoria']]);
                } else {
                    $posts = [
                        ['Mensaje' => 'No existe la categoría']
                    ];
                }



                echo json_encode([
                    'status' => 'success',
                    'message' => 'Datos obtenidos correctamente',
                    'data' => $posts
                ]);
            } catch (\Throwable $e) {
                // Captura cualquier error inesperado
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No existe la categoría',
                    'data' => []
                ]);
            }


            exit(); // Terminar ejecución inmediatamente
        }

        // GET: mostramos la vista normalmente
        $categorias = $this->modelo->all();
        $this->render("api/index", [
            "titulo" => "Categorías: Mostrar Artículos",
            "datosTabla" => $categorias
        ]);
    }
}
