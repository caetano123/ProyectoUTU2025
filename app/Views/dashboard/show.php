    <h1>Detalles de Usuario</h1>
    <p>ID de usuario: <?= $userId ?? 'N/A' ?></p>
    <p>Nombre: <?= htmlspecialchars($user['Nombre']) ?></p>
    <p>Email: <?= htmlspecialchars($user['Correo']) ?></p>
    
    <p><a href="/dashboard">← Volver al dashboard</a></p>

