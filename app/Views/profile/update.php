
 <body>

    <form action="/profile/save" method="POST" enctype="multipart/form-data">

        <div class="Perfil">
            <h1 class="personal">
                <?= htmlspecialchars($title ?? 'Editar Perfil de ' . $user['Nombre']) ?>
            </h1>

            <div class="info">
                <p>
                    <strong>Nombre:</strong>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($user['Nombre']) ?>">
                </p>
                <p>
                    <strong>Apellido:</strong>
                    <input type="text" name="apellido" value="<?= htmlspecialchars($user['Apellido']) ?>">
                </p>
                <p>
                    <strong>Teléfono:</strong>
                    <input type="tel" name="telefono" value="<?= htmlspecialchars($user['Telefono']) ?>">
                </p>
                <p>
                    <strong>Correo Electrónico:</strong>
                    <input type="email" name="correo" value="<?= htmlspecialchars($user['Correo']) ?>">
                </p>
            </div>

            <div class="botones-accion">
                <button type="submit" class="btnAceptar">Actualizar Perfil</button>
            </div>

        </div>
    </form>

    </body>