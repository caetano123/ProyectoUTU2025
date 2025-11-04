

<body>
    <div class="servicio-container">


       <h1 class="titulo-servicio">
    <?= htmlspecialchars($title) ?>
    
    <?php if (!empty($promedio) && $promedio > 0): ?>
        <span class="promedio-estrellas">
            <?php
                $estrellasLlenas = floor($promedio);
                $mitad = ($promedio - $estrellasLlenas >= 0.5);
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $estrellasLlenas) {
                        echo '<span class="estrella llena">&#9733;</span>';
                    } elseif ($mitad && $i == $estrellasLlenas + 1) {
                        echo '<span class="estrella media">&#9733;</span>';
                    } else {
                        echo '<span class="estrella vacia">&#9734;</span>';
                    }
                }
            ?>
            <span class="promedio-num">(<?= number_format($promedio, 1) ?>)</span>
            <small class="total">[<?= $totalValoraciones ?>]</small>
        </span>
    <?php else: ?>
        <span class="sin-valoraciones">(Sin valoraciones)</span>
    <?php endif; ?>
</h1>


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
                    <?php if ($isOwner || $isAdmin): ?>
            <div class="botones-accion">
                 <a href="/servicio/edit?id=<?= $servicio['ID_Servicio'] ?>" class="btnAceptar">Editar Servicio</a>

                 <form method="POST" action="/buscar/eliminar">
                    <input type="hidden" name="id" value="<?= $servicio['ID_Servicio'] ?>">
                    <button type="submit" class="btnBorrar" 
                    onclick="return confirm('¿Estás seguro que quieres eliminar este servicio?');">Eliminar</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if (!$isOwner): ?>
        <form action="/servicio/valorar" method="POST" class="valoracion-form mt-3">
         <input type="hidden" name="id_proveedor" value="<?= $usuario['ID_Persona'] ?>">
    <input type="hidden" name="id_servicio" value="<?= $servicio['ID_Servicio'] ?>">

       
    <div class="estrellas">
        <input type="radio" id="star5" name="puntos" value="5"><label for="star5" title="5 estrellas">&#9733;</label>
        <input type="radio" id="star4" name="puntos" value="4"><label for="star4" title="4 estrellas">&#9733;</label>
        <input type="radio" id="star3" name="puntos" value="3"><label for="star3" title="3 estrellas">&#9733;</label>
        <input type="radio" id="star2" name="puntos" value="2"><label for="star2" title="2 estrellas">&#9733;</label>
        <input type="radio" id="star1" name="puntos" value="1"><label for="star1" title="1 estrella">&#9733;</label>
    </div>

    <textarea name="comentario" placeholder="Deja un comentario (opcional)" rows="2" class="comentario" maxlength="70"></textarea>

    <button type="submit" class="btn-valorar">Enviar valoración</button>
        </form>
    <?php endif; ?>
    
    <h3>Comentarios</h3>

<?php if (!empty($comentarios)): ?>
    <div class="comentarios">
        <?php foreach ($comentarios as $c): ?>
            <div class="comentario">
                <strong><?= htmlspecialchars($c['usuario'] ?? 'Anonimo') ?></strong>
                <span class="estrellas">
                    <?php
                        $est = $c['estrellas'] ?? 0; 
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $est ? '&#9733;' : '&#9734;';
                        }
                    ?>
                </span>
                <p><?= htmlspecialchars($c['Comentario'] ?? '') ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No hay comentarios todavía.</p>
<?php endif; ?>


    
    
    
        

    </div> </body>
