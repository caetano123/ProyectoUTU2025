<div class="register-wrapper">
    <div class="register-box">
        <h2>Crear Cuenta: </h2>

        <form method="POST" class="login-form">
            <div>
                <input type="text" name="nombre" required placeholder="Nombre" value="<?= $this->e($input['nombre'] ?? '') ?>">
            </div>

            <div>
                <input type="text" name="apellido" required placeholder="Apellido" value="<?= $this->e($input['apellido'] ?? '') ?>">
            </div>

            <div>
                <input type="text" name="telefono" required placeholder="Número de Teléfono" value="<?= $this->e($input['telefono'] ?? '') ?>">
            </div>

            <div>
                <input type="email" name="correo" required placeholder="Correo Electronico" value="<?= $this->e($input['correo'] ?? '') ?>">
            </div>

            <div>
                <input type="password" name="password" required placeholder="Contraseña">
            </div>

            <div>
                <input type="password" name="password_confirm" required placeholder="Confirmar Contraseña">
            </div>

            <button type="submit" class="btn-cta">Registrar</button>

           

        </form>

        

        <p class="olvidaste">
            ¿Ya tienes una cuenta? <a href="/login">Inicia sesión</a>
        </p>
    </div>
</div>
