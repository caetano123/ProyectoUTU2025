<?php
namespace App\Core;

class View {
    protected $sharedData = [];

    public function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    public function share($key, $value = null) {
        if (is_array($key)) {
            $this->sharedData = array_merge($this->sharedData, $key);
        } else {
            $this->sharedData[$key] = $value;
        }
    }

    public function component($name, $data = []) {
        // Combina los datos compartidos con los datos específicos del componente.
        $data = array_merge($this->sharedData, $data);
        extract($data); // Hace que las variables de $data estén disponibles en el ámbito del componente.

        // Determina la ruta del archivo del componente.
        if (strpos($name, '/') === false) {
            $componentFile = BASE_PATH . "/Views/components/{$name}.php";
        } else {
            // Permite componentes en subdirectorios como 'minuevo/lista_articulos'
            $componentFile = BASE_PATH . "/Views/{$name}.php";
        }

        // Verifica si el archivo del componente existe.
        if (!file_exists($componentFile)) {
            error_log("Componente no encontrado: {$name}. Ruta esperada: {$componentFile}");
            echo "<div style='color:red;'>Error: Componente '{$name}' no encontrado.</div>";
            return;
        }
        require $componentFile; // Esto imprimirá directamente en el búfer de salida activo (el iniciado por render()).
    }

   public function render($viewPath, $data = []) {
    // Combina datos compartidos con datos específicos de la vista.
    $data = array_merge($this->sharedData, $data);
    extract($data);

    ob_start();
    $viewFile = BASE_PATH . "/Views/{$viewPath}.php";

    if (!file_exists($viewFile)) {
        ob_end_clean();
        throw new \Exception("Vista no encontrada: {$viewPath}. Archivo esperado: {$viewFile}");
    }

    require $viewFile;

    $content = ob_get_clean();

    $layoutFile = BASE_PATH . "/Views/layouts/main.php";

    if (file_exists($layoutFile)) {
        ob_start();
        require $layoutFile;
        echo ob_get_clean();
    } else {
        echo $content;
    }
}
}
