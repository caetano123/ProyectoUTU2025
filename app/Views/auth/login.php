<div class="login-wrapper">
    <div class="login-box">

        <h2>Inicio de Sesion:</h2>

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
              
                <input type="email" name="email" required="admin@example.com" placeholder="Email">
                
                
            </div>
            
            <div class="form-group">
                
                <input type="password" name="password" required="1234" placeholder="Contraseña">
                
            </div>
            
            <button type="submit" class="btn-cta">Iniciar Sesión</button>

                <p class="olvidaste"><a href="/recuperar_contraseña.php">¿Olvidaste tu contraseña?</a></p>

        </form>
    </div>
</div>
