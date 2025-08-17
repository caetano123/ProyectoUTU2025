<div class="login-wrapper">
  <div class="login-bg"></div>

  <div class="login-box">
    <h2>Crear Cuenta:</h2>

    <form method="POST" class="login-form">

      <div class="form-group">
        <input type="text" name="nombre" required placeholder="Nombre" value="<?= $this->e($input['nombre'] ?? '') ?>">
      </div>

      <div class="form-group">
        <input type="text" name="apellido" required placeholder="Apellido" value="<?= $this->e($input['apellido'] ?? '') ?>">
      </div>

      <div class="form-group">
        <input type="text" name="telefono" required placeholder="Número de Teléfono" value="<?= $this->e($input['telefono'] ?? '') ?>">
      </div>

      <div class="form-group">
        <input type="email" name="correo" required placeholder="Correo Electrónico" value="<?= $this->e($input['correo'] ?? '') ?>">
      </div>

      <div class="form-group">
        <input type="password" name="password" required placeholder="Contraseña">
      </div>

      <div class="form-group">
        <input type="password" name="password_confirm" required placeholder="Confirmar Contraseña">
      </div>

     <p class="olvidaste">
    Al registrarte, aceptas nuestros
   <a href="<?= BASE_URL ?>/terminos" target="_blank">Términos y Condiciones</a>.
    </p>

      <button type="submit" class="btn-cta">Registrar</button>
    </form>

    <p class="olvidaste">
      ¿Ya tienes una cuenta? <a href="/login">Inicia sesión</a>
    </p>
  </div>
</div>
