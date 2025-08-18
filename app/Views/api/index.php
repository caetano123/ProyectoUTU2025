<div class="container">
    <h1>Comunicación Fetch (JS) & API (PHP)</h1>
    <p>Este ejemplo muestra cómo JavaScript puede hacer peticiones a un script PHP que actúa como una API sencilla, sin recargar la página.</p>

    <h2>Petición POST a la API</h2>
    <label for="postInput">Dato a enviar por POST:</label>
    <input type="text" id="postInput" value="Tecnología">
    <button id="fetchPostData" class="post">Enviar Datos (POST)</button>

    <div id="postResult" class="result-box">
        Esperando datos POST...
    </div>

    <h2><?= htmlspecialchars($titulo) ?></h2>

    <?php require BASE_PATH . "/Views/components/tabla.php"; ?>
</div>