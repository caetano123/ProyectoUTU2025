<?php
namespace App\Core;

use App\Models\User;

/**
 * Clase Auth - Maneja la autenticación de usuarios
 * 
 * Esta clase se encarga de:
 * - Autenticar usuarios contra la base de datos
 * - Mantener el estado de sesión del usuario
 * - Proporcionar métodos para verificar la autenticación
 */
class Auth {
    /**
     * @var Session Instancia de la clase Session
     */
    protected $session;
    
    /**
     * @var User Instancia del modelo User
     */
    protected $userModel;

    /**
     * Constructor
     * 
     * @param Session $session
     * @param User $userModel
     */
    public function __construct(Session $session, User $userModel) {
        $this->session = $session;
        $this->userModel = $userModel;
    }

    /**
     * Intenta autenticar al usuario
     * 
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function attempt($email, $password) {
        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user["ContrasenaHash"])) {
            unset($user["ContrasenaHash"]); // Seguridad: no guardar la contraseña
            $this->session->set("user", $user);
            return true;
        }

        return false;
    }

    /**
     * Inicia sesión con un usuario dado (ya autenticado)
     * 
     * @param array $user
     * @return void
     */
    public function login(array $user) {
        unset($user["ContrasenaHash"]); // Seguridad
        $this->session->set("user", $user);
    }

    /**
     * Registra un nuevo usuario
     * 
     * @param array $userData
     * @return int|bool
     */
    public function register($userData) {
        // Encriptar la contraseña
        $userData["ContrasenaHash"] = password_hash($userData["ContrasenaHash"], PASSWORD_DEFAULT);

        // Crear el usuario en la base de datos
        return $this->userModel->create($userData);
    }

    /**
     * Obtiene el usuario autenticado
     * 
     * @return array|null
     */
    public function user() {
        return $this->session->get("user");
    }

    /**
     * Verifica si hay un usuario autenticado
     * 
     * @return bool
     */
    public function check() {
        return $this->session->has("user");
    }

    /**
     * Cierra la sesión del usuario autenticado
     * 
     * @return void
     */
    public function logout() {
        $this->session->remove("user");
    }
}
