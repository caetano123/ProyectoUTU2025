
    <h2>Iniciar sesión</h2>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($flash['error'])): ?>
    <div class="flash-error"><?= htmlspecialchars($flash['error']) ?></div>
<?php endif; ?>

<?php if (!empty($flash['success'])): ?>
    <div class="flash-success"><?= htmlspecialchars($flash['success']) ?></div>
<?php endif; ?>



    <form method="POST" action="/login" style="max-width: 300px;">
        <div>
            <label>Email:</label>
            <input type="email" name="email" required value="admin@example.com">
            <small>Usar: admin@example.com</small>
        </div>
        
        <div>
            <label>Contraseña:</label>
            <input type="password" name="password" required value="1234">
            <small>Usar: 1234</small>
        </div>
        
        <button type="submit">Ingresar</button>
    </form>

