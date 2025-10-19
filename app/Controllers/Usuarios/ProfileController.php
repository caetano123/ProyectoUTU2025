<?php
namespace App\Controllers\Usuarios;

use App\Core\Controller;
use App\Models\User;
use App\Models\Servicio; 

class ProfileController extends Controller {

    protected $userModel;
    protected $servicioModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->servicioModel = new Servicio();
        // Nos aseguramos de que el usuario esté autenticado para cualquier método de este controlador.
        $this->checkAuth();
    }

    private function checkAuth()
    {
        if (!$this->auth->check()) {
            $this->redirect('/login');
            exit(); 
        }
    }

    public function index($userId = null) {
        // Si no se especifica un ID, mostramos el perfil del usuario logueado.
        $user = $userId ? $this->userModel->findById($userId) : $this->auth->user();
        
        if (!$user) {
             // Si el usuario no existe, redirigir a alguna página de error o al inicio.
             return $this->redirect('/dashboard');
        }

        $servicios = [];
        try {
            $servicios = $this->servicioModel->getByUserId($user['ID_Persona']);
        } catch (\Exception $e) {
            error_log("Error al cargar servicios del usuario " . ($user['ID_Persona'] ?? 'N/A') . ": " . $e->getMessage());
            $servicios = []; 
        }


        // --> La vista 'profile/index' es para MOSTRAR el perfil (solo vista).
        return $this->render('profile/index', [
            'title' => 'Perfil de ' . ($user['Nombre'] ?? 'Usuario'),
            'user' => $user,
            'servicios' => $servicios
        ]);
    }

    public function edit() {
        $user = $this->auth->user();

        // --> La vista 'profile/update' es para el FORMULARIO de edición.
        return $this->render('profile/update', [
            'title' => 'Editar Perfil',
            'user' => $user
        ]);
    }

    public function save() {

        $data = $_POST;
        $files = $_FILES;

        $currentUser = $this->auth->user();
        $userId = $currentUser['ID_Persona'];

        $validatedData = [];

        if (!empty($data['nombre'])) {
            $validatedData['Nombre'] = trim($data['nombre']);
        }
        if (!empty($data['apellido'])) {
            $validatedData['Apellido'] = trim($data['apellido']);
        }
        if (!empty($data['telefono'])) {
            $validatedData['Telefono'] = trim($data['telefono']);
        }
        if (!empty($data['correo'])) {
            $email = filter_var(trim($data['correo']), FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->flash('error', 'El correo electrónico proporcionado no es válido.');
                return $this->redirect('/profile/edit'); // Redirigir de vuelta al formulario de edición
            }
            $validatedData['Correo'] = $email;
        }

        if (isset($files['nueva_foto']) && $files['nueva_foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $files['nueva_foto'];
            $nombreArchivo = uniqid('perfil_', true) . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
            
            // Ruta absoluta construida dinámicamente.
            // Esto asume que la carpeta 'public' está en el directorio raíz de la aplicación.
            $directorioSubidas = dirname(__DIR__, 3) . '/public/assets/profiles/';
            $rutaDestino = $directorioSubidas . $nombreArchivo;

            if (move_uploaded_file($foto['tmp_name'], $rutaDestino)) {
                $validatedData['UrlFoto'] = '/assets/profiles/' . $nombreArchivo;
            } else {
                $this->session->flash('error', 'Error al guardar la imagen.');
                return $this->redirect('/profile/edit');
            }
        }

        if (empty($validatedData)) {
            $this->session->flash('info', 'No se proporcionaron datos para actualizar.');
            return $this->redirect('/profile/edit');
        }

        try {
            $isUpdated = $this->userModel->updateUser($userId, $validatedData);

            if ($isUpdated) {
                $this->session->flash('success', '¡Tu perfil ha sido actualizado con éxito!');

             // Combinamos los datos de la sesión actual con los datos validados que acabamos de guardar.
                //    Esto evita una consulta extra a la base de datos y asegura que todos los campos están presentes.
                $updatedUserSession = array_merge($currentUser, $validatedData);

                // Forzamos al sistema de autenticación a actualizar la sesión con los datos combinados y actualizados.
                $this->auth->login($updatedUserSession);

            } else {
                // Esto puede pasar si el usuario guarda sin cambiar nada.
                $this->session->flash('info', 'No se realizaron cambios en el perfil.');
            }

        } catch (\Exception $e) {
            error_log("Profile update error: " . $e->getMessage());
            $this->session->flash('error', 'Error interno al intentar actualizar el perfil.');
        }
        
        // --> Al final, redirigimos a la página de VER el perfil.
        return $this->redirect('/profile');
    }

}
