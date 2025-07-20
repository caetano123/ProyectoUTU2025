<?php
namespace App\Core;

/**
 * Clase Router - Maneja el enrutamiento y la ejecución de controladores
 * 
 * Esta clase se encarga de:
 * - Registrar rutas
 * - Comparar la URL actual con las rutas registradas
 * - Ejecutar los middleware asociados a la ruta
 * - Llamar al controlador y método correspondiente
 */
class Router {
    /**
     * @var array Colección de rutas registradas
     */
    protected $routes = [];
    
    /**
     * @var array Parámetros extraídos de la URL
     */
    protected $params = [];
    
    /**
     * @var View Instancia de la clase View
     */
    protected $view;
    
    /**
     * @var array Datos de la petición actual
     */
    protected $request;
    
    /**
     * Constructor
     * 
     * @param View $view Instancia de la clase View
     * @param array $request Datos de la petición actual
     */
    public function __construct(View $view, array $request) {
        $this->view = $view;
        $this->request = $request;
    }

    /**
     * Registra una nueva ruta
     * 
     * @param string $method Método HTTP (GET, POST, etc.)
     * @param string $uri Patrón de URL
     * @param mixed $handler Controlador@método o callable
     * @param array $middleware Lista de clases middleware
     * @return void
     */
    public function addRoute($method, $uri, $handler, $middleware = []) {
        $uri = trim($uri, "/");
        $uri = $uri ?: "home";
        
        // Convierte parámetros tipo :id a grupos con nombre en regex
        $pattern = preg_replace("~/:([a-zA-Z]+)~", "/(?P<\\1>[^/]+)", $uri);
        
        $this->routes[] = [
            "method" => strtoupper($method),
            "pattern" => "~^" . $pattern . "$~",
            "handler" => $handler,
            "middleware" => $middleware
        ];
    }

    /**
     * Procesa la solicitud actual y ejecuta el controlador correspondiente
     * 
     * @return mixed Resultado de la ejecución del controlador
     */
    public function dispatch() {
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route)) {
                // Ejecutar middleware si existen
                foreach ($route["middleware"] as $middlewareClass) {
                    (new $middlewareClass())->handle();
                }

                // Ejecutar controlador
                return $this->callHandler($route["handler"]);
            }
        }

        // Ruta no encontrada
        http_response_code(404);
        return $this->view->render("errors/404", [
            "title" => "Página no encontrada"
        ]);
    }

    /**
     * Verifica si la ruta coincide con la solicitud actual
     * 
     * @param array $route Ruta a verificar
     * @return bool True si coincide, false si no
     */
    protected function matchRoute($route) {
        if ($route["method"] !== $this->request["method"]) {
            return false;
        }

        if (preg_match($route["pattern"], $this->request["uri"], $matches)) {
            // Guardar solo parámetros nombrados
            $this->params = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }

    /**
     * Ejecuta el controlador asociado a la ruta
     * 
     * @param mixed $handler Controlador@método o callable
     * @return mixed Resultado de la ejecución
     * @throws \Exception Si el controlador o método no existen
     */
    protected function callHandler($handler) {
        if (is_callable($handler)) {
            return call_user_func($handler, $this->params);
        }

        if (is_string($handler)) {
            list($controller, $method) = explode("@", $handler);

            // Si ya incluye subnamespace, no lo tocamos
            $controllerClass = str_contains($controller, '\\') 
                ? "App\\Controllers\\" . $controller
                : "App\\Controllers\\" . $controller;

            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();

                if (method_exists($controllerInstance, $method)) {
                    return call_user_func_array([$controllerInstance, $method], [$this->params]);
                }

                throw new \Exception("Método '{$method}' no encontrado en el controlador '{$controllerClass}'");
            }

            throw new \Exception("Controlador '{$controllerClass}' no encontrado");
        }

        throw new \Exception("Manejador de ruta no válido");
    }

    /**
     * Redirige a una URL específica
     * 
     * @param string $url URL de destino
     * @return void
     */
    public function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}
