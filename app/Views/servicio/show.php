<body>
    <div class="servicio-container">

        <h1><?php echo $title; ?></h1>

        <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($servicio['Nombre']); ?>" class="servicio-imagen" />

        <p class="servicio-descripcion">
            <?php echo $servicio['Descripcion']; ?>
        </p>

        <div class="servicio-info-box">
            <p><strong>Precio (por hora trabajada en pesos uruguayos):</strong></p>
            <p class="servicio-precio">$<?= number_format($servicio['Precio'], 2) ?></p>

            <p><strong>Categoría:</strong> <?= $categoria['Nombre']; ?></p>
            <p><strong>Subcategoría:</strong> <?= $subcategoria['Nombre']; ?></p>
            <p><strong>Zona:</strong> <?= $zona['Nombre']; ?></p>
        </div>
        
        
        <form action="/servicio/contactar" method="POST" style="margin-bottom: 25px;">
        
            <input type="hidden" name="id" value="<?= htmlspecialchars($servicio['ID_Servicio']) ?>">

            <button type="submit" class="btn-contratar">
                Contactar al Proveedor
            </button>
        </form>

        <div class="servicio-meta">
            <p>
                <strong>Publicado por:</strong> 
                <a href="/profile?id=<?= $usuario['ID_Persona'] ?>">
                    <?= $usuario['Nombre'] . ' ' . $usuario['Apellido']; ?>
                </a>
            </p>
            <p>
                <strong>Fecha de Publicación:</strong> 
                <?= date('d/m/Y H:i', strtotime($servicio['FechaPublicacion'])); ?>
            </p>
            </div>
                    <?php if ($is_owner): ?>
            <div class="botones-accion">
                 <a href="/servicio/edit?id=<?= $servicio['ID_Servicio'] ?>" class="btnAceptar">Editar Servicio</a>

                 <form method="POST" action="/buscar/eliminar">
                    <input type="hidden" name="id" value="<?= $servicio['ID_Servicio'] ?>">
                    <button type="submit" class="btnBorrar" 
                    onclick="return confirm('¿Estás seguro que quieres eliminar este servicio?');">Eliminar</button>
                </form>
            </div>
        <?php endif; ?>
        

    </div> </body>