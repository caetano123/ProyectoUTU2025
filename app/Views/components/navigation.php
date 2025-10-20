<nav class="navbar">
  <div class="nav-left">
<img src="<?= BASE_URL ?>/assets/logo-ServiciOS.png" alt="Logo" class="nav-logo">  </div>
  <div class="nav-links">

    <a href="/" class="<?= isActive('/', $currentUrl, true) ?>">Inicio</a>

    <?php if ($auth->check()): 
      $user = $auth->user();
      $isAdmin = isset($user['roles']) && in_array('admin', $user['roles']);
    ?>
      <a href="/vender" class="<?= isActive('/vender', $currentUrl) ?>">Vender Servicio</a>


      <a href="/post" class="<?= isActive('/post', $currentUrl) ?>">Posts</a>

      
      <a href="/apicategorias" class="<?= isActive('/apicategorias', $currentUrl) ?>">Api</a>


      <div class="dropdown">
        <a href="javascript:void(0)" class="btn-exp" onclick="toggleDropdown('explorarDropdown')">Explorar ▼</a>
        <div id="explorarDropdown" class="dropdown-content">
          <a href="/informatica">Informática</a>
          <a href="/carpinteria">Carpintería</a>
          <a href="/arquitecto">Arquitecto</a>
          <a href="/albanil">Albañil</a>
          <a href="/plomero">Plomero</a>
          <a href="/electricista">Electricista</a>
          <a href="/todos">Ver Todos</a>
        </div>
      </div>

      <?php if ($isAdmin): ?>
        <a href="/dashboard" class="<?= isActive('/dashboard', $currentUrl) ?>">Dashboard</a>
        <a href="/clientes" class="<?= isActive('/clientes', $currentUrl) ?>">Clientes</a>
      <?php endif; ?>

      <a href="/servicios" class="<?= isActive('/servicios', $currentUrl) ?>">Servicios</a>

      <?php if (strpos($currentUrl, '/servicios') === false): ?>
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
      <?php endif; ?>

      <div class="dropdown">
        <a href="javascript:void(0)" class="btn-exp" onclick="toggleDropdown('perfilDropdown')">
          Perfil ▼
        </a>
        <div id="perfilDropdown" class="dropdown-content">
          <a href="/profile">Ver Perfil</a>
          <a href="/configuracion">Configuración</a>
          <a href="/logout">Cerrar sesión</a>
        </div>
      </div>

    <?php else: ?>
      <a href="/vender" class="<?= isActive('/vender', $currentUrl) ?>">Vender Servicio</a>

      <div class="dropdown">
    <a href="javascript:void(0)" class="btn-exp" onclick="toggleDropdown('explorarDropdown')">Explorar ▼</a>
    <div id="explorarDropdown" class="dropdown-content">
        <a href="#" data-query="Informática">Informática</a>
        <a href="#" data-query="Carpintería">Carpintería</a>
        <a href="#" data-query="Arquitecto">Arquitecto</a>
        <a href="#" data-query="Albañil">Albañil</a>
        <a href="#" data-query="Plomero">Plomero</a>
        <a href="#" data-query="Electricista">Electricista</a>
        <a href="#" data-query="">Ver Todos</a>
    </div>
</div>




      <?php if (strpos($currentUrl, '/servicios') === false): ?>
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
      <?php endif; ?>

      <a href="/login" class="btn-lgn <?= isActive('/login', $currentUrl, true) ?>">Iniciar sesión</a>
      <a href="/register" class="btn-cta <?= isActive('/register', $currentUrl, true) ?>">Registrarse</a>
    <?php endif; ?>

  </div>
</nav>
