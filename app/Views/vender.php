<form action="/vender/crear" method="POST" enctype="multipart/form-data">
  <div class="venta-servicios-container">
    <h2 class="venta-servicios-titulo">Venta de servicios</h2>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Título:</h4>
      <input type="text" name="titulo" class="busquedaTitulo" placeholder="Ej: Carpintero con 5 años de experiencia" minlength="4" maxlength="20" required />
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
      <input name="subcategoria" class="busquedaVender" placeholder="Escribe la subcategoría. Por ej: Carpintería" minlength="4" maxlength="25" required />
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

    <div class="botones-accion">
      <button type="submit" class="btnAceptar">Agregar Servicio</button>
    </div>
  </div>
</form>
