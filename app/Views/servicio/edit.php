<body>
    <form action="/servicio/update" method="POST" enctype="multipart/form-data">
        <div class="servicio-container">
            <h1 class="personal">
                <?= htmlspecialchars($title ?? 'Editar Servicio ' . $servicio['Nombre']) ?>
            </h1>
            <input type="hidden" name="id" value="<?= htmlspecialchars($servicio['ID_Servicio']) ?>">

                <p class="servicio-descripcion">
                    <strong>Nombre:</strong>
                    <input type="text" name="Nombre" value="<?= htmlspecialchars($servicio['Nombre']) ?>">
                </p>

                <p class="servicio-descripcion">
                    <strong>Descripción:</strong>
                    <textarea name="Descripcion"><?= htmlspecialchars($servicio['Descripcion']) ?></textarea>
                </p>

            <div class="servicio-info-box">
                <p><strong>Precio:</strong>
                    <input type="text" name="Precio" value="<?= htmlspecialchars($servicio['Precio']) ?>">
                </p>
                <div class="busqueda-linea">
      <h4 class="h4Nom">Categoría:</h4>
      <select name="categoria" class="busquedaCategoria" required>
        <option value="">Selecciona una categoría</option>
        <option value="1">Diseño Gráfico y Creatividad</option>
        <option value="2">Tecnología y Programación</option>
        <option value="3">Marketing Digital y Ventas</option>
        <option value="4">Video, Foto y Animación</option>
        <option value="5">Negocios y Asistencia Virtual</option>
        <option value="6">Hogar y Reparaciones</option>
        <option value="7">Clases y Tutorías</option>
        <option value="8">Eventos</option>
        <option value="9">Cuidado y Bienestar</option>
        <option value="10">Otra (especificar)</option>
      </select>
    </div>
        <div class="busqueda-linea">
      <h4 class="h4Nom">Subcategoria:</h4>
      <input name="subcategoria" class="busquedaVender" value="<?= htmlspecialchars($servicio['Subcategoria']) ?>" required />
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Zona:</h4>
      <select name="zona" class="busquedaCategoria" required>
        <option value="">Selecciona una zona</option>
        <option value="1">18 de Mayo</option>
        <option value="2">Canelones</option>
        <option value="3">La Paz</option>
        <option value="4">Las Brujas</option>
        <option value="5">Las Piedras</option>
        <option value="6">Los Cerrillos</option>
        <option value="7">Montevideo</option>
        <option value="8">Progreso</option>
        <option value="9">Toledo</option>
      </select>
    </div>
            </div>
            <div class="botones-accion">
                <button type="submit" class="btnAceptar">Actualizar Servicio</button>
            </div>
        </div>
    </form>
</body>