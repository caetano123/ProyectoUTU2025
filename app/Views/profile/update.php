
 <body>

    <form action="/profile/save" method="POST" enctype="multipart/form-data">

        <div class="Perfil">
            <h1 class="personal">
                <?= htmlspecialchars($title ?? 'Editar Perfil de ' . $user['Nombre']) ?>
            </h1>

            <div class="foto-perfil">
                <img src="<?= htmlspecialchars($user['UrlFoto'] ?? 'profile-picture.jpg') ?>" alt="Profile Picture" class="foto-perfil-img">
                
                <label for="nueva-foto">Cambiar foto de perfil:</label>
                <input type="file" name="nueva_foto" id="nueva-foto" accept="image/png, image/jpeg">
            </div>

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

            <div class="acciones">
                <button type="submit">Actualizar Perfil</button>
            </div>

        </div>
    </form>

    </body>