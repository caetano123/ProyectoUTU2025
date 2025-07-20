<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mini Framework' ?></title>
   <link rel="StyleSheet" href="css/style.css" />
   <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <?php $this->component('navigation') ?>
    </header>

    <main class="container">
        <?php $this->component('flash-messages') ?>
		<?= $content ?>
        </main>


        <script src="/js/script.js"> </script>
</body>
</html>
