<?php
namespace App\Controllers\Servicios;

use App\Core\Controller;
use App\Models\Servicio;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Zona;
use App\Models\User;
use App\Models\Notificacion;
use App\Models\Valoracion;   


class ServicioController extends Controller {

    protected $servicioModel;
    protected $categoriaModel;
    protected $subcategoriaModel;
    protected $zonaModel;
    protected $usuarioModel;
    protected $notificacionModel;
    protected $valoracionModel;

    public function __construct(){
        $this->servicioModel = new Servicio();
        $this->categoriaModel = new Categoria();
        $this->subcategoriaModel = new Subcategoria();
        $this->zonaModel = new Zona();
        $this->usuarioModel = new User();
        $this->notificacionModel = new Notificacion();
        $this->valoracionModel = new Valoracion(); 

        parent::__construct();
    }

    public function show(){
    $idServicio = $_GET['id'] ?? null;

    $servicio = $this->servicioModel->findById($idServicio);

    if (!$servicio) {
        return $this->render('errors/404', ['title' => 'Servicio No Encontrado']);
    }

    if (isset($_GET['accion']) && $_GET['accion'] === 'valorar') {
        return $this->valorarForm($servicio);
    }

    $categoria = $this->categoriaModel->findById($servicio['ID_Categoria']);
    $subcategoria = $this->subcategoriaModel->findById($servicio['ID_Subcategoria']);
    $zona = $this->zonaModel->findById($servicio['ID_Zona']);
    $usuario = $this->usuarioModel->findById($servicio['ID_Persona']);

    $loggedInUser = $this->auth->user();
    $loggedInUserId = $loggedInUser['ID_Persona'] ?? null;
    $isOwner = ($loggedInUserId === $servicio['ID_Persona']);
    $isAdmin = (isset($loggedInUser['Tipo']) && $loggedInUser['Tipo'] == 'ADMIN');


    
    $valoracion = $this->valoracionModel->promedioValoraciones($usuario['ID_Persona']);
    $comentarios = $this->valoracionModel->obtenerComentarios($usuario['ID_Persona']);
    $promedio = round($valoracion['promedio'] ?? 0, 1);
    $totalValoraciones = $valoracion['total'] ?? 0;

    return $this->render('servicio/show', [
    'title' => $servicio['Nombre'],
    'servicio' => $servicio,
    'categoria' => $categoria,
    'subcategoria' => $subcategoria,
    'zona' => $zona,
    'usuario' => $usuario,
    'id_usuario' => $usuario['ID_Persona'],
    'imgPath' => BASE_URL . '/assets/uploads/servicios/' . $servicio['ID_Categoria'] . '.jpg',
    'isOwner' => $isOwner,
    'isAdmin' => $isAdmin,
    'valoracion' => $valoracion,
    'promedio' => $promedio,
    'totalValoraciones' => $totalValoraciones,
    'comentarios' => $comentarios
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
        $idServicio = $_GET['id'] ?? null;
        $servicio = $this->servicioModel->find(['ID_Servicio' => $idServicio]);

        if (empty($servicio)) {
            return $this->render('errors/404', ['title' => 'Servicio No Encontrado']);
        }

        $categorias = $this->categoriaModel->all(); 
        $zonas = $this->zonaModel->all();

        return $this->render('servicio/edit', [
            'title' => 'Editar Servicio ',

            'servicio' => $servicio,
            'categorias' => $categorias, 
            'zonas' => $zonas

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

        if (empty($data['nombre'])) {
            $this->session->flash('error', 'El nombre del servicio no puede estar vacío.');
            return $this->redirect($redirectUrl); 
        }
        $validatedData['nombre'] = trim($data['nombre']);

        if (!empty($data['Descripcion'])) {
            $validatedData['Descripcion'] = trim($data['Descripcion']);
        }

        if (empty($data['precio']) || !is_numeric($data['precio'])) {
             $this->session->flash('error', 'El precio no es válido.');
            return $this->redirect($redirectUrl);
        }
        $validatedData['precio'] = $data['precio'];

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

        if (empty($data['subcategoria']) || !is_numeric($data['subcategoria'])) {
            $this->session->flash('error', 'La subcategoría no es válida.');
            return $this->redirect($redirectUrl);
        }
        $validatedData['ID_Subcategoria'] = $data['subcategoria'];

        try {

            $this->servicioModel->update(['ID_Servicio' => $idServicio], $validatedData);
            
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

        $usuarioActual = $this->auth->user();
        if (!$usuarioActual) {
            $this->session->flash('error', 'Debes iniciar sesión para contactar.');
            return $this->redirect('/login');
        }

        $idProveedor = $servicio['ID_Persona']; 
        $idUsuarioQueContacta = $usuarioActual['ID_Persona'];
        $nombreUsuarioQueContacta = $usuarioActual['Nombre'];

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

   public function valorarForm($servicio)
{
    $usuarioActual = $this->auth->user();
    if (!$usuarioActual) {
        $this->session->flash('error', 'Debes iniciar sesión para valorar.');
        return $this->redirect('/login');
    }

    // Verificar que el usuario no sea el dueño del servicio
    if ($usuarioActual['ID_Persona'] === $servicio['ID_Persona']) {
        $this->session->flash('error', 'No puedes valorar tu propio servicio.');
        return $this->redirect('/servicio?id=' . $servicio['ID_Servicio']);
    }

    return $this->render('servicio/valorar', [
        'title' => 'Valorar proveedor',
        'servicio' => $servicio
    ]);
}



    public function valorar()
{
    $usuarioActual = $this->auth->user();
    if (!$usuarioActual) {
        $this->session->flash('error', 'Debes iniciar sesión para valorar.');
        return $this->redirect('/login');
    }

    
    $idServicio = $_POST['id_servicio'] ?? null;
    $idProveedor = $_POST['id_proveedor'] ?? null;
    $puntos = $_POST['puntos'] ?? null;
    $comentario = $_POST['comentario'] ?? '';

    // metodo para redirigir al servicio, si es que existe
    $redirigirAlServicio = function() use ($idServicio) {
        if (!empty($idServicio) && is_numeric($idServicio)) {
            return $this->redirect('/servicio?id=' . (int)$idServicio);
        }
        return $this->redirect('/buscar');
    };

    // Validaciones: que id_proveedor e id_servicio y puntos sean numéricos y válidos
    if (empty($idProveedor) || !is_numeric($idProveedor) || empty($puntos) || !is_numeric($puntos)) {
        $this->session->flash('error', 'Datos de valoración inválidos.');
        return $redirigirAlServicio();
    }

    $idProveedor = (int) $idProveedor;
    $puntos = (int) $puntos;

    if ($puntos < 1 || $puntos > 5) {
        $this->session->flash('error', 'Los puntos deben estar entre 1 y 5.');
        return $redirigirAlServicio();
    }

    $idCliente = $usuarioActual['ID_Persona'];

    // Verificar contacto (si falla, se vuelve al servicio)
    $contacto = $this->notificacionModel->verificarContacto($idCliente, $idProveedor);
    if (!$contacto) {
        $this->session->flash('error', 'Solo puedes valorar proveedores que contactaste.');
        return $redirigirAlServicio();
    }

    // Verificar si valoro
    if ($this->valoracionModel->yaValoro($idCliente, $idProveedor)) {
        $this->session->flash('error', 'Ya valoraste a este proveedor.');
        return $redirigirAlServicio();
    }

    // Guardar valoración
    try {
        $this->valoracionModel->crearValoracion([
            'ID_Cliente'   => $idCliente,
            'ID_Proveedor' => $idProveedor,
            'Puntos'       => $puntos,
            'Comentario'   => $comentario
        ]);
        $this->session->flash('success', '¡Gracias por valorar al proveedor!');
    } catch (\Exception $e) {
        error_log('Error al guardar valoración: ' . $e->getMessage());
        $this->session->flash('error', 'Ocurrió un error al guardar la valoración.');
    }

    // Redirigir al servicio
    return $redirigirAlServicio();
}

public function index()
{
    $pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    $categoria = $_GET['categoria'] ?? null;
    $query = $_GET['query'] ?? null; // Agregar búsqueda por texto

    // Si hay categoría, necesitamos el ID
    $categoriaId = null;
    if ($categoria && $categoria !== 'Ver Todos') {
        $categoriaModel = new \App\Models\Categoria();
        $categoriaId = $categoriaModel->findByName($categoria);
    }

    // Traemos los servicios paginados
    $result = $this->servicioModel->getServicios($pagina, $categoriaId, 10, $query);

    $servicios = $result['data'];
    $totalPaginas = $result['totalPaginas'];
    $totalRegistros = $result['totalRegistros'];

    // Si la página solicitada es mayor que el total, redirigir a la última página
    if ($pagina > $totalPaginas && $totalPaginas > 0) {
        $params = $_GET;
        $params['pagina'] = $totalPaginas;
        $queryString = http_build_query($params);
        header("Location: /buscar?$queryString");
        exit;
    }
   
    return $this->render('servicio/index', [
        'title' => 'Buscar servicios',
        'servicios' => $servicios,
        'totalPaginas' => $totalPaginas,
        'totalRegistros' => $totalRegistros,
        'pagina' => $pagina,
        'categoria' => $categoria,
        'query' => $query
    ]);
}



}
