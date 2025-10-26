<form action="/vender/crear" method="POST" enctype="multipart/form-data">
  <div class="venta-servicios-container">
    <h2 class="venta-servicios-titulo">Venta de servicios</h2>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Título:</h4>
      <input type="text" name="titulo" class="busquedaTitulo" placeholder="Ej: Carpintero con 5 años de experiencia" required />
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Categoría:</h4>
      <select name="categoria" class="busquedaCategoria" required>
        <option value="">Selecciona una categoría</option>
        <option value="electricista">Electricista</option>
        <option value="plomero">Plomero</option>
        <option value="pintor">Pintor</option>
        <option value="mecanico">Mecánico</option>
        <option value="jardinero">Jardinero</option>
        <option value="arquitecto">Arquitecto</option>
        <option value="barbero">Barbero</option>
        <option value="contador">Contador</option>
        <option value="albanil">Albañil</option>
        <option value="soldador">Soldador</option>
        <option value="disenador">Diseñador</option>
        <option value="informatico">Informático</option>
        <option value="otra">Otra (especificar)</option>
      </select>
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

    <div class="busqueda-linea">
      <h4 class="h4Nom">Descripción:</h4>
      <textarea name="descripcion" class="descripcion" placeholder="Escribe una descripción" required></textarea>
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Precio (por hora trabajada en pesos $):</h4>
      <input type="text" name="precio" class="busquedaVender" placeholder="Ej: 4000" required />
    </div>

    <div class="busqueda-linea">
      <h4 class="h4Nom">Subir imagen:</h4>
      <div class="file-upload-wrapper">
        <label for="imagen" class="file-upload-label">Elegir archivo</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" class="file-upload-input" />
        <span class="file-upload-name" id="file-name">Ningún archivo seleccionado</span>
      </div>
    </div>

    <div class="botones-accion">
      <button type="submit" class="btnAceptar">Agregar Servicio</button>
    </div>
  </div>
</form>
