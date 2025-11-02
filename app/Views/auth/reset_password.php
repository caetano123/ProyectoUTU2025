<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<div class="login-wrapper">
  <div class="login-bg"></div>

  <div class="login-box">
    <h2>Restablecer Contraseña</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="/recuperar_contraseña/<?= htmlspecialchars($token ?? '') ?>" class="login-form">
  <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">


      <div class="form-group">
        <label for="password">Nueva contraseña</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="password_confirm">Confirmar contraseña</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
      </div>

      <button type="submit" class="btn-cta">Guardar nueva contraseña</button>
    </form>
  </div>
</div>
