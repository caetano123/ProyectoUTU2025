<?php
namespace App\Controllers;

use App\Core\Controller;

class BuscarController extends Controller {
    public function index() {
        return $this->render('buscar', [
            'title' => 'Busqueda'
        ]);
    }
}