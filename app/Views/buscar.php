
<!-- Formulario de búsqueda -->
<form action="/buscar" method="GET" id="formBusqueda">
    

    <!-- Hidden input para la categoría -->
    <input type="hidden" name="categoria" id="categoriaSeleccionada" value="<?= $_GET['categoria'] ?? '' ?>" />
</form>

<!-- Botones de categoría -->
<h4>Buscar por categoría:</h4>
<div class="busqueda-container">
    <button type="button" class="servicio-btn">
        <img src="assets/marketing.png" alt="Marketing Digital y Ventas">
        <span>Marketing Digital y Ventas</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/informatica.png" alt="Tecnología y Programación">
        <span>Tecnología y Programación</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/barberia.png" alt="Cuidado y Bienestar">
        <span>Cuidado y Bienestar</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/diseño.png" alt="Diseño Gráfico y Creatividad">
        <span>Diseño Gráfico y Creatividad</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/Electricista.png" alt="Clases y Tutorías">
        <span>Clases y Tutorías</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/carpinteria.png" alt="Hogar y Reparaciones">
        <span>Hogar y Reparaciones</span>
    </button>
      <button type="button" class="servicio-btn">
        <img src="assets/contador.png" alt="Negocios y Asistencia Virtual">
        <span>Negocios y Asistencia Virtual</span>
    </button>
       <button type="button" class="servicio-btn">
        <img src="assets/foto.png" alt="Video, Foto y Animación">
        <span>Video, Foto y Animación</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/Eventos.png" alt="Eventos">
        <span>Eventos</span>
    </button>
    <button type="button" class="servicio-btn">
        <img src="assets/servicio.png" alt="Otros servicios">
        <span>Otros servicios</span>
    </button>   
    <button type="button" class="servicio-btn">
        <img src="assets/vertodo.png" alt="Ver Todos">
        <span>Ver Todos</span>
    </button>
</div>

<!-- tarjetas de servicios  -->
<h2><?= $title ?></h2>
<div class="servicios-grid">
    <?php if(!empty($servicios)): ?>
        <?php foreach($servicios as $s): ?>
        <div class="servicio-card">
            <h3><?= htmlspecialchars($s['Nombre']) ?></h3>
            <p><?= htmlspecialchars($s['Descripcion']) ?></p>
            <p class="precio">$<?= number_format($s['Precio'],2) ?></p>

            <div class="botones-accion">
                 <a href="/servicio?id=<?= $s['ID_Servicio'] ?>" class="btnAceptar">
                     Ver más
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay servicios cargados aún.</p>
    <?php endif; ?>
</div>




