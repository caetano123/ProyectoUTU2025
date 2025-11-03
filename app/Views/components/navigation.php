<nav class="navbar">
  <div class="nav-left">
    <img src="<?= BASE_URL ?? '' ?>/assets/logo-ServiciOS.png" alt="Logo" class="nav-logo">
  </div>
  <div class="nav-links">

    <a href="/" class="<?= isActive('/', $currentUrl, true) ?>">Inicio</a>
    <a href="/vender" class="<?= isActive('/vender', $currentUrl) ?>">Vender Servicio</a>
    <a href="/buscar" class="<?= isActive('/buscar', $currentUrl) ?>">Servicios</a>

      <form class="navbar-search-form" action="/buscar" method="get">
        <input
          type="text"
          name="query"
          placeholder="Buscar servicio..."
          class="navbar-search-input"
          required>
        <button type="submit" class="search-button">
          <i class="fas fa-search" aria-hidden="true"></i><span class="sr-only">Buscar</span>
        </button>
      </form>


    <?php if (isset($auth) && $auth->check()): 
      $currentUser = $auth->user();
      $isAdmin = (isset($currentUser['Tipo']) && $currentUser['Tipo'] === 'ADMIN');
    ?>

      <?php if ($isAdmin): ?>
        <a href="/admin" class="<?= isActive('/admin', $currentUrl) ?>">Panel para administradores</a>
      <?php endif; ?>
      
      <div class="notificaciones-icono" id="campana-container">
        <i class="fa-solid fa-bell"></i>        
      <div class="notificaciones-panel" id="notificaciones-lista">
          <div class="notificacion-header">Notificaciones</div>
          <div id="notificaciones-items"></div>
          <div class="notificacion-footer">
              <a href="/notificaciones">Ver todas</a>
          </div>
        </div>
      </div>

      <div class="dropdown">
        <button 
          type="button" 
          class="btn-exp" 
          aria-haspopup="true"
          aria-controls="perfilDropdown"
          aria-expanded="false">
          Perfil ▼
        </button>
        <div id="perfilDropdown" class="dropdown-content">
          <a href="/profile">Ver Perfil</a>
          <a href="/configuracion">Configuración</a>
          <a href="/logout">Cerrar sesión</a>
        </div>
      </div>

    <?php else: ?>

      <a href="/login" class="btn-lgn <?= isActive('/login', $currentUrl, true) ?>">Iniciar sesión</a>
      <a href="/register" class="btn-cta <?= isActive('/register', $currentUrl, true) ?>">Registrarse</a>
    
    <?php endif; ?>

  </div>
</nav>