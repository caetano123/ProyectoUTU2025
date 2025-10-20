<?php
namespace App\Controllers\Servicios;

use App\Core\Controller;
use App\Models\Servicio; 

class ServicioController extends Controller {

    protected $servicioModel;

    public function __construct() {
        parent::__construct();
        $this->servicioModel = new Servicio();
        // Nos aseguramos de que el usuario esté autenticado para cualquier acción de servicios.
        $this->checkAuth();
    }

    private function checkAuth()
    {
        if (!$this->auth->check()) {
            $this->redirect('/login');
            exit(); 
        }
    }

    /**
     * Muestra el formulario para editar un servicio existente.
     * Esta es la función que resolverá tu error 404.
     */
    public function edit($servicioId) {
        $currentUser = $this->auth->user();
        $servicio = $this->servicioModel->findById($servicioId);

        // --- Verificación de Seguridad ---
        // 1. ¿Existe el servicio?
        // 2. ¿El servicio le pertenece al usuario actual?
        if (!$servicio || $servicio['ID_Persona'] !== $currentUser['ID_Persona']) {
            $this->session->flash('error', 'No tienes permiso para editar este servicio.');
            return $this->redirect('/profile');
        }

        // Renderiza la vista del formulario de edición de servicios
        return $this->render('servicios/edit', [
            'title' => 'Editar Servicio',
            'servicio' => $servicio
        ]);
    }

    /**
     * Procesa los datos del formulario de edición y los actualiza en la BD.
     */
    public function update($servicioId, $data) {
        $currentUser = $this->auth->user();
        $servicio = $this->servicioModel->findById($servicioId);

        // Repetimos la verificación de seguridad
        if (!$servicio || $servicio['ID_Persona'] !== $currentUser['ID_Persona']) {
            $this->session->flash('error', 'Acción no permitida.');
            return $this->redirect('/profile');
        }

        // --- Validación de Datos ---
        $validatedData = [];
        if (!empty($data['NombreServicio'])) {
            $validatedData['NombreServicio'] = trim($data['NombreServicio']);
        }
        if (!empty($data['Descripcion'])) {
            $validatedData['Descripcion'] = trim($data['Descripcion']);
        }
        // ... puedes añadir más validaciones aquí

        if (empty($validatedData)) {
            $this->session->flash('info', 'No se proporcionaron datos para actualizar.');
            return $this->redirect('/servicios/edit/' . $servicioId);
        }

        try {
            $isUpdated = $this->servicioModel->updateById($servicioId, $validatedData);
            if ($isUpdated) {
                $this->session->flash('success', '¡Servicio actualizado con éxito!');
            } else {
                $this->session->flash('info', 'No se realizaron cambios.');
            }
        } catch (\Exception $e) {
            error_log("Service update error: " . $e->getMessage());
            $this->session->flash('error', 'Error interno al actualizar el servicio.');
        }

        // Redirigir de vuelta al perfil del usuario para que vea la lista actualizada.
        return $this->redirect('/profile');
    }

    // --- Métodos Adicionales (que probablemente necesitarás) ---

    /**
     * Muestra el formulario para crear un nuevo servicio.
     */
    public function create() {
        return $this->render('servicios/create', [
            'title' => 'Añadir Nuevo Servicio'
        ]);
    }

    /**
     * Guarda el nuevo servicio en la base de datos.
     */
    public function store($data) {
        // Lógica para validar y guardar un nuevo servicio...
        // Al terminar, redirige a /profile
        return $this->redirect('/profile');
    }
}
