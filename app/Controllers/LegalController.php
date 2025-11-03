<?php

namespace App\Controllers;

use App\Core\Controller;

class LegalController extends Controller
{
    public function terminos()
    {
        return $this->render('legal/terminos', [
            'title' => 'Términos y Condiciones'
        ]);
    }

}
