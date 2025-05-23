

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Lista de Usuarios</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>

        <h1>Usuarios</h1>

        <!-- Formulario para buscar usuario por ID -->
        <h2>Buscar usuario por ID</h2>
        <form method="GET" action="">
            <input type="number" name="usuario_id" placeholder="Ingrese ID del usuario" required>
            <button type="submit">Buscar</button>
        </form>

        <!-- Tabla con todos los usuarios -->
    <h2>Lista de usuarios</h2>
    <table>
    <?php
    if ($usuarios && $usuarios->num_rows > 0):
        // Definir manualmente las columnas que quieres mostrar
        $columnasMostrar = ['id', 'nombre', 'email', 'pass']; // Añade todas las que necesites
        
        // Mostrar encabezado
        echo "<tr>";
        foreach ($columnasMostrar as $columna) {
            echo "<th>" . htmlspecialchars($columna) . "</th>";
        }
        echo "</tr>";

        // Mostrar filas
        $usuarios->data_seek(0); // Reiniciar el puntero
        while ($fila = $usuarios->fetch_assoc()) {
            echo "<tr>";
            foreach ($columnasMostrar as $columna) {
                echo "<td>" . htmlspecialchars($fila[$columna] ?? 'N/A') . "</td>";
            }
            echo "</tr>";
        }
    else:
        echo "<tr><td colspan='4'>No hay usuarios para mostrar.</td></tr>";
    endif;
    ?>
    </table>

        <!-- Tabla con usuario filtrado -->
        <?php if ($usuarioFiltrado): ?>
            <h2>Usuario buscado</h2>
            <table>
                <tr>
                    <?php
                    // Mostrar encabezados del usuario filtrado dinámicamente
                    foreach (array_keys($usuarioFiltrado) as $campo) {
                        echo "<th>" . htmlspecialchars($campo) . "</th>";
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    // Mostrar valores del usuario filtrado
                    foreach ($usuarioFiltrado as $valor) {
                        echo "<td>" . htmlspecialchars($valor) . "</td>";
                    }
                    ?>
                </tr>
            </table>
        <?php elseif (isset($_GET['usuario_id'])): ?>
            <p>No se encontró ningún usuario con ese ID.</p>
        <?php endif; ?>


        <form method="POST" action="index.php">
            <input type="hidden" name="id" value="<?php echo isset($usuarioFiltrado['id']) ? htmlspecialchars($usuarioFiltrado['id']) : ''; ?>">

            <label for="username">Usuario:</label><br>
            <input type="text" id="nombre" name="nombre" required value="<?php echo isset($usuarioFiltrado['nombre']) ? htmlspecialchars($usuarioFiltrado['nombre']) : ''; ?>"><br>

            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" <?php echo isset($usuarioFiltrado['id']) ? '' : 'required'; ?>><br>
            <small><?php if (isset($usuarioFiltrado['id'])) echo "Dejar vacío para no cambiar la contraseña."; ?></small><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required value="<?php echo isset($usuarioFiltrado['email']) ? htmlspecialchars($usuarioFiltrado['email']) : ''; ?>"><br><br>

            <button type="submit" name="submit">
                <?php echo isset($usuarioFiltrado['id']) ? 'Actualizar Usuario' : 'Crear Usuario'; ?>
            </button>
        </form>

        

    </body>
    </html>
