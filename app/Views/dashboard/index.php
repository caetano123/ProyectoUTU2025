<h1>Dashboard</h1>
<p>Bienvenido al área privada, <?= htmlspecialchars($user['Nombre'] ?? 'Usuario') ?>.</p>

<p>Datos del usuario:</p>
<ul>
    <li>ID: <?= htmlspecialchars($user['ID_Usuarios'] ?? 'No definido') ?></li>
    <li>Email: <?= htmlspecialchars($user['Correo'] ?? 'No definido') ?></li>
</ul>

<p><a href="/dashboard/<?= htmlspecialchars($user['ID_Usuarios'] ?? '') ?>">Ver detalles de mi perfil</a></p>