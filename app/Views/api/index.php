<?php


echo '
       <div class="container">
        <h1>Comunicación Fetch (JS) & API (PHP)</h1>
        <p>Este ejemplo muestra cómo JavaScript puede hacer peticiones a un script PHP que actúa como una API sencilla, sin recargar la página.</p>

        <h2>2. Petición POST a la API</h2>
        <label for="portinput">Dato a enviar por POST:</label>
        <input type="text" id="postInput" value="Hola desde el cliente!">
        <button id="fetchPostData" class="post">Enviar Datos (POST)</button>
        <div id="postResult" class="result-box">Esperando datos POST... ' ;

echo "<h2>$titulo</h2>";    

require BASE_PATH ."/app/Views/components/tabla.php"; 

echo '</div>
	</div>

    <script src="js/script.js"></script>';
