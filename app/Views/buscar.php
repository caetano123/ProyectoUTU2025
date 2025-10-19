
<!-- Formulario de búsqueda -->
<form action="/buscar" method="GET" id="formBusqueda">
    

    <!-- Hidden input para la categoría -->
    <input type="hidden" name="categoria" id="categoriaSeleccionada" value="<?= $_GET['categoria'] ?? '' ?>" />
</form>

<!-- tarjetas de servicios  -->
<h2><?= $title ?></h2>
<div class="servicios-grid">
    <?php if(!empty($servicios)): ?>
        <?php foreach($servicios as $s): ?>
        <div class="servicio-card">
            <h3><?= htmlspecialchars($s['Nombre']) ?></h3>
            <p><?= htmlspecialchars($s['Descripcion']) ?></p>
            <p class="precio">$<?= number_format($s['Precio'],2) ?></p>

           
            <a href="/buscar/eliminar?id=<?= $s['ID_Servicio'] ?>" 
               class="btn-eliminar" 
               onclick="return confirm('¿Estás seguro que quieres eliminar este servicio?');">
               Eliminar
            </a>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay servicios cargados aún.</p>
    <?php endif; ?>
</div>

<!-- Botones de categoría -->
<h4>Buscar por categoría:</h4>
<div class="busqueda-container">
    <button type="button" class="servicio-btn">
        <img src="assets/informatica.png" alt="Informática">
        <span>Informática</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/barberia.png" alt="Barbería">
        <span>Barberia</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/diseño.png" alt="Diseñador gráfico">
        <span>Diseñador gráfico</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/Electricista.png" alt="Electricista">
        <span>Electricista</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/vertodo.png" alt="VerTodos">
        <span>Ver Todos</span>
    </button>
</div>



