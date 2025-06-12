<?php
function isActive(string $path, string $currentUrl, bool $exact = false): string {
    if ($exact) {
        return $currentUrl === $path ? 'active' : '';
    }
    return strpos($currentUrl, $path) === 0 ? 'active' : '';
}
?>

<nav>
    <a href="/" class="<?= isActive('/', $currentUrl, true) ?>">Inicio</a>
    
    <?php if ($auth['check']): ?>
        <a href="/dashboard" class="<?= isActive('/dashboard', $currentUrl) ?>">Dashboard</a>
        <a href="/clientes" class="<?= isActive('/clientes', $currentUrl) ?>">Clientes</a>
        <a href="/servicios" class="<?= isActive('/servicios', $currentUrl) ?>">Servicios</a>
        <a href="/perfil" class="<?= isActive('/perfil', $currentUrl) ?>">Perfil</a>
        <a href="/logout">Cerrar sesión</a>
    <?php else: ?>
        <a href="/login" class="<?= isActive('/login', $currentUrl, true) ?>">Login</a>
        <a href="/register" class="<?= isActive('/register', $currentUrl, true) ?>">Register</a>
        <a href="/clientes" class="<?= isActive('/clientes', $currentUrl) ?>">Clientes</a>
    <?php endif; ?>
</nav>