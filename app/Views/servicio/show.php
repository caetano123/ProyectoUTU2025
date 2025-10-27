<body>
	<h1><?php echo $title; ?></h1>

	<p><?php echo $servicio['Descripcion']; ?></p>
	<p>Precio: $<?= number_format($servicio['Precio'], 2) ?></p>
	<p>Categoría: <?= $categoria['Nombre']; ?></p>
	<p>Subcategoría: <?= $subcategoria['Nombre']; ?></p>
	<p>Zona: <?= $zona['Nombre']; ?></p>
	<p>Publicado por: <a href="/profile?id=<?= $id_usuario ?>">
		<?= $usuario['Nombre'] . ' ' . $usuario['Apellido']; ?></a>
	</p>
	<p>Fecha de Publicación: <?= date('d/m/Y H:i', strtotime($servicio['FechaPublicacion'])); ?></p>
</body>