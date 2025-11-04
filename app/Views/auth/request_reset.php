<h1><?= $title ?></h1>

<?php if(!empty($errors)): ?>
    <ul>
        <?php foreach($errors as $field => $msg): ?>
            <li><?= $msg ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if(!empty($success)): ?>
    <p><?= $success ?></p>
<?php endif; ?>

<form method="POST" action="/recuperar_contraseña">
    <label for="email">Ingrese su Correo aqui:</label>
    <input type="text" placeholder="Ej:SeleLopez@gmail" name="email" id="email" required>
    <button type="submit">Enviar</button>
</form>
