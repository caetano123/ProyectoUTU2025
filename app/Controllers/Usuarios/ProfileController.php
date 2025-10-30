<?php
namespace App\Controllers\Usuarios;

use App\Core\Controller;
use App\Models\User;
use App\Models\Servicio; 

class ProfileController extends Controller {

    protected $userModel;
    protected $servicioModel;

    public function __construct() {
        
        $this->userModel = new User();
        $this->servicioModel = new Servicio();
        parent::__construct();
        $this->checkAuth();
    }

    public function index() {
        $id = $_GET['id'] ?? null;

        $loggedInUser = $this->auth->user();
        $loggedInUserId = $loggedInUser['ID_Persona'] ?? null;

        $profileUser = $id ? $this->userModel->findById($id) : $loggedInUser;

         if (!$profileUser) {
            $this->session->flash('error', 'El perfil solicitado no existe.');
            return $this->redirect('/home'); 
        }

        $profileUserId = $profileUser['ID_Persona'];
        $is_owner = ($loggedInUserId == $profileUserId);


        $servicios = [];
        try {
            $servicios = $this->servicioModel->getByUserId($profileUserId);
        } catch (\Exception $e) {
            error_log("Error al cargar servicios del usuario " . ($profileUserId ?? 'N/A') . ": " . $e->getMessage());
            $servicios = []; 
        }

        return $this->render('profile/index', [
            'title' => 'Perfil de ' . ($profileUser['Nombre'] ?? 'Usuario'),
            'user' => $profileUser,
            'servicios' => $servicios,
            'is_owner' => $is_owner
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
