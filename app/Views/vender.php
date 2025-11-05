<form action="/vender/crear" method="POST" enctype="multipart/form-data">
  <div class="venta-servicios-container">
    <h2 class="venta-servicios-titulo">Venta de servicios</h2>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Título:</h4>
      <input type="text" name="titulo" class="busquedaTitulo" placeholder="Ej: Carpintero con 5 años de experiencia" minlength="4" maxlength="50" required />
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Descripción:</h4>
      <textarea name="descripcion" class="descripcion" placeholder="Escribe una descripción" maxlength="200" required></textarea>
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Precio (por hora trabajada en pesos uruguayos):</h4>
      <input type="text" name="precio" class="busquedaVender" placeholder="Ej: 4000" minlength="1" maxlength="10" required />
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Categoría:</h4>

      <select name="categoria" id="select-categoria" class="busquedaCategoria" required>
        <option value="">Selecciona una categoría</option>
        <?php foreach ($categorias as $cat): ?>
           <option value="<?= $cat['ID_Categoria'] ?>">
            <?= htmlspecialchars($cat['Nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Subcategoria:</h4>
      
      <select name="subcategoria" id="select-subcategoria" class="busquedaCategoria" required disabled>
        <option value="">Selecciona una categoría primero</option>
      </select>
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Zona:</h4>
      <select name="zona" class="busquedaCategoria" required>
        <option value="">Selecciona una zona</option>
        <?php foreach ($zonas as $zona): ?>
          <option value="<?= $zona['ID_Zona'] ?>">
               <?= htmlspecialchars($zona['Nombre']) ?>
              </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="botones-accion">
      <button type="submit" class="btnAceptar">Agregar Servicio</button>
    </div>
  </div>
</form>
