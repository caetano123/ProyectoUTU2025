<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mini Framework' ?></title>
   <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    
/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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


<footer class="footer">
  <div class="footer-left">
    <img src="<?= BASE_URL ?>/assets/logo-ServiciOS.png" alt="Logo ServiciOs" class="footer-logo" />
    <img src="<?= BASE_URL ?>/assets/logo-quadstack.png" alt="Logo Quadstack" class="footer-logo" />
  </div>

  <div class="footer-center">
    <!-- Espacio vacío para separar -->
  </div>

  <div class="footer-right">
    <div class="footer-icons">
      <a href="https://www.instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="https://www.facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://www.linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn">
        <i class="fab fa-linkedin-in"></i>
      </a>
      <a href="https://www.tiktok.com" target="_blank" rel="noopener" aria-label="TikTok">
        <i class="fab fa-tiktok"></i>
      </a>
      <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="X">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="https://www.pinterest.com" target="_blank" rel="noopener" aria-label="Pinterest">
        <i class="fab fa-pinterest-p"></i>
      </a>
    </div>
  </div>

   <div class="footer-copy">
    <a href="<?= BASE_URL ?>/terminos" target="_blank" title="Lee nuestros Términos y Condiciones">
      Términos y Condiciones</a>
      © ServiciOs Association Ltd. 2025 
  </div>

</footer>


    <script src="<?= BASE_URL ?>/js/script.js"> </script>
</body>

</html>