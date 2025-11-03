<?php
namespace App\Controllers;

use App\Core\Controller;    
use App\Models\Notificacion;
use App\Models\User;

class NotificacionController extends Controller {

    protected $notificacionModel;
    protected $userModel;

    public function __construct() {
        $this->notificacionModel = new Notificacion();
        $this->userModel = new User();

        parent::__construct();
    }

    public function index() {
        if (!$this->auth->check()) {
            header("Location: /login");
            exit();
        }

        $currentUser = $this->auth->user();
        $userId = $currentUser['ID_Persona'];

        $notificaciones = $this->notificacionModel->getLatestNotifications($userId, 50);

        return $this->render('/notificaciones', [
            'title' => 'Notificaciones de ' . $currentUser['Nombre'],
            'notificaciones' => $notificaciones,
            'currentUser' => $currentUser
        ]);
    }

    public function check() {
        $userId = $this->auth->user()['ID_Persona'] ?? null;
        if (!$userId) {
            // Devuelve JSON vacío si no está logueado
            return $this->json(['items' => []], 401); // 401 Unauthorized
        }

        try {
            // Asumimos que tu modelo tiene este método
            $items = $this->notificacionModel->getLatestNotifications($userId, 15); 
            
            // Devuelve los items como JSON
            return $this->json(['items' => $items ?? []]);

        } catch (\Exception $e) {
            // Devuelve un error 500 si la base de datos falla
            return $this->json(['error' => 'Error al consultar la base de datos'], 500);
        }
    }
}
