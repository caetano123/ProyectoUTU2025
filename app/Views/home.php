   
<div class="buscador-contenedor">
    <input type="text" class="barra-busqueda" placeholder="¿Qué servicio buscas? Ej: Albañil" />
    <button class="btn-cta">Buscar</button>
</div>    
    <?php if ($auth['check']): ?>
        <p>Bienvenido <?= htmlspecialchars($auth['user']['Nombre']) ?></p>
    <?php else: ?>
       
    <?php endif; ?>

<div class="servicios-container">
  <button class="servicio-btn">
    <img src="assets/informatica.png" alt="Informática">
    <span>Informatica</span>
  </button>
  <button class="servicio-btn">
    <img src="assets/barberia.png" alt="Barbería">
    <span>Barberia</span>
  </button>
  <button class="servicio-btn">
    <img src="assets/diseño.png" alt="Diseñador gráfico">
    <span>Diseñador gráfico</span>
  </button>

   <button class="servicio-btn">
    <img src="assets/Electricista.png" alt="Electricista">
    <span>Electricista</span>
  </button>

   <button class="servicio-btn">
    <img src="assets/carpinteria.png" alt="Carpinteria">
    <span>Diseñador gráfico</span>
  </button>

  <!-- Agregá más botones según necesites -->
</div>
