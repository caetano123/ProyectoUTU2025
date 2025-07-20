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

    public function showLogin()
    {
        if ($this->auth->check()) {
            return $this->redirect('/dashboard');
        }

        return $this->render('auth/login', [
            'title' => 'Iniciar Sesión'
        ]);
    }

    public function login()
{
    // Si la petición NO es POST, mostrar el formulario directamente
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $this->render('auth/login', [
            'title' => 'Iniciar Sesión'
        ]);
    }

    // Si es POST, procesar login
    $email = $this->input('email');
    $password = $this->input('password');

    $errors = $this->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (!empty($errors)) {
        return $this->render('auth/login', [
            'title' => 'Iniciar Sesión',
            'errors' => $errors,
            'input' => ['email' => $email]
        ]);
    }

    $user = $this->userModel->findByEmail($email);

    if ($user && password_verify($password, $user['ContrasenaHash'])) {
        $this->auth->login($user); // Guarda usuario en sesión
        $this->session->flash('success', 'Has iniciado sesión correctamente');
        error_log('Usuario autenticado: ' . $email);
        return $this->redirect('/dashboard');
    }

    return $this->render('auth/login', [
    'title' => 'Iniciar Sesión',
    'input' => ['email' => $email],
    'flash' => ['error' => 'Credenciales incorrectas']
]);

}


    public function showRegister()
    {
        if ($this->auth->check()) {
            return $this->redirect('/dashboard');
        }

        return $this->render('auth/register', [
            'title' => 'Registro de Usuario'
        ]);
    }

   public function register()
{
    $nombre = $this->input('nombre');
    $apellido = $this->input('apellido');
    $correo = $this->input('correo');
    $password = $this->input('password');
    $passwordConfirm = $this->input('password_confirm');

    $errors = $this->validate([
        'nombre' => 'required|min:3',
        'apellido' => 'required|min:2',
        'correo' => 'required|email',
        'password' => 'required|min:4'
    ]);

    if ($password !== $passwordConfirm) {
        $errors['password_confirm'] = 'Las contraseñas no coinciden';
    }

    if ($this->userModel->findByEmail($correo)) {
        $errors['correo'] = 'Este email ya está registrado';
    }

    if (!empty($errors)) {
        return $this->render('auth/register', [
            'title' => 'Registro de Usuario',
            'errors' => $errors,
            'input' => [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'correo' => $correo,
            ]
        ]);
    }

    $userId = $this->userModel->create([
        'Nombre' => $nombre,
        'Apellido' => $apellido,
        'Correo' => $correo,
        'ContrasenaHash' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    if ($userId) {
        // Aquí podrías llamar directamente a login para evitar duplicar código
        $this->auth->login($this->userModel->findByEmail($correo));
        $this->session->flash('success', 'Te has registrado correctamente');
        return $this->redirect('/dashboard');
    }

    $this->session->flash('error', 'Error al registrar el usuario');
    return $this->render('auth/register', [
        'title' => 'Registro de Usuario',
        'input' => [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
        ]
    ]);
}

    public function logout()
    {
        $this->auth->logout();
        $this->session->flash('success', 'Has cerrado sesión correctamente');
        return $this->redirect('/');
    }
}
