<?php $this->component('banner-home') ?>
</div>    
 <?php if ($auth->check()): ?>
    <p>Bienvenido <?= htmlspecialchars($auth->user()["Nombre"]) ?></p>
<?php else: ?>
       
    <?php endif; ?>

<div class="servicios-container">
  <button class="servicio-btn">
    <img src="assets/informatica.png" alt="Tecnología y Programación">
    <span>Tecnología y Programación</span>
  </button>
  <button class="servicio-btn">
    <img src="assets/barberia.png" alt="Cuidado y Bienestar">
    <span>Cuidado y Bienestar</span>
  </button>
  <button class="servicio-btn">
    <img src="assets/diseño.png" alt="Diseño Gráfico y Creatividad">
    <span>Diseño Gráfico y Creatividad</span>
  </button>

   <button class="servicio-btn">
    <img src="assets/Electricista.png" alt="Clases y Tutorías">
    <span>Clases y Tutorías</span>
  </button>

   <button class="servicio-btn">
    <img src="assets/carpinteria.png" alt="Hogar y Reparaciones">
    <span>Hogar y Reparaciones</span>
  </button>
  <!-- Agregá más botones según necesites -->
</div>

<?php $this->component('carousel-home') ?>

<section class="info-section">
  <div class="info-content">
    <div class="info-text">
      <h2>Freelancers competentes en todos los campos a solo un clic</h2>
      <p><strong>Trabajo de calidad - eficiente y confiable</strong><br>
      Recibe entregas puntuales y de alta calidad, ya sea un trabajo a corto plazo o un proyecto complejo.</p>

      <p><strong>Seguridad en cada pedido</strong><br>
      Pagos protegidos mediante tecnología SSL. Las transacciones no se liberan hasta que se apruebe la entrega.</p>

      <p><strong>Locales o globales</strong><br>
      Trabaja con expertos de habla español o con talento profesional internacional, de acuerdo a tus preferencias y requisitos.</p>

      <p><strong>24/7 - soporte continuo a cualquier hora del día</strong><br>
      ¿Tienes alguna pregunta? Nuestro equipo de soporte está disponible a cualquier hora del día, en todo momento y en todo lugar.</p>
    </div>

    <div class="info-video">
  <iframe
    width="100%" height="100%"
    src="https://www.youtube.com/embed/kcfs1-ryKWE?autoplay=1&mute=1&loop=1&playlist=kcfs1-ryKWE&controls=1"
    title="Video de ejemplo"
    frameborder="0"
    allow="autoplay; fullscreen"
    allowfullscreen
  ></iframe>
</div>

</section>


<section class="how-it-works">
  <h2 class="how-title">¿Cómo funciona?</h2>
  <div class="cards-container">

    <div class="how-card">
      <img src="https://cdn-icons-png.flaticon.com/512/751/751463.png" alt="Búsqueda" />
      <h3>Búsqueda simple</h3>
      <p>Usa la barra de búsqueda para buscar un servicio o encuentra la categoría adecuada en el menú de navegación.</p>
    </div>

    <div class="how-card">
      <img src="https://cdn-icons-png.flaticon.com/512/565/565654.png" alt="Selección" />
      <h3>Selección simple</h3>
      <p>Elige un servicio en base a las calificaciones, el nivel y los comentarios, o usa opciones de filtro como "Freelancer que hable español".</p>
    </div>

    <div class="how-card">
      <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png" alt="Pago" />
      <h3>Pago fácil</h3>
      <p>Contrata a tu freelancer de manera fácil y segura: pagos protegidos, comunicación directa y entregas puntuales.</p>
    </div>

  </div>

  <p class="how-footer">¿Tienes alguna pregunta? <a href="#">Más información aquí.</a></p>
</section>

<section class="categories-section">
  <h2 class="categories-title">Lo necesitas, nosotros lo tenemos</h2>
  <div class="categories-grid">

    <a href="buscar?categoria=Diseño Gráfico y Creatividad" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/2921/2921222.png" alt="Diseño Gráfico y Creatividad" />
      <span>Diseño Gráfico y Creatividad</span>
    </a>

    <a href="buscar?categoria=Marketing Digital y Ventas" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/2913/2913926.png" alt="Marketing Digital y Ventas" />
      <span>Marketing Digital y Ventas</span>
    </a>

    <a href="buscar?categoria=Clases y Tutorías" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/3159/3159989.png" alt="Clases y Tutorías" />
      <span>Clases y Tutorías</span>
    </a>

    <a href="buscar?categoria=Video, Foto y Animación" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/3237/3237873.png" alt="Video, Foto y Animación" />
      <span>Video, Foto y Animación</span>
    </a>

    <a href="buscar?categoria=Música y Audio" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/727/727218.png" alt="Música y Audio" />
      <span>Música y Audio</span>
    </a>

    <a href="buscar?categoria=Tecnología y Programación" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Tecnología y Programación" />
      <span>Tecnología y Programación</span>
    </a>

    <a href="buscar?categoria=Negocios y Asistencia Virtual" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/1399/1399417.png" alt="Negocios y Asistencia Virtual" />
      <span>Negocios y Asistencia Virtual</span>
    </a>

    <a href="buscar?categoria=Cuidado y Bienestar" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/891/891462.png" alt="Cuidado y Bienestar" />
      <span>Cuidado y Bienestar</span>
    </a>

    <a href="buscar?categoria=Eventos" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/2921/2921221.png" alt="Eventos" />
      <span>Eventos</span>
    </a>

    <a href="buscar?categoria=Otra (especificar)" class="category-btn">
      <img src="https://cdn-icons-png.flaticon.com/512/3132/3132693.png" alt="Otra (especificar)" />
      <span>Otra (especificar)</span>
    </a>



  </div>
