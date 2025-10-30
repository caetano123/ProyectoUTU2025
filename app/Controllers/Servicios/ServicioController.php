<?php
namespace App\Controllers\Servicios;

use App\Core\Controller;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Zona;
use App\Models\User;
use App\Models\Notificacion;

class ServicioController extends Controller {

    protected $servicioModel;
    protected $categoriaModel;
    protected $subcategoriaModel;
    protected $zonaModel;
    protected $usuarioModel;
    protected $notificacionModel;

    public function __construct(){
        $this->servicioModel = new Servicio();
        $this->categoriaModel = new Categoria();
        $this->subcategoriaModel = new Subcategoria();
        $this->zonaModel = new Zona();
        $this->usuarioModel = new User();
        $this->notificacionModel = new Notificacion();

        parent::__construct();
    }

    public function show(){
        $idServicio = $_GET['id'] ?? null;

        $servicio = $this->servicioModel->findById($idServicio);

        if (!$servicio) {
            return $this->render('errors/404', ['title' => 'Servicio No Encontrado']);
        }

        $categoria = $this->categoriaModel->findById($servicio['ID_Categoria']);
        $subcategoria = $this->subcategoriaModel->findById($servicio['ID_Subcategoria']);
        $zona = $this->zonaModel->findById($servicio['ID_Zona']);
        $usuario = $this->usuarioModel->findById($servicio['ID_Persona']);

        $loggedInUser = $this->auth->user();
        $loggedInUserId = $loggedInUser['ID_Persona'] ?? null;
        $is_owner = ($loggedInUserId === $servicio['ID_Persona']);


        return $this->render('servicio/show', [
            'title' => $servicio['Nombre'],
            'servicio' => $servicio,
            'categoria' => $categoria,
            'subcategoria' => $subcategoria,
            'zona' => $zona,
            'usuario' => $usuario,
            'id_usuario' => $usuario['ID_Persona'],
            'imgPath' => BASE_URL . '/assets/uploads/servicios/' . $servicio['ID_Categoria'] . '.jpg',
            'is_owner' => $is_owner
        ]);
    }



    public function eliminar() {
    $idServicio = $_POST['id'] ?? null;

    if (!$idServicio) {
        $this->session->flash('error', 'ID de servicio inválido.');
        return $this->redirect('/buscar');
    }

    $servicio = $this->servicioModel->findById($idServicio);

    if (!$servicio) {
        $this->session->flash('error', 'El servicio que intentas eliminar no existe.');
        return $this->redirect('/buscar');
    }

    $loggedInUser = $this->auth->user();

    if (!$loggedInUser) {
        $this->session->flash('error', 'Debes iniciar sesión para eliminar.');
        return $this->redirect('/login');
    }

    $loggedInUserId = $loggedInUser['ID_Persona'] ?? null;
    $loggedInUserRole = $loggedInUser['Tipo'] ?? 'USER';

    if ($loggedInUserId != $servicio['ID_Persona'] && $loggedInUserRole != 'ADMIN') {
        $this->session->flash('error', 'No tienes permiso para eliminar este servicio.');
        return $this->redirect('/buscar');
    }

    try {
        $this->servicioModel->delete(['ID_Servicio' => $idServicio]);
        $this->session->flash('success', 'Servicio eliminado correctamente.');
    } catch (\Exception $e) {
        $this->session->flash('error', 'Ocurrió un error al eliminar el servicio.');
    }

    return $this->redirect('/buscar');
    }



    public function edit() {
        $servicio = $this->servicioModel->findById($_GET['id'] ?? null);

        if (empty($servicio)) {
            return $this->render('errors/404', ['title' => 'Servicio No Encontrado']);
        }

        return $this->render('servicio/edit', [
            'title' => 'Editar Servicio',
            'servicio' => $servicio
        ]);
    }



