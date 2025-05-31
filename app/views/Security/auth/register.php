<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h2>Registro</h2>
    <?php if (!empty($errors)) : ?>
        <ul style="color:red;">
            <?php foreach ($errors as $e) : ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/register">
        <input type="text" name="ci" placeholder="CI" value="<?= htmlspecialchars($_POST['ci'] ?? '') ?>" required><br><br>
        <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required><br><br>
        <input type="text" name="apellido" placeholder="Apellido" value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>" required><br><br>
        <input type="email" name="email" placeholder="Correo electrónico" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required><br><br>
        <input type="text" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"><br><br>
        <input type="text" name="direccion" placeholder="Dirección" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>"><br><br>
        <input type="password" name="password" placeholder="Contraseña" required><br><br>
        <input type="password" name="password_confirm" placeholder="Confirmar contraseña" required><br><br>
        <select name="tipo" required>
            <option value="">Seleccione tipo</option>
            <option value="Cliente" <?= (($_POST['tipo'] ?? '') === 'Cliente') ? 'selected' : '' ?>>Cliente</option>
            <option value="Proveedor" <?= (($_POST['tipo'] ?? '') === 'Proveedor') ? 'selected' : '' ?>>Proveedor</option>
            <option value="Ambos" <?= (($_POST['tipo'] ?? '') === 'Ambos') ? 'selected' : '' ?>>Ambos</option>
        </select><br><br>
        <button type="submit">Registrar</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="/login">Inicia sesión</a></p>
</body>
</html>
