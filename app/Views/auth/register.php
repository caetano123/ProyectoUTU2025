<div class="login-wrapper">
  <div class="login-bg"></div>

  <div class="login-box">
    <h2>Crear Cuenta:</h2>

    <?php if (isset($errors['general'])): ?>
        <p class="alert alert-error"><?= $errors['general'] ?></p>
    <?php endif; ?>
   
    <?php if (isset($flash['error'])): ?>
        <p class="alert alert-error"><?= $flash['error'] ?></p>
    <?php endif; ?>

    <form method="POST" class="login-form">

      <!-- CAMPO NOMBRE -->
      <div class="form-group">
        <input type="text" name="nombre" required placeholder="Nombre" value="<?= $this->e($input['nombre'] ?? '') ?>">
        <?php if (isset($errors['nombre'])): ?>
            <p class="alert alert-error"><?= $errors['nombre'] ?></p>
        <?php endif; ?>
      </div>

      <!-- CAMPO APELLIDO -->
      <div class="form-group">
        <input type="text" name="apellido" required placeholder="Apellido" value="<?= $this->e($input['apellido'] ?? '') ?>">
        <?php if (isset($errors['apellido'])): ?>
            <p class="alert alert-error"><?= $errors['apellido'] ?></p>
        <?php endif; ?>
      </div>

      <!-- CAMPO TELÉFONO -->
      <div class="form-group">
        <input type="text" name="telefono" required placeholder="Número de Teléfono" value="<?= $this->e($input['telefono'] ?? '') ?>">
        <?php if (isset($errors['telefono'])): ?>
            <p class="alert alert-error"><?= $errors['telefono'] ?></p>
        <?php endif; ?>
      </div>

      <!-- CAMPO CORREO -->
      <div class="form-group">
        <input type="email" name="correo" required placeholder="Correo Electrónico" value="<?= $this->e($input['correo'] ?? '') ?>">
        <?php if (isset($errors['correo'])): ?>
            <p class="alert alert-error"><?= $errors['correo'] ?></p>
        <?php endif; ?>
      </div>

      <!-- CAMPO CONTRASEÑA -->
      <div class="form-group">
        <input type="password" name="password" required placeholder="Contraseña">
        <?php if (isset($errors['password'])): ?>
            <p class="alert alert-error"><?= $errors['password'] ?></p>
        <?php endif; ?>
      </div>

      <!-- CAMPO CONFIRMAR CONTRASEÑA -->
      <div class="form-group">
        <input type="password" name="password_confirm" required placeholder="Confirmar Contraseña">
        <?php if (isset($errors['password_confirm'])): ?>
            <p class="alert alert-error"><?= $errors['password_confirm'] ?></p>
        <?php endif; ?>
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
