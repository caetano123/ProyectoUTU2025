<?php

function isActive(string $path, string $currentUrl, bool $exact = false): string {
    if ($exact) {
        return $currentUrl === $path ? 'active' : '';
    }
    return strpos($currentUrl, $path) === 0 ? 'active' : '';
}
?>

<nav>
    <div class="nav-left">ServiciOS</div>
    <a href="/" class="<?= isActive('/', $currentUrl, true) ?>">Inicio</a>
    
    <?php if ($auth['check']): ?>
        <a href="/dashboard" class="<?= isActive('/dashboard', $currentUrl) ?>">Dashboard</a>
        <a href="/clientes" class="<?= isActive('/clientes', $currentUrl) ?>">Clientes</a>
        <a href="/servicios" class="<?= isActive('/servicios', $currentUrl) ?>">Servicios</a>
        <a href="/perfil" class="<?= isActive('/perfil', $currentUrl) ?>">Perfil</a>
        <a href="/logout">Cerrar sesión</a>
    <?php else: ?>
         <a href="/vender" class="<?= isActive('/explorar', $currentUrl) ?>">Vender Servicio</a>
          <a href="/buscar" class="<?= isActive('/explorar', $currentUrl) ?>">Buscar Servicio</a>
        
        
          <div class="dropdown">
  <a href="javascript:void(0)" class="btn-exp" onclick="toggleDropdown()">Explorar ▼</a>
  <div id="dropdownContent" class="dropdown-content">
    <a href="/informatica">Informatica</a>
    <a href="/carpinteria">Carpinteria</a>
    <a href="/arquitecto">Arquitecto</a>
    <a href="/arquitecto">Albañil</a>
    <a href="/plomero">Plomero</a>
    <a href="/electricista">Electricista</a>
    <a href="/todos">Ver Todos</a>
  </div>
</div>
        <a href="/login" class="btn-lgn<?= isActive('/login', $currentUrl, true) ?>">Iniciar sesión</a>
        <a href="/register" class="btn-cta<?= isActive('/register', $currentUrl, true) ?>">Registrarse</a>
        
    <?php endif; ?>
</nav>

