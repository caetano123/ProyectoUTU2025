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
        $this->checkAuth();
    }

    public function index($userId = null) {
        $user = $userId ? $this->userModel->findById($userId) : $this->auth->user();
        
        if (!$user) {
             return $this->redirect('/dashboard');
        }

        $servicios = [];
        try {
            $servicios = $this->servicioModel->getByUserId($user['ID_Persona']);
        } catch (\Exception $e) {
            error_log("Error al cargar servicios del usuario " . ($user['ID_Persona'] ?? 'N/A') . ": " . $e->getMessage());
            $servicios = []; 
        }

        return $this->render('profile/index', [
            'title' => 'Perfil de ' . ($user['Nombre'] ?? 'Usuario'),
            'user' => $user,
            'servicios' => $servicios
        ]);
    }


    public function edit() {
        $user = $this->auth->user();

        return $this->render('profile/update', [
            'title' => 'Editar Perfil',
            'user' => $user
        ]);
    }

    public function save() {

        $data = $_POST;

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

        if (empty($validatedData)) {
            $this->session->flash('error', 'No se proporcionaron datos para actualizar.');
            return $this->redirect('/profile/edit');
        }

        try {
            $isUpdated = $this->userModel->updateUser($userId, $validatedData);

            if ($isUpdated) {
                $this->session->flash('success', '¡Tu perfil ha sido actualizado con éxito!');

             // Combinamos los datos de la sesión actual con los datos validados que acabamos de guardar.
                //    Esto evita una consulta extra a la base de datos y asegura que todos los campos están presentes.
                $updatedUserSession = array_merge($currentUser, $validatedData);

                 $this->auth->login($updatedUserSession);
            }

        } catch (\Exception $e) {
            error_log("Profile update error: " . $e->getMessage());
            $this->session->flash('error', 'Error interno al intentar actualizar el perfil.');
        }
        
        return $this->redirect('/profile');
    }

}
