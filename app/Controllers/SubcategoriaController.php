<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Subcategoria;

class SubcategoriaController extends Controller
{
    protected $subcategoriaModel;

    public function __construct() {
        parent::__construct();
        $this->subcategoriaModel = new Subcategoria();
    }

    public function getByCategoria() {
        $categoriaId = $_GET['categoria_id'] ?? null;

        if (!$categoriaId || !is_numeric($categoriaId)) {
            return $this->json(['error' => 'ID de categoría inválido'], 400);
        }

        $subcategorias = $this->subcategoriaModel->findBy(['ID_Categoria' => $categoriaId]);

        return $this->json($subcategorias);
    }
}