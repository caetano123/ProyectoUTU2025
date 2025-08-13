// ==============================
// Dropdown toggle
// ==============================

function toggleDropdown(id) {
  const content = document.getElementById(id);

  if (!content) {
    console.warn(`No existe el elemento con id "${id}"`);
    return;
  }

  // Cerrar todos menos el actual
  document.querySelectorAll('.dropdown-content').forEach(div => {
    if (div !== content) {
      div.classList.remove('active');
    }
  });

  // Alternar la clase 'active' del dropdown actual
  content.classList.toggle('active');
}

// Cerrar dropdowns al hacer click fuera
document.addEventListener('click', function(event) {
  if (!event.target.closest('.dropdown')) {
    document.querySelectorAll('.dropdown-content.active').forEach(div => {
      div.classList.remove('active');
    });
  }
});

// ==============================
// DOMContentLoaded: Todo lo que depende del DOM cargado
// ==============================

document.addEventListener('DOMContentLoaded', () => {

  // ==============================
  // Carrusel con scroll centrado
  // ==============================

  const track = document.getElementById("track");
  if (track) {
    const wrap = track.parentElement;
    const cards = Array.from(track.children);
    const prev = document.getElementById("prev");
    const next = document.getElementById("next");
    const dotsBox = document.getElementById("dots");

    const isMobile = () => matchMedia("(max-width:767px)").matches;

    cards.forEach((_, i) => {
      const dot = document.createElement("span");
      dot.className = "dot";
      dot.onclick = () => activate(i, true);
      dotsBox.appendChild(dot);
    });

    const dots = Array.from(dotsBox.children);
    let current = 0;

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


  // ==============================
  // Preguntas Frecuentes (FAQ toggle)
  // ==============================

  const faqButtons = document.querySelectorAll('.faq-question');

  faqButtons.forEach(button => {
    button.addEventListener('click', () => {
      const expanded = button.getAttribute('aria-expanded') === 'true';

      // Cerrar todos
      faqButtons.forEach(btn => {
        btn.setAttribute('aria-expanded', 'false');
        btn.nextElementSibling.hidden = true;
      });

      // Abrir el actual si estaba cerrado
      if (!expanded) {
        button.setAttribute('aria-expanded', 'true');
        button.nextElementSibling.hidden = false;
      }
    });
  });

  // ==============================
  // Mostrar nombre de archivo subido
  // ==============================

  const input = document.getElementById('imagen');
  const fileName = document.getElementById('file-name');

  if (input && fileName) {
    input.addEventListener('change', function () {
      fileName.textContent = this.files[0] ? this.files[0].name : 'Ningún archivo seleccionado';
    });
  }

});



// ==============================
// API PHP - Gestión de Posts
// ==============================

// Referencias a los elementos del DOM
const btnEnviarPost = document.getElementById('fetchPostData');
const inputPost = document.getElementById('postInput');
const divResultado = document.getElementById('postResult');

// --- Event Listener ---
btnEnviarPost.addEventListener('click', () => {
    const dato = inputPost.value.trim();
    if (dato) {
        apiEnviarPost(dato);
    } else {
        divResultado.innerHTML = '<span>Ingresa un dato válido</span>';
    }
});

// --- Función para enviar datos al API ---
async function apiEnviarPost(dato) {
    divResultado.textContent = 'Enviando datos POST...';
    try {
        const formData = new FormData();
        formData.append('miDato', dato);

        const response = await fetch('/apicategorias', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Error en la petición POST: ${response.status} ${response.statusText}`);
        }

        let datosJson;
        try {
            datosJson = await response.json();
        } catch (e) {
            throw new Error("La respuesta del servidor no es JSON válido");
        }

        // Validar que data sea un array
        if (!datosJson.data || !Array.isArray(datosJson.data) || datosJson.data.length === 0) {
            divResultado.innerHTML = `<span>${datosJson.message || 'No se encontraron posts para esta categoría'}</span>`;
            return;
        }

        // Si el primer elemento tiene "Mensaje", mostrarlo
        if (datosJson.data[0].Mensaje) {
            divResultado.innerHTML = `<span>${datosJson.data[0].Mensaje}</span>`;
            return;
        }

        // Mostrar la lista de posts
        mostrarListaPosts(datosJson.data);

    } catch (error) {
        console.error('Error al enviar datos:', error);
        divResultado.innerHTML = `<span class="error-message">Error: ${error.message}</span>`;
    }
}

// --- Función para mostrar la lista de posts ---
function mostrarListaPosts(posts) {
    divResultado.innerHTML = ''; // limpiar contenedor

    const ul = document.createElement('ul');

    posts.forEach(post => {
        const titulo = post.Titulo || 'Sin título';
        const contenido = post.Contenido || 'Sin contenido';
        const autor = post.Usuario || 'Desconocido';
        const categoria = post.Categoria || 'Desconocida';

        const li = document.createElement('li');
        li.innerHTML = `<strong>${titulo}</strong> - ${contenido} (Autor: ${autor}, Categoría: ${categoria})`;
        ul.appendChild(li);
    });

    divResultado.appendChild(ul);
}
