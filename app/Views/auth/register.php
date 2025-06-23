<h2>Registro de Usuario</h2>

<form method="POST" style="max-width: 400px;">
    <div>
        <label>Nombre:</label>
        <input type="text" name="nombre" required value="<?= $this->e($input['nombre'] ?? '') ?>">
    </div>

    <div>
        <label>Apellido:</label>
        <input type="text" name="apellido" required value="<?= $this->e($input['apellido'] ?? '') ?>">
    </div>

    <div>
        <label>Correo:</label>
        <input type="email" name="correo" required value="<?= $this->e($input['correo'] ?? '') ?>">
    </div>

    <div>
        <label>Contraseña:</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label>Confirmar Contraseña:</label>
        <input type="password" name="password_confirm" required>
    </div>

    <button type="submit">Registrarse</button>
</form>

<p>¿Ya tienes una cuenta? <a href="/login">Inicia sesión</a></p>
