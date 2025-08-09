<?php 

if ( count( $datosTabla) > 0 ){

	if ( isset($datosTabla[0]) ){ 
			$columnas = array_keys($datosTabla[0]);

	}else  {
			$columnas = array_keys($datosTabla);
			$datosTabla = [ $datosTabla ];
	}?>
    <table border="1">
        <thead>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datosTabla as $fila): ?>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <td><?= htmlspecialchars($fila[$col]) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php } else{ echo "<h1>SIN DATOS</h1>" ;} ?>
