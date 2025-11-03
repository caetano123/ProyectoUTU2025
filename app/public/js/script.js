// ENVUELVE TODO en DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {

  // --- 1. Lógica de Menús Dropdown ---
    // Cierra todos los menús abiertos
  function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-content.show').forEach(menu => {
      menu.classList.remove('show');
      // Restablece el ARIA del botón que lo controla
      const trigger = document.querySelector(`[aria-controls="${menu.id}"]`);
      if (trigger) {
        trigger.setAttribute('aria-expanded', 'false');
      }
    });
  }
    // Busca todos los botones que activan menús
  const dropdownTriggers = document.querySelectorAll('button[aria-haspopup="true"]');
  dropdownTriggers.forEach(trigger => {
    trigger.addEventListener('click', (event) => {
      event.stopPropagation(); 
      const menuId = trigger.getAttribute('aria-controls');
      const menu = document.getElementById(menuId);
      if (menu) {
        const isExpanded = menu.classList.contains('show');
        closeAllDropdowns();
        if (!isExpanded) {
          menu.classList.add('show');
          trigger.setAttribute('aria-expanded', 'true');
        }
      }
    });
  });

  window.addEventListener('click', (event) => {
    if (!event.target.matches('button[aria-haspopup="true"]')) {
      closeAllDropdowns();
    }
  });
   
  // --- 2. Lógica del Carrusel ---
     const track = document.getElementById("track");
    if (track) {
        const wrap = track.parentElement;
        const cards = Array.from(track.children);
        const prev = document.getElementById("prev");
        const next = document.getElementById("next");
        const dotsBox = document.getElementById("dots");
        let current = 0;

        const isMobile = () => matchMedia("(max-width:767px)").matches;

        // Crear dots
        cards.forEach((_, i) => {
            const dot = document.createElement("span");
            dot.className = "dot";
            dot.onclick = () => activate(i, true);
            dotsBox.appendChild(dot);
        });

        const dots = Array.from(dotsBox.children);

        function center(i) {
            const card = cards[i];
            const axis = isMobile() ? "top" : "left";
            const size = isMobile() ? "clientHeight" : "clientWidth";
            const start = isMobile() ? card.offsetTop : card.offsetLeft;
            wrap.scrollTo({
                [axis]: start - (wrap[size] / 2 - card[size] / 2),
                behavior: "smooth"
            });
        }

        function toggleUI(i) {
            cards.forEach((c, k) => c.toggleAttribute("active", k === i));
            dots.forEach((d, k) => d.classList.toggle("active", k === i));
            prev.disabled = i === 0;
            next.disabled = i === cards.length - 1;
        }

        function activate(i, scroll) {
            if (i === current) return;
            current = i;
            toggleUI(i);
            if (scroll) center(i);
        }

        function go(step) {
            activate(Math.min(Math.max(current + step, 0), cards.length - 1), true);
        }

        prev.onclick = () => go(-1);
        next.onclick = () => go(1);

        addEventListener("keydown", (e) => {
            if (["ArrowRight", "ArrowDown"].includes(e.key)) go(1);
            if (["ArrowLeft", "ArrowUp"].includes(e.key)) go(-1);
        }, { passive: true });

        cards.forEach((card, i) => {
            card.addEventListener("mouseenter", () => {
                if (matchMedia("(hover:hover)").matches) activate(i, true);
            });
            card.addEventListener("click", () => activate(i, true));
        });

        let sx = 0, sy = 0;
        track.addEventListener("touchstart", (e) => {
            sx = e.touches[0].clientX;
            sy = e.touches[0].clientY;
        }, { passive: true });

        track.addEventListener("touchend", (e) => {
            const dx = e.changedTouches[0].clientX - sx;
            const dy = e.changedTouches[0].clientY - sy;
            if (isMobile() ? Math.abs(dy) > 60 : Math.abs(dx) > 60) {
                go((isMobile() ? dy : dx) > 0 ? -1 : 1);
            }
        }, { passive: true });

        if (window.matchMedia("(max-width:767px)").matches) dotsBox.hidden = true;

        addEventListener("resize", () => center(current));

        toggleUI(0);
        center(0);
    }

  // --- 3. Lógica de FAQ ---
    const faqButtons = document.querySelectorAll('.faq-question');
    faqButtons.forEach(button => {
        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            faqButtons.forEach(btn => {
                btn.setAttribute('aria-expanded', 'false');
                btn.nextElementSibling.hidden = true;
            });
            if (!expanded) {
                button.setAttribute('aria-expanded', 'true');
                button.nextElementSibling.hidden = false;
            }
        });
    });

  // --- 5. Lógica de Botones de categoría en búsqueda ---
