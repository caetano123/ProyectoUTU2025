<body>

    <div class="Perfil">
        <h1 class="personal">
            <?= htmlspecialchars($title ?? 'Perfil de Usuario de ' . $user['Nombre']) ?>
        </h1>
        <div class="foto-perfil">
            <img src="https://i.pinimg.com/736x/87/22/ec/8722ec261ddc86a44e7feb3b46836c10.jpg" alt="Foto de Perfil" class="foto-perfil-img">
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
    <br>
    <h3>Servicios</h3>
 <div class="servicios-grid">
    <?php if(!empty($servicios)): ?>
        <?php foreach($servicios as $s): ?>
        <div class="servicio-card">
            <h3><?= htmlspecialchars($s['Nombre']) ?></h3>
            <p><?= htmlspecialchars($s['Descripcion']) ?></p>
            <p class="precio">$<?= number_format($s['Precio'],2) ?></p>

           
            <a href="/buscar/eliminar?id=<?= $s['ID_Servicio'] ?>" 
               class="btn-eliminar" 
               onclick="return confirm('¿Estás seguro que quieres eliminar este servicio?');">
               Eliminar
            </a>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay servicios cargados aún.</p>
    <?php endif; ?>
</div>
</body>