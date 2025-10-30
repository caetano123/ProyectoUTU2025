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

        <?php if ($is_owner): ?>
            <div class="botones-accion" style="margin-top: 20px;">
                 <a href="/profile/edit" class="btnAceptar">Editar Perfil</a>
                  <a href="/logout" class="btnBorrar">Cerrar sesión</a>
            </div>
        <?php endif; ?>
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

                             <div class="botones-accion">
                              <a href="/servicio?id=<?= $s['ID_Servicio'] ?>" class="btnAceptar">Ver más</a>
                            </div>
                             <?php if ($is_owner): ?>
                                <form method="POST" action="/buscar/eliminar" style="display: inline-block;">
                                    <input type="hidden" name="id" value="<?= $s['ID_Servicio'] ?>">
                                    <button type="submit" class="btnBorrar" 
                                    onclick="return confirm('¿Estás seguro que quieres eliminar este servicio?');">Eliminar</button>
                                </form>
                            <?php endif; ?>
                         </div>
                    <?php endforeach; ?>
             <?php else: ?>
                 <p>No hay servicios cargados aún.</p>
            <?php endif; ?>
    </div>
</body>