</section>


<section class="faq-section">
  <h2>Preguntas frecuentes</h2>

  <div class="faq-item">
    <button class="faq-question" aria-expanded="false">
      ¿Por qué debería contratar a un freelancer?
      <span class="arrow" aria-hidden="true">›</span>
    </button>
    <div class="faq-answer" hidden>
      Debido a la creciente escasez de trabajadores calificados, actualmente es cada vez más difícil encontrar personal competente a tiempo completo. No en vano, cada vez más empresas españolas trabajan con freelancers que ofrecen sus servicios online. Los sitios web de freelancers permiten a las empresas encontrar a los expertos adecuados de manera rápida y confiable, y así seguir siendo competitivas en el mercado actual. Por el contrario, cada vez más freelancers prefieren encontrar su trabajo por cuenta propia y de forma online, ya que la gestión es menor y la oferta de trabajo es mayor.
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question" aria-expanded="false">
      ¿En qué se diferencia ServiciOs de otras plataformas?
      <span class="arrow" aria-hidden="true">›</span>
    </button>
    <div class="faq-answer" hidden>
      ServiciOs es un portal de freelancers en el cual puedes encontrar freelancers profesionales y con experiencia en más de 500 categorías y sectores. Ya sea marketing en redes sociales o desarrollo web, freelancers de Uruguay o proveedores internacionales, ya sean proyectos a corto o largo plazo, aquí encontrarás a los especialistas idóneos para todas tus necesidades. Registro fácil y gratuito, comunicación directa, pago seguro y atención al cliente las 24 horas del día.
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question" aria-expanded="false">
      ¿Cuánto cuesta registrarse en ServiciOs?
      <span class="arrow" aria-hidden="true">›</span>
    </button>
    <div class="faq-answer" hidden>
      Registrarte y crear tu perfil en ServiciOs es gratuito. Por cada pedido cobramos una tarifa del 5,5% del precio de compra. De este modo cubrimos nuestros gastos administrativos y podemos garantizar el correcto funcionamiento de nuestra plataforma y una atención al cliente continua durante todo el día.
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question" aria-expanded="false">
      ¿Cómo puedo comunicarme con mi freelancer?
      <span class="arrow" aria-hidden="true">›</span>
    </button>
    <div class="faq-answer" hidden>
      Puedes enviar mensajes a los freelancers de manera fácil y directa a través del botón "Comunicación con el Freelancer". No tienes que encargar un pedido primero para establecer una comunicación. De esta manera, no solo puedes establecer los precios y los plazos de los proyectos, sino también generar confianza y definir las expectativas. Además de la función de chat, también es posible configurar videollamadas con el freelancer.
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question" aria-expanded="false">
      ¿Qué métodos de pago ofrece ServiciOs?
      <span class="arrow" aria-hidden="true">›</span>
    </button>
    <div class="faq-answer" hidden>
      Existen varios métodos de pago disponibles para los usuarios uruguayos, entre ellos, tarjeta de crédito y PayPal. Cada uno de estos métodos garantiza transacciones rápidas, eficientes y seguras en la plataforma.
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question" aria-expanded="false">
      ¿Cómo sé que voy a recibir el trabajo por el cual pagué?
      <span class="arrow" aria-hidden="true">›</span>
    </button>
    <div class="faq-answer" hidden>
      La confianza y la seguridad constituyen nuestras principales prioridades. Queremos que tú, el cliente, tengas la mejor experiencia posible y obtengas resultados inmejorables. Si necesitas ayuda con los pedidos, los pagos, la privacidad o la calidad del servicio, estamos aquí para ayudarte.
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question" aria-expanded="false">
      ¿A quién debo dirigirme si tengo problemas con un pedido o con un freelancer?
      <span class="arrow" aria-hidden="true">›</span>
    </button>
    <div class="faq-answer" hidden>
      Nuestro servicio de atención al cliente está disponible durante todo el día. En caso de problemas con pedidos, pagos, tu perfil u otras preguntas, solo tienes que comunicarte con nuestros especialistas de soporte.
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question" aria-expanded="false">
      ¿También puedo trabajar con freelancers de habla español?
      <span class="arrow" aria-hidden="true">›</span>
    </button>
    <div class="faq-answer" hidden>
      Sí, en ServiciOs puedes trabajar fácilmente con freelancers que hablen español. Puedes filtrar los resultados por idioma para encontrar profesionales que se comuniquen contigo sin barreras.
    </div>
  </div>

</section>



<?php $this->component('categorias-home') ?>
