<body>
    <form action="/servicio/update" method="POST" enctype="multipart/form-data">
        <div class="servicio-container">
            <h1 class="personal">
                <?= htmlspecialchars($title . htmlspecialchars($servicio['Nombre'])) ?>
            </h1>
            <input type="hidden" name="id" value="<?= htmlspecialchars($servicio['ID_Servicio']) ?>">

    <div class="busqueda-linea">
      <h4 class="h4Nom">Título:</h4>
      <input type="text" name="titulo" class="busquedaTitulo" value="<?=htmlspecialchars($servicio['Nombre'])?>" minlength="4" maxlength="30" required />
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Descripción:</h4>
      <textarea name="descripcion" class="descripcion" placeholder="<?=htmlspecialchars($servicio['Descripcion'])?>" maxlength="200" required></textarea>
    </div>

       <div class="busqueda-linea">
      <h4 class="h4Nom">Precio (por hora trabajada en pesos uruguayos):</h4>
      <input type="text" name="precio" class="busquedaVender" value="<?=htmlspecialchars($servicio['Precio'])?>" minlength="1" maxlength="10" required />
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
            </div>
            <div class="botones-accion">
                <button type="submit" class="btnAceptar">Actualizar Servicio</button>
            </div>
        </div>
    </form>
</body>