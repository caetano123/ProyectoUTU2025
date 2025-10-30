
// DOMContentLoaded

document.addEventListener('DOMContentLoaded', () => {

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
      // Evita que el clic en el botón cierre el menú inmediatamente
      event.stopPropagation(); 
      
      const menuId = trigger.getAttribute('aria-controls');
      const menu = document.getElementById(menuId);

      if (menu) {
        // Verifica si este menú ya está abierto
        const isExpanded = menu.classList.contains('show');
        
        // Cierra todos los menús ANTES de abrir el nuevo
        closeAllDropdowns();

        // Si no estaba abierto, ábrelo
        if (!isExpanded) {
          menu.classList.add('show');
          trigger.setAttribute('aria-expanded', 'true');
        }
      }
    });
  });

  // Cierra los menús si se hace clic fuera de ellos
  window.addEventListener('click', (event) => {
    if (!event.target.matches('button[aria-haspopup="true"]')) {
      closeAllDropdowns();
    }
  });
});
   
    // Carrusel con scroll centrado
   
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

  
    // FAQ toggle
   
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

   
    // Mostrar nombre archivo subido
   
    const input = document.getElementById('imagen');
    const fileName = document.getElementById('file-name');
    if (input && fileName) {
        input.addEventListener('change', function () {
            fileName.textContent = this.files[0] ? this.files[0].name : 'Ningún archivo seleccionado';
        });
    }

  
    // Botones de categoría en búsqueda
    
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

    // CAMPANA DE NOTIFICACIONES 
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