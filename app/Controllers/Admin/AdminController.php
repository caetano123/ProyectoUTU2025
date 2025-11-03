<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\Servicio;
use App\Models\Notificacion;

class AdminController extends Controller
{
    protected $view;
    protected $userModel;
    protected $servicioModel;
    protected $notificacionModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->servicioModel = new Servicio();
        $this->notificacionModel = new Notificacion();
    }

    private function checkAdmin() {
        $currentUser = $this->auth->user();
        $isAdmin = (isset($currentUser['Tipo']) && $currentUser['Tipo'] == 'ADMIN');

        if (!$isAdmin) {
            return $this->redirect('/login');
        }
        
        return null;
    }


    public function index() {
        
        $redirect = $this->checkAdmin();
        if ($redirect) {
            return $redirect;
        }

        $currentUser = $this->auth->user();
        $clientes = $this->userModel->findByRol('USUARIO');
        $servicios = $this->servicioModel->findAll();

        return $this->render('admin/index', [
            "title" => 'Panel de Administración ' . ($currentUser['Nombre'] ?? 'Usuario'),
            "clientes" => $clientes,
            "servicios" => $servicios
        ]);
    }

 public function deleteUser() {
    $redirect = $this->checkAdmin();
    if ($redirect) {
        return $redirect;
    }

    $userId = $_POST['id'] ?? null;

    if (!$userId || !is_numeric($userId)) {
        $this->session->flash('error', 'ID de usuario inválido.');
        return $this->redirect('/admin');
    }

    $currentAdminId = $this->auth->user()['ID_Persona'] ?? null;
    if ($userId == $currentAdminId) {
        $this->session->flash('error', 'No puedes eliminar tu propia cuenta de administrador.');
        return $this->redirect('/admin');
    }

    $user = $this->userModel->findById($userId);
    if (empty($user)) {
        $this->session->flash('error', 'El usuario que intentas eliminar no existe.');
        return $this->redirect('/admin');
    }

    try {
        $this->userModel->delete(['ID_Persona' => $userId]);
        $this->session->flash('success', 'Usuario eliminado correctamente.');

    } catch (\Exception $e) {
        $this->session->flash('error', 'Ocurrió un error al eliminar el usuario.');
    }

    return $this->redirect('/admin');
    }

    // ... (dentro de AdminController)

    /**
     * Muestra el formulario para enviar una notificación a un usuario.
     */
    public function showNotifyForm() {
        // 1. Asegurarse de que el usuario es Admin
        $redirect = $this->checkAdmin();
        if ($redirect) {
            return $redirect;
        }

        // 2. Obtener el ID del usuario de la URL (ej: /admin/notify?id=5)
        $userId = $_GET['id'] ?? null;
        $usuario_a_notificar = $this->userModel->findById($userId);

        if (!$usuario_a_notificar) {
            $this->session->flash('error', 'Usuario no encontrado.');
            return $this->redirect('/admin');
        }

        // 3. Renderizar la vista del formulario
        return $this->view->render('admin/notificar', [
            "titulo" => "Notificar a " . $usuario_a_notificar['Nombre'],
            "usuario" => $usuario_a_notificar
        ]);
    }

    /**
     * Procesa el envío del formulario y crea la notificación.
     */
    public function sendNotification() {
        // 1. Asegurarse de que el usuario es Admin
        $redirect = $this->checkAdmin();
        if ($redirect) {
            return $redirect;
        }

        // 2. Obtener los datos del formulario POST
        $userId = $_POST['id_usuario'] ?? null;
        $mensaje = $_POST['mensaje'] ?? null;
        $url = $_POST['url'] ?? '/notificaciones'; // URL opcional, por defecto al historial

        if (!$userId || empty($mensaje)) {
            $this->session->flash('error', 'Faltan datos (usuario o mensaje).');
            return $this->redirect('/admin/notificar');
        }

        // 3. Crear la notificación
        try {
            // (Opcional) Añadir quién la envía
            $adminNombre = $this->auth->user()['Nombre'];
            $mensajeCompleto = "**Mensaje del administrador ({$adminNombre}):** " . $mensaje;

            $this->notificacionModel->crearNotificacion([ // O ->create() si es genérico
                'ID_Persona' => $userId,
                'Mensaje'    => $mensajeCompleto,
                'URL'        => $url,
                'Leida'      => 0
            ]);

            $this->session->flash('success', '¡Notificación enviada con éxito!');
            return $this->redirect('/admin');

        } catch (\Exception $e) {
            // var_dump($e->getMessage()); die; // Descomenta para depurar
            $this->session->flash('error', 'No se pudo enviar la notificación.');
            return $this->redirect('/admin/notificar');
        }
    }

    public function makeAdmin() {
        $redirect = $this->checkAdmin();
        if ($redirect) {
            return $redirect;
        }

        // 2. Obtener el ID del usuario a ascender (desde el formulario POST)
        $userIdToPromote = $_POST['id'] ?? null;

        if (!$userIdToPromote || !is_numeric($userIdToPromote)) {
            $this->session->flash('error', 'ID de usuario inválido.');
            return $this->redirect('/admin');
        }

        // 3. (Opcional) Evitar que un admin se "ascienda" a sí mismo
        $currentAdminId = $this->auth->user()['ID_Persona'] ?? null;
        if ($userIdToPromote == $currentAdminId) {
            $this->session->flash('info', 'Ya eres administrador.');
            return $this->redirect('/admin');
        }

        // 4. Actualizar el rol del usuario en la Base de Datos
        try {
            $this->userModel->update(
                ['ID_Persona' => $userIdToPromote], 
                ['Tipo' => 'ADMIN']                
            );
            
            $this->session->flash('success', 'Usuario ascendido a Administrador.');

        } catch (\Exception $e) {
            // var_dump($e->getMessage()); die; // Descomenta para depurar
            $this->session->flash('error', 'No se pudo ascender al usuario.');
        }

        // 5. Redirigir de vuelta al panel
        return $this->redirect('/admin');
    }
}