    public function update() {
        
        $idServicio = $_POST['id'] ?? null;
        $data = $_POST;

        $loggedInUser = $this->auth->user();
        if (!$loggedInUser) {
            $this->session->flash('error', 'Debes iniciar sesión para editar.');
            return $this->redirect('/login');
        }

        $servicio = $this->servicioModel->findById($idServicio);
        if (!$servicio) {
            $this->session->flash('error', 'El servicio que intentas editar no existe.');
            return $this->redirect('/buscar');
        }

        $loggedInUserId = $loggedInUser['ID_Persona'] ?? null;
        $loggedInUserRole = $loggedInUser['Tipo'] ?? 'USER';

        if ($loggedInUserId != $servicio['ID_Persona'] && $loggedInUserRole != 'ADMIN') {
            $this->session->flash('error', 'No tienes permiso para editar este servicio.');
            return $this->redirect('/servicio?id=' . $idServicio);
        }

        $validatedData = [];
        $redirectUrl = '/servicio/edit?id=' . $idServicio; 

        if (empty(trim($data['Nombre']))) {
            $this->session->flash('error', 'El nombre del servicio no puede estar vacío.');
            return $this->redirect($redirectUrl); 
        }
        $validatedData['Nombre'] = trim($data['Nombre']);

        if (!empty($data['Descripcion'])) {
            $validatedData['Descripcion'] = trim($data['Descripcion']);
        }

        if (empty($data['Precio']) || !is_numeric($data['Precio'])) {
             $this->session->flash('error', 'El precio no es válido.');
            return $this->redirect($redirectUrl);
        }
        $validatedData['Precio'] = $data['Precio'];

        if (empty($data['categoria']) || !is_numeric($data['categoria'])) {
             $this->session->flash('error', 'La categoría no es válida.');
            return $this->redirect($redirectUrl);
        }
        $validatedData['ID_Categoria'] = $data['categoria'];

        if (empty($data['zona']) || !is_numeric($data['zona'])) {
             $this->session->flash('error', 'La zona no es válida.');
            return $this->redirect($redirectUrl);
        }
        $validatedData['ID_Zona'] = $data['zona'];

        $subcategoriaNombre = trim($data['subcategoria'] ?? '');
        if (empty($subcategoriaNombre)) {
             $this->session->flash('error', 'La subcategoría no puede estar vacía.');
            return $this->redirect($redirectUrl);
        }

        $subcat = $this->subcategoriaModel->findByName($subcategoriaNombre); 
        
        if ($subcat) {
            $validatedData['ID_Subcategoria'] = $subcat['ID_Subcategoria'];
        } else {
            $newSubcatId = $this->subcategoriaModel->addSubcategoria($subcategoriaNombre, $validatedData['ID_Categoria']);
            $validatedData['ID_Subcategoria'] = $newSubcatId;
        }
        try {
            $this->servicioModel->updateById($idServicio, $validatedData);
            
            $this->session->flash('success', 'Servicio actualizado correctamente.');
            return $this->redirect('/servicio?id=' . $idServicio); 

        } catch (\Exception $e) {
            error_log("Error al actualizar servicio: " . $e->getMessage());
            $this->session->flash('error', 'Ocurrió un error al actualizar el servicio.');
            return $this->redirect($redirectUrl);
        }
    }



    public function contactar() {
        
        $idServicio = $_POST['id'] ?? null;
        if (!$idServicio || !is_numeric($idServicio)) {
            $this->session->flash('error', 'ID de servicio inválido.');
            return $this->redirect('/buscar'); 
        }
        
        $servicio = $this->servicioModel->findById($idServicio); 

        if (empty($servicio)) {
            $this->session->flash('error', 'El servicio ya no existe.');
            return $this->redirect('/buscar');
        }

        $currentUser = $this->auth->user();
        if (!$currentUser) {
            $this->session->flash('error', 'Debes iniciar sesión para contactar.');
            return $this->redirect('/login');
        }

        $idProveedor = $servicio['ID_Persona']; 
        $idUsuarioQueContacta = $currentUser['ID_Persona'];
        $nombreUsuarioQueContacta = $currentUser['Nombre'];

        if ($idProveedor == $idUsuarioQueContacta) {
            $this->session->flash('error', 'No puedes contactarte por tu propio servicio.');

            return $this->redirect('/servicio?id=' . $servicio['ID_Servicio']);
        }

        try {
            $mensaje = "El usuario {$nombreUsuarioQueContacta} está interesado en tu servicio: {$servicio['Nombre']}.";
            $url = "/profile?id=" . $idUsuarioQueContacta; 

            $this->notificacionModel->crearNotificacion([
                'ID_Persona' => $idProveedor,
                'Mensaje'    => $mensaje,
                'URL'        => $url,
                'Leida'      => 0
            ]);

            $this->session->flash('success', '¡Se ha notificado al proveedor!');
        
        } catch (\Exception $e) {
            $this->session->flash('error', 'No se pudo enviar la notificación.');
        }

        return $this->redirect('/servicio?id=' . $servicio['ID_Servicio']);
    }
}
