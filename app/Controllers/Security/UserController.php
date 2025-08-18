<?php
namespace App\Controllers\Security;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;

class UserController extends Controller
{
    protected $userModel;
    protected $view;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->view = new View();
    }

    private function checkAuth()
    {
        if (!$this->auth->check()) {
            return $this->redirect('/login');
        }
    }

    public function index()
    {
        $this->checkAuth();

        $usuarios = $this->userModel->all();
        return $this->view->render("auth/index", [
            "titulo" => "Listado de usuarios",
            "datos" => $usuarios
        ]);
    }

    public function show($params)
    {
        $this->checkAuth();

        $id = $params['id'] ?? null;
        if (!$id) {
            return $this->redirect('/usuarios');
        }

        $usuario = $this->userModel->findById($id);
        if (!$usuario) {
            return $this->redirect('/usuarios');
        }

        return $this->view->render("auth/show", [
            "titulo" => "Usuario ID $id",
            "datos" => [$usuario]
        ]);
    }

    public function edit($params)
    {
        $this->checkAuth();

        $id = $params['id'] ?? null;
        $usuario = $this->userModel->findById($id);
        if (!$usuario) {
            return $this->redirect('/usuarios');
        }

        return $this->view->render("auth/edit", [
            "titulo" => "Editar usuario ID $id",
            "datos" => $usuario
        ]);
    }

    public function save($params)
    {
        $this->checkAuth();

        $id = $params['id'] ?? null;
        if (!$id) {
            return $this->redirect('/usuarios');
        }

        $data = [
            'Nombre' => $_POST['nombre'] ?? '',
            'Apellido' => $_POST['apellido'] ?? '',
            'Correo' => $_POST['correo'] ?? '',
            'Verificado' => isset($_POST['verificado']) ? 1 : 0
        ];

        $updated = $this->userModel->update($id, $data);

        $mensaje = $updated ? "Se actualizó correctamente." : "No se pudo actualizar.";
        return $this->view->render("auth/save", [
            "titulo" => "Actualizar usuario",
            "datos" => [$mensaje]
        ]);
    }
    
    public function paginado(){
        $datos = $this->userModel->sqlPaginado();
        $datos["baseUrl"] = "/usuarios/pagina";
        
        return $this->render( "usuarios/paginar", $datos );

    }
}

