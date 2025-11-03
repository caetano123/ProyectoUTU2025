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
            <!-- Agregá más columnas si querés -->
        </tr>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= htmlspecialchars($cliente["ID_Usuarios"]) ?></td>
                <td><?= htmlspecialchars($cliente["Nombre"]) ?></td>
                <td><?= htmlspecialchars($cliente["Apellido"]) ?></td>
                <td><?= htmlspecialchars($cliente["Correo"]) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

