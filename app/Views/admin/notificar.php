<body>
    <div class="container-general">
        <h1><?= htmlspecialchars($titulo) ?></h1>
        <p>Estás enviando una notificación a: <strong><?= htmlspecialchars($usuario['Correo']) ?></strong></p>

        <form action="/admin/notificar/send" method="POST">
            
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['ID_Persona']) ?>">

            <div class="busqueda-linea">
                <h4 class="h4Nom">Mensaje:</h4>
                <textarea name="mensaje" class="descripcion" rows="5" required placeholder="Escribe tu mensaje aquí..."></textarea>
            </div>

            <div class="busqueda-linea">
                <h4 class="h4Nom">URL (Opcional):</h4>
                <input name="url" class="busquedaVender" placeholder="Ej: /servicio?id=5 (Si está vacío, va a /notificaciones)">
            </div>
            
            <div class="botones-accion" style="margin-top: 20px;">
                <button type="submit" class="btnAceptar">Enviar Notificación</button>
                <a href="/paneladmin" class="btnBorrar">Cancelar</a>
            </div>
        </form>
    </div>
</body>