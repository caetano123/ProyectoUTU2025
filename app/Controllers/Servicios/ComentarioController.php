<?php
namespace App\Controllers\Usuarios;

use App\Core\Controller;
use App\Models\Servicio;
use App\Models\Notificacion;

class ComentarioController extends Controller {

    private $servicioModel;
    private $notificacionModel;

    public function __construct() {
        $this->servicioModel = new Servicio();
        $this->notificacionModel = new Notificacion();

        parent::__construct();
    }

public function guardarComentario() {
    $servicio = $this->servicioModel->findById($_POST['ID_Servicio']);
    
    $idAutorServicio = $servicio['ID_Persona'];
    $nombreUsuarioQueComenta = $this->auth->user()['Nombre'];
    
    if ($idAutorServicio != $this->auth->user()['ID_Persona']) {
                
        $datosNotificacion = [
            'ID_Usuario' => $idAutorServicio, 
            'Mensaje'    => $nombreUsuarioQueComenta . ' ha comentado en tu servicio.',
            'URL'        => '/servicio?id=' . $servicio['ID_Servicio']
        ];

        $this->notificacionModel->create($datosNotificacion);
    }

    return $this->redirect('servicio?id=' . $servicio['ID_Servicio']);
}
}