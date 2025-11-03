<body>
    <h1><?= htmlspecialchars($title) ?></h1>
    <p>Bienvenido al panel de administración.</p>
    <?php if (count($clientes) == 0): ?>
    <h1>NO HAY CLIENTES</h1>
<?php else: ?>
    <h1>Clientes</h1>
    <table class="tabla">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Fecha de Registro</th>
            <th>Acciones</th>
            <!-- Agregá más columnas si querés -->
        </tr>
        <?php foreach ($clientes as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c["ID_Persona"]) ?></td>
                <td><?= htmlspecialchars($c["Nombre"]) ?></td>
                <td><?= htmlspecialchars($c["Apellido"]) ?></td>
                <td><?= htmlspecialchars($c["Correo"]) ?></td>
                <td><?= htmlspecialchars($c["Telefono"]) ?></td>
                <td><?= htmlspecialchars($c["FechaRegistro"]) ?></td>
                <td>
                      <div class="acciones-btn">
                            <a href="/admin/notificar?id=<?= htmlspecialchars($c["ID_Persona"]) ?>" class="btnAceptar">Notificar</a>

                            <form method="POST" action="/admin/deleteUser">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($c["ID_Persona"]) ?>">
                                <button type="submit" class="btnBorrar" onclick="return confirm('¿Estás seguro de que querés eliminar este usuario?');">Eliminar</button>
                            </form>

                        <?php if ($c["Tipo"] != 'ADMIN'): ?>
                        <form method="POST" action="/admin/makeAdmin" style="display:inline;">
                            <input type="hidden" name="id" value='<?= htmlspecialchars($c["ID_Persona"]) ?>'>
                            <button type="submit" class="btnAceptar" onclick="return confirm('¿Estás seguro de que querés hacer admin a este usuario?');">Hacer Admin</button>
                        </form>
                    <?php endif; ?>

                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if (count($servicios) == 0): ?>
    <h1>NO HAY SERVICIOS</h1>
<?php else: ?>
    <h1>Servicios</h1>
    <table class="tabla">
        <tr>
            <th>ID</th>
            <th>ID Usuario</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Fecha de Publicación</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($servicios as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s["ID_Servicio"]) ?></td>
                <td><?= htmlspecialchars($s["ID_Persona"]) ?></td>
                <td><?= htmlspecialchars($s["Nombre"]) ?></td>
                <td><?= htmlspecialchars($s["Descripcion"]) ?></td>
                <td><?= htmlspecialchars($s["Precio"]) ?></td>
                <td><?= htmlspecialchars($s["FechaPublicacion"]) ?></td>
                <td>
                        <div class="botones-accion">
                            <a href="/servicio?id=<?= htmlspecialchars($s["ID_Servicio"]) ?>" class="btnAceptar">Ver más</a>

                            <form method="POST" action="/buscar/eliminar">
                                <input type="hidden" name="id" value="<?= $s['ID_Servicio'] ?>">
                                <button type="submit" class="btnBorrar" 
                                    onclick="return confirm('¿Estás seguro que quieres eliminar este servicio?');">Eliminar</button>
                            </form>
                        </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>