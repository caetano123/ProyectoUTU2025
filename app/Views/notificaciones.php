<body>
    <div class="container-general">
        <h1><?= htmlspecialchars($title) ?></h1>
        <p>Historial completo de tus notificaciones.</p>

        <div class="notificaciones-listado">
            
            <?php if (empty($notificaciones)): ?>
                
                <div class="notificacion-vacia">
                    <p>No tienes notificaciones todavía.</p>
                </div>

            <?php else: ?>

                <?php foreach ($notificaciones as $notificacion): ?>
                    
                    <?php
                        $claseLeida = $notificacion['Leida'] == 0 ? 'no-leida' : 'leida';
                    ?>

                    <a href="<?= htmlspecialchars($notificacion['URL']) ?>" class="notificacion-item <?= $claseLeida ?>">
                        
                        <div class="icono-notificacion">
                            <i class="fa-solid fa-comment-dots"></i>
                        </div>
                        
                        <div class="contenido-notificacion">
                            <p class="mensaje"><?= htmlspecialchars($notificacion['Mensaje']) ?></p>
                            <span class="fecha"><?= htmlspecialchars($notificacion['FechaCreacion']) ?></span>
                        </div>
                        
                        <?php if ($notificacion['Leida'] == 0): ?>
                            <div class="punto-nuevo"></div>
                        <?php endif; ?>

                    </a>

                <?php endforeach; ?>

            <?php endif; ?>

        </div> </div> </body>