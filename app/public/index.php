<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Cargar variables de entorno

    require_once __DIR__ . '/../../vendor/autoload.php';
    $env = parse_ini_file(__DIR__ . '/../../.env');

if ($env === false) {
    die('<h1 style="color: red;">ERROR: No se encontró el archivo .env</h1>');
}

// Mostrar información básica
echo '<h1>Plataforma de Servicios Locales</h1>';
echo '<h2>Configuración del Entorno</h2>';
echo '<p>Entorno: <strong>'.htmlspecialchars($env['APP_ENV'] ?? 'No definido').'</strong></p>';

// Conexión a la base de datos
try {
    $dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=utf8',
$env['DB_HOST'] ?? 'db',
        $env['DB_NAME'] ?? 'servicios_local'
    );
    
    $db = new PDO(
        $dsn,
        $env['DB_USER'] ?? 'servicios_user',
        $env['DB_PASSWORD'] ?? 'password_segura',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo '<div style="background: #dfd; padding: 1em; border: 1px solid #8f8;">';
    echo '<h3>✅ Conexión a la base de datos exitosa</h3>';
    
    // Mostrar información de la base de datos
    $stmt = $db->query('SELECT VERSION() as version');
    $mysqlVersion = $stmt->fetch();
    echo '<p>Versión MySQL/MariaDB: <strong>'.htmlspecialchars($mysqlVersion['version']).'</strong></p>';
    
    echo '</div>';
    
} catch (PDOException $e) {
    echo '<div style="background: #fdd; padding: 1em; border: 1px solid #f88;">';
    echo '<h3>❌ Error de conexión a la base de datos</h3>';
    echo '<p><strong>Error:</strong> '.htmlspecialchars($e->getMessage()).'</p>';
    echo '<p>Verifica tu configuración en el archivo <code>.env</code>:</p>';
    echo '<pre>'.htmlspecialchars(print_r($env, true)).'</pre>';
    echo '</div>';
}

// Mostrar información del servidor
echo '<h2>Información del Servidor</h2>';
echo '<p>PHP Version: <strong>'.phpversion().'</strong></p>';
echo '<p>Servidor Web: <strong>'.$_SERVER['SERVER_SOFTWARE'].'</strong></p>';
echo '<p>Sistema Operativo: <strong>'.php_uname().'</strong></p>';

// Mostrar variables de entorno relevantes
echo '<h2>Variables de Entorno</h2>';
echo '<table border="1" cellpadding="5">';
echo '<tr><th>Variable</th><th>Valor</th></tr>';

$relevantVars = ['APP_ENV', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PORT'];
foreach ($relevantVars as $var) {
    echo '<tr>';
    echo '<td>'.$var.'</td>';
    echo '<td>'.htmlspecialchars($env[$var] ?? '(no definida)').'</td>';
    echo '</tr>';
}
echo '</table>';