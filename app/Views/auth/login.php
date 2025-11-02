<div class="login-wrapper">

  <div class="login-bg"></div>
  
  <div class="login-box">
    <h2>Inicio de Sesión</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (!empty($flash['error'])): ?>
      <div class="alert alert-error"><?= htmlspecialchars($flash['error']) ?></div>
    <?php endif; ?>

    <?php if (!empty($flash['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($flash['success']) ?></div>
    <?php endif; ?>

    <form method="POST" action="/login" class="login-form">
      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          placeholder="Ej: admin@example.com" 
          required
          value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
        >
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          placeholder="Tu contraseña" 
          required
        >
      </div>

      <button type="submit" class="btn-cta">Iniciar Sesión</button>

      <p class="olvidaste">
        <a href="/recuperar_contraseña.php">¿Olvidaste tu contraseña?</a>
      </p>
    </form>
  </div>
</div>
