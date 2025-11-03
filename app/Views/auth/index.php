<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title><?= $titulo ?></title>
</head>
<body>
    <h1><?= $titulo ?></h1>

    <a href="/logout">Cerrar sesión</a>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario->id) ?></td>
                <td><?= htmlspecialchars($usuario->Nombre) ?></td>
                <td><?= htmlspecialchars($usuario->Apellido) ?></td>
                <td><?= htmlspecialchars($usuario->Correo) ?></td>
                <td>
                    <a href="/usuarios/show/<?= $usuario->id ?>">Ver</a> |
                    <a href="/usuarios/edit/<?= $usuario->id ?>">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
