<body>
    <div class="servicio-container">

        <h1><?php echo $title; ?></h1>

        <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($servicio['Nombre']); ?>" class="servicio-imagen" />

        <p class="servicio-descripcion">
            <?php echo $servicio['Descripcion']; ?>
        </p>

        <div class="servicio-info-box">
            <p class="servicio-precio">$<?= number_format($servicio['Precio'], 2) ?></p>

            <p><strong>Categoría:</strong> <?= $categoria['Nombre']; ?></p>
            <p><strong>Subcategoría:</strong> <?= $subcategoria['Nombre']; ?></p>
            <p><strong>Zona:</strong> <?= $zona['Nombre']; ?></p>
        </div>

        <a href="/contactar?id=<?= $servicio['ID_Servicio'] ?>" class="btn-contratar">
            Contactar para Contratar
        </a>

        <div class="servicio-meta">
            <p>
                <strong>Publicado por:</strong> 
                <a href="/profile?id=<?= $id_usuario ?>">
                    <?= $usuario['Nombre'] . ' ' . $usuario['Apellido']; ?>
                </a>
            </p>
            <p>
                <strong>Fecha de Publicación:</strong> 
                <?= date('d/m/Y H:i', strtotime($servicio['FechaPublicacion'])); ?>
            </p>

app/Controllers/Servicios/ServicioController.php
app/Models/Servicio.php
app/Views/buscar.php
app/Views/profile/index.php
app/Views/profile/update.php
app/Views/servicio/show.php
app/Views/vender.php
app/public/css/style.css

    config/database.php

</div>

    </div> </body>
