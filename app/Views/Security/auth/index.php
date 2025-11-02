<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo ?? 'Usuarios') ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($titulo ?? 'Usuarios') ?></h1>

    <!-- Botón cerrar sesión -->
    <form action="/logout" method="POST" style="margin-bottom: 20px;">
        <button type="submit">Cerrar sesión</button>
    </form>

    <?php if (!empty($datos)): ?>
        <ul>
            <?php foreach ($datos as $usuario): ?>
                <li>
                    <?= htmlspecialchars($usuario['Nombre']) . ' ' . htmlspecialchars($usuario['Apellido']) ?> 
                    (CI: <?= htmlspecialchars($usuario['CI']) ?>, Email: <?= htmlspecialchars($usuario['Email']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay usuarios para mostrar.</p>
    <?php endif; ?>

</body>
</html>
