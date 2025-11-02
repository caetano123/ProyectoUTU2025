<?php

namespace App\Controllers\Security;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

     //Formulario de inicio de sesión
    public function showLogin()
    {
        if ($this->auth->check()) {
            return $this->redirect('/dashboard');
        }

        return $this->render('auth/login', [
            'title' => 'Iniciar Sesión'
        ]);
    }

    // Procesa la solicitud de inicio de sesión.
    public function login()
    {
        // Si la petición NO es POST, redirigir a showLogin
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/login');
        }

        // Procesar login
        $email = $this->input('email');
        $password = $this->input('password');

        $errors = $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!empty($errors)) {
            
            $this->session->flash('errors', $errors);
            $this->session->flash('old', ['email' => $email]);
            
            return $this->redirect('/login');
        }

        // Intentar autenticación
        if ($this->auth->attempt($email, $password)) {
            $this->session->flash('success', 'Has iniciado sesión correctamente');
            error_log('Usuario autenticado: ' . $email);
            return $this->redirect('/profile');
        }

        // Fallo de autenticación
        $errors = ['email' => 'Credenciales incorrectas o usuario no encontrado.'];
        
        $this->session->flash('errors', $errors);
        $this->session->flash('old', ['email' => $email]);
        $this->session->flash('error', 'Credenciales incorrectas'); // Mensaje genérico de flash

        return $this->redirect('/login');
    }

    /**
     * Muestra el formulario de registro.
     */
    public function showRegister()
    {
        if ($this->auth->check()) {
            return $this->redirect('/profile');
        }

        return $this->render('auth/register', [
            'title' => 'Registro de Usuario',
          
            'errors' => $this->session->flash('errors') ?? [],
            'old' => $this->session->flash('old') ?? [],
            'success' => $this->session->flash('success'),
            'error' => $this->session->flash('error')
        ]);
    }

    // Procesa la solicitud de registro de un nuevo usuario.
    public function register()
    {
        // Solo procesar si es POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/register');
        }

        $nombre = $this->input('nombre');
        $apellido = $this->input('apellido');
        $correo = $this->input('correo');
        $telefono = $this->input('telefono');
        $password = $this->input('password');
        $passwordConfirm = $this->input('password_confirm');

        // Definición de reglas de validación
        $rules = [
            'nombre' => 'required|min:3',
            'apellido' => 'required|min:2',
            'correo' => 'required|email',
            'telefono' => 'required|min:8', 
            'password' => 'required|min:4',
            'password_confirm' => 'required'
        ];

        $errors = $this->validate($rules);
        $input = compact('nombre', 'apellido', 'correo', 'telefono');

        // Validación de unicidad de correo. Solo si no hay errores previos en el campo 'correo'.
        if (!isset($errors['correo']) && $this->userModel->findByEmail($correo)) {
            $errors['correo'] = 'Este correo ya está registrado';
        }
        
        // Validación de confirmación de contraseña
        if ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Las contraseñas no coinciden';
        }


        if (!empty($errors)) {
            $this->session->flash('errors', $errors);
            $this->session->flash('old', $input);
            return $this->redirect('/register');
        }

        // Si no hay errores, se puede crear el usuario
        $data = [
            'Nombre' => $nombre,
            'Apellido' => $apellido,
            'Correo' => $correo,
            'Telefono' => $telefono,
            'Tipo' => 'USUARIO', // Asignar un rol por default 
            'ContrasenaHash' => password_hash($password, PASSWORD_BCRYPT) // Almacenamiento seguro
        ];

        $userId = $this->userModel->create($data);

        if ($userId) {
            $user = $this->userModel->findByEmail($correo);
            
            // Auto-login después del registro
            if ($user && $this->auth->login($user)) {
                $this->session->flash('success', 'Te has registrado correctamente. Bienvenido/a.');
                return $this->redirect('/profile');
            }
        }
        
        // Manejo de error si la inserción o el login automático fallan
        error_log('Fallo crítico al crear o autenticar el nuevo usuario: ' . $correo);
        $errors['general'] = 'Error interno al registrar el usuario. Inténtalo de nuevo.';
        
        $this->session->flash('errors', $errors);
        $this->session->flash('old', $input);
        $this->session->flash('error', 'Error al registrar el usuario');

        return $this->redirect('/register');
    }

    // Cierra la sesión del usuario.
    public function logout()
    {
        $this->auth->logout();
        $this->session->flash('success', 'Has cerrado sesión correctamente');
        return $this->redirect('/');
    }

    /**
     * Helper para verificar si un campo tiene un error en el array de errores.
     */
    private function inputHasError(string $field, array $errors): bool
    {
        return isset($errors[$field]);
    }
}
