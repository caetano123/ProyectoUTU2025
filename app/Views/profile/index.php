<body>

    <div class="Perfil">
        <h1 class="personal">
            <?= htmlspecialchars($title ?? 'Perfil de Usuario de ' . $user['Nombre']) ?>
        </h1>
        <div class="foto-perfil">
            <img src="profile-picture.jpg" alt="Profile Picture" class="foto-perfil-img">
        </div>

        <div class="info">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($user['Nombre']) ?></p>
            <p><strong>Apellido:</strong> <?= htmlspecialchars($user['Apellido']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($user['Telefono']) ?></p>
            <p><strong>Correo Electrónico:</strong> <?= htmlspecialchars($user['Correo']) ?></p>
        </div>

    <div class="acciones-perfil" style="margin-top: 20px;">
        <a href="profile/edit" class="boton-editar">Editar Perfil</a>
    </div>

    </div>
    <h3>Servicios</h3>
    <div class="servicios">
        <ul>
            <?php foreach ($servicios as $servicio): ?>
                <li><?= htmlspecialchars($servicio['NombreServicio']) ?> - <?= htmlspecialchars($servicio['Descripcion']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>