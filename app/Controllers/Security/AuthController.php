<?php

namespace App\Controllers\Security;

use App\Core\Controller;
use App\Models\User;
use App\Helpers\Mailer; 

class AuthController extends Controller
{
    protected $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

     //Formulario de inicio de sesión
    public function showLogin()
    {
        if ($this->auth->check()) {
            return $this->redirect('/profile');
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


     public function showRequestReset()
    {
        return $this->render('auth/request_reset', [
            'title' => 'Recuperar Contraseña',
            'errors' => $this->session->flash('errors') ?? [],
            'success' => $this->session->flash('success'),
        ]);
    }

    // Procesar solicitud de recuperación de contraseña
    public function requestReset()
    {
        $emailOrCI = $this->input('email') ?? '';

        if (!$emailOrCI) {
            $this->session->flash('errors', ['email' => 'Ingresa tu correo o CI.']);
            return $this->redirect('/recuperar_contraseña');
        }

        $user = $this->userModel->getByEmailOrCI($emailOrCI);

        if ($user) {
            // Generar token seguro
            $token = bin2hex(random_bytes(24));
            $token_hash = hash('sha256', $token);
            $expires = (new \DateTime('+1 hour'))->format('Y-m-d H:i:s');

            // Guardar token en DB
           $this->userModel->savePasswordReset($user['ID_Persona'], $token_hash, $expires);

            // Enviar correo
            $mailer = new Mailer();
            $link = "http://localhost:81/recuperar_contraseña/" . urlencode($token);
            $mailer->send(
                $user['Correo'], 
                $user['Nombre'], 
                'Recuperar contraseña - ServiciOs',
                "Hola {$user['Nombre']}, haz clic en este enlace para cambiar tu contraseña: <a href='{$link}'>Cambiar contraseña</a>"
            );
        }

        // Mensaje genérico para evitar filtrado de usuarios
        $this->session->flash('success', 'Si ese usuario existe, recibirás un email con instrucciones.');
        return $this->redirect('/recuperar_contraseña');
    }

    // Mostrar formulario para cambiar la contraseña usando token
 public function showResetPassword($token)
{
    // Si el router pasa un array
    if (is_array($token)) {
        $token = $token['token'] ?? '';
    }

    return $this->render('auth/reset_password', [
        'token' => $token,
        'errors' => $this->session->flash('errors') ?? [],
        'success' => $this->session->flash('success'),
    ]);
}


    // Procesar cambio de contraseña
    public function resetPassword()
    {
        $token = $this->input('token') ?? '';
        $password = $this->input('password') ?? '';
        $passwordConfirm = $this->input('password_confirm') ?? '';

        if (!$token || !$password || !$passwordConfirm) {
            $this->session->flash('errors', ['general' => 'Datos incompletos']);
            return $this->redirectBack();
        }

        if ($password !== $passwordConfirm) {
            $this->session->flash('errors', ['password_confirm' => 'Las contraseñas no coinciden']);
            return $this->redirectBack();
        }

        $token_hash = hash('sha256', $token);
        $reset = $this->userModel->getPasswordReset($token_hash);

        if (!$reset || $reset['used'] || new \DateTime() > new \DateTime($reset['expires_at'])) {
            $this->session->flash('errors', ['general' => 'Token inválido o expirado']);
            return $this->redirectBack();
        }

        // Actualizar contraseña del usuario
        $newHash = password_hash($password, PASSWORD_BCRYPT);
        $this->userModel->updatePassword($reset['CI'], $newHash);

        // Marcar token como usado
        $this->userModel->markTokenUsed($reset['id']);

        $this->session->flash('success', 'Contraseña cambiada correctamente. Ahora podés iniciar sesión.');
        return $this->redirect('/login');
    }
}
