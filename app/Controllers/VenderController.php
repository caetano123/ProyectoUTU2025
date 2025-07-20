<?php
namespace App\Controllers;

use App\Core\Controller;

class VenderController extends Controller {
    public function index() {
        return $this->render('vender', [
            'title' => 'Venta de Servicios'
        ]);
    }
}