const botonesCategoria = document.querySelectorAll('.servicio-btn');
    botonesCategoria.forEach(btn => {
        btn.addEventListener('click', () => {
            const textoCategoria = btn.querySelector('span').textContent.trim();
            if (textoCategoria === "Ver Todos") {
                window.location.href = "/buscar";
            } else {
                window.location.href = "/buscar?categoria=" + encodeURIComponent(textoCategoria);
            }
        });
    });


  // --- 6. Lógica de la Campana de Notificaciones ---
    var campanaContainer = document.getElementById('campana-container');
    var notificacionesLista = document.getElementById('notificaciones-lista');
    var notificacionesItems = document.getElementById('notificaciones-items');

    var panelVisible = false;
    var notificacionesCargadas = false;

    // Función para cargar las notificaciones
    async function loadNotifications() {
        try {
            var response = await fetch('/notificaciones/check');
            
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }

            var data = await response.json(); // Parsea la respuesta JSON

            notificacionesItems.innerHTML = '';

            if (data.items && data.items.length > 0) {
                data.items.forEach(function(item) {
                    var itemHtml = `
                        <a href="${item.URL}">
                            <li>${item.Mensaje}</li>
                        </a>
                    `;
                    notificacionesItems.innerHTML += itemHtml;
                });
            } else {
                notificacionesItems.innerHTML = '<li>No tienes notificaciones.</li>';
            }
            
            notificacionesCargadas = true; // Marcamos como cargadas

        } catch (err) {
            console.error("Error al cargar notificaciones:", err);
            notificacionesItems.innerHTML = '<li>Error al cargar notificaciones.</li>';
        }
    }

    campanaContainer.addEventListener('click', function() {
        
        if (panelVisible) {
            notificacionesLista.style.display = 'none';
        } else {
            notificacionesLista.style.display = 'block';
        }
        panelVisible = !panelVisible;

        if (panelVisible && !notificacionesCargadas) {
            loadNotifications();
        }
    });

  
  // --- 7. Lógica de Categorías y Subcategorías (CON DEPURACIÓN) ---

  // Control 1: Ver si este bloque de código se ejecuta.
  console.log("Bloque de Categorías: Iniciado.");

  var categoriaSelect = document.getElementById('select-categoria');
  var subcategoriaSelect = document.getElementById('select-subcategoria');

  // Control 2: Ver si JavaScript encontró los elementos.
  console.log("Buscando #select-categoria:", categoriaSelect);
  console.log("Buscando #select-subcategoria:", subcategoriaSelect);

  // Comprueba si los selects existen en esta página
  if (categoriaSelect && subcategoriaSelect) { 
      
      // Control 3: Ver si el script entró en el 'if'.
      console.log("Elementos encontrados. Adjuntando 'change' listener...");

      categoriaSelect.addEventListener('change', function() {
          
          // Control 4: Ver si el 'listener' se dispara al cambiar.
          console.log("¡Categoría cambiada! El 'listener' funciona.");

          var categoriaId = this.value; 
          subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
          subcategoriaSelect.disabled = true;

          if (!categoriaId) {
              subcategoriaSelect.innerHTML = '<option value="">Selecciona una categoría primero</option>';
              return;
          }

          // Control 5: Ver si el 'fetch' se está preparando.
          console.log("Llamando a API: /api/subcategorias?categoria_id=" + categoriaId);

          fetch('/api/subcategorias?categoria_id=' + categoriaId)
              .then(function(response) {
                  if (!response.ok) {
                      throw new Error('Error en la respuesta de la red');
                  }
                  return response.json();
              })
              .then(function(subcategorias) {
                  subcategoriaSelect.innerHTML = '<option value="">Selecciona una subcategoría</option>';
                  subcategorias.forEach(function(subcat) {
                      var option = document.createElement('option');
                      option.value = subcat.ID_Subcategoria;
                      option.textContent = subcat.Nombre;
                      subcategoriaSelect.appendChild(option);
                  });
                  subcategoriaSelect.disabled = false;
              })
              .catch(function(error) {
                  console.error('Error al cargar subcategorías:', error);
                  subcategoriaSelect.innerHTML = '<option value="">Error al cargar</option>';
              });
      });
  } else {
      // Control 6: (El más probable) Ver si el 'if' falló.
      console.error("ERROR: No se encontraron los 'selects' (#select-categoria o #select-subcategoria) en esta página. El 'listener' no fue adjuntado.");
  }

}); // <-- FIN DEL DOMContentLoaded