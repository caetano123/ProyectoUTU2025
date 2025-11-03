

<!-- Formulario oculto para filtros de categoría -->
<form action="/buscar" method="GET" id="formBusqueda" style="display:none;">
    <input type="hidden" name="categoria" id="categoriaSeleccionada" value="<?= htmlspecialchars($_GET['categoria'] ?? '') ?>" />
    <input type="hidden" name="query" value="<?= htmlspecialchars($_GET['query'] ?? '') ?>" />
</form>

<!-- Botones de categoría -->
<h4>Buscar por categoría:</h4>
<div class="busqueda-container">
    <?php
    $categorias = [
        'Marketing Digital y Ventas' => 'assets/marketing.png',
        'Tecnología y Programación' => 'assets/informatica.png',
        'Cuidado y Bienestar' => 'assets/barberia.png',
        'Diseño Gráfico y Creatividad' => 'assets/diseño.png',
        'Clases y Tutorías' => 'assets/Electricista.png',
        'Hogar y Reparaciones' => 'assets/carpinteria.png',
        'Negocios y Asistencia Virtual' => 'assets/contador.png',
        'Video, Foto y Animación' => 'assets/foto.png',
        'Eventos' => 'assets/Eventos.png',
        'Otros servicios' => 'assets/servicio.png',
        'Ver Todos' => 'assets/vertodo.png',
    ];

    $categoriaActual = $_GET['categoria'] ?? '';
    
    foreach ($categorias as $nombre => $img): 
        $esActivo = ($nombre === $categoriaActual) || ($nombre === 'Ver Todos' && empty($categoriaActual));
    ?>
        <button type="button" 
                class="servicio-btn <?= $esActivo ? 'activo' : '' ?>" 
                onclick="filtrarCategoria('<?= htmlspecialchars($nombre) ?>')">
            <img src="<?= $img ?>" alt="<?= htmlspecialchars($nombre) ?>">
            <span><?= htmlspecialchars($nombre) ?></span>
        </button>
    <?php endforeach; ?>
</div>

<!-- Tarjetas de servicios -->
<h2><?= htmlspecialchars($title) ?></h2>
<?php if(!empty($query)): ?>
    <p>Resultados para: <strong><?= htmlspecialchars($query) ?></strong></p>
<?php endif; ?>

<div class="servicios-grid">
    <?php if(!empty($servicios)): ?>
        <?php foreach($servicios as $s): ?>
        <div class="servicio-card">
            <h3><?= htmlspecialchars($s['Nombre']) ?></h3>
            <p><?= htmlspecialchars($s['Descripcion']) ?></p>
            <p class="precio">$<?= number_format($s['Precio'], 2) ?></p>
            <div class="botones-accion">
                <a href="/servicio?id=<?= $s['ID_Servicio'] ?>" class="btnAceptar">Ver más</a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay servicios disponibles con los filtros seleccionados.</p>
    <?php endif; ?>
</div>

<!-- Paginación -->
<?php if($totalPaginas > 1): ?>
<div class="paginacion">
    <?php
    $params = $_GET;
    
    // Botón anterior
    if ($pagina > 1):
        $params['pagina'] = $pagina - 1;
        $queryString = http_build_query($params);
    ?>
        <a href="/buscar?<?= $queryString ?>" class="pag-nav">‹ Anterior</a>
    <?php endif; ?>
    
    <?php
    // Números de página
    for ($i = 1; $i <= $totalPaginas; $i++):
        $params['pagina'] = $i;
        $queryString = http_build_query($params);
    ?>
        <a href="/buscar?<?= $queryString ?>" class="pag-num <?= $i == $pagina ? 'activo' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
    
    <?php
    // Botón siguiente
    if ($pagina < $totalPaginas):
        $params['pagina'] = $pagina + 1;
        $queryString = http_build_query($params);
    ?>
        <a href="/buscar?<?= $queryString ?>" class="pag-nav">Siguiente ›</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<script>
function filtrarCategoria(categoria) {
    const form = document.getElementById('formBusqueda');
    const queryActual = '<?= htmlspecialchars($_GET['query'] ?? '') ?>';
    
    // Construir URL con parámetros
    const url = new URL('/buscar', window.location.origin);
    
    // Mantener búsqueda de texto si existe
    if (queryActual) {
        url.searchParams.set('query', queryActual);
    }
    
    // Agregar categoría solo si no es "Ver Todos"
    if (categoria && categoria !== 'Ver Todos') {
        url.searchParams.set('categoria', categoria);
    }
    
    // Siempre empezar en página 1 al cambiar filtro
    window.location.href = url.toString();
}
</script>