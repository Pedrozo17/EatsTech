// --- CONFIGURACIÓN DE SWIPER ---
var swiper = new Swiper(".mySwiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    loop: true,
    coverFlowEffect: {
        depth: 500,
        modifier: 1,
        slidesShadows: true,
        rotate: 0,
        stretch: 0
    }
});

// --- CONTROL DEL MENÚ DE HAMBURGUESA ---
document.addEventListener("DOMContentLoaded", function() {
    const menuBtn = document.getElementById("mobile-menu-btn");
    const navCollapse = document.getElementById("navbar-collapse-target");

    if (menuBtn && navCollapse) {
        menuBtn.addEventListener("click", function() {
            navCollapse.classList.toggle("show");
            menuBtn.classList.toggle("open");
        });
    }
});

// --- ANIMACIONES DE CARGA CON GSAP ---
document.body.classList.add("loading");

gsap.fromTo(".logo-name", { y: 50, opacity: 0 }, { y: 0, opacity: 1, duration: 2, delay: 0.5 });
gsap.fromTo("#svg", { scale: 0.5, opacity: 0 }, { scale: 1, opacity: 1, duration: 1.5, ease: "back.out(1.7)" });

gsap.fromTo(
    ".loading-page",
    { opacity: 1 },
    {
        opacity: 0,
        duration: 1.5,
        delay: 2.5,
        onComplete: () => {
            document.querySelector(".loading-page").style.display = "none";
            document.querySelector(".swiper").style.visibility = "visible";
            document.body.classList.remove("loading");
            document.body.classList.add("bloquear-scroll"); 
        }
    }
);

// --- NAVEGACIÓN DINÁMICA DEL MENÚ (DESBLOQUEO DE SCROLL) ---
document.addEventListener("DOMContentLoaded", function () {
    const contenedorTexto = document.getElementById('contenido-restaurante');
    const enlacesMenu = document.querySelectorAll('.nav-links a');

    function habilitarSecciones() {
        document.body.classList.remove("bloquear-scroll");
        if (contenedorTexto) {
            contenedorTexto.style.display = 'block';
            contenedorTexto.style.opacity = '1';
        }
    }

    function restaurarEstadoInicial() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => {
            document.body.classList.add("bloquear-scroll");
            if (contenedorTexto) {
                contenedorTexto.style.display = 'none';
                contenedorTexto.style.opacity = '0';
            }
        }, 500); 
    }

    enlacesMenu.forEach(enlace => {
        enlace.addEventListener('click', function (event) {
            const targetId = this.getAttribute('href');

            if (targetId === '#') {
                event.preventDefault();
                restaurarEstadoInicial();
                return;
            }

            if (targetId.startsWith('#')) {
                event.preventDefault();
                habilitarSecciones(); 

                const seccionDestino = document.querySelector(targetId);
                if (seccionDestino) {
                    setTimeout(() => {
                        seccionDestino.scrollIntoView({ behavior: 'smooth' });
                    }, 50);
                }
            }
        });
    });
});

// --- FORMULARIO ENVIAR A WHATSAPP ---
document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById('formulario-whatsapp');

    if (formulario) {
        formulario.addEventListener('submit', function (event) {
            event.preventDefault(); 

            const numeroTelefono = "573142756300"; // Tu número aquí

            const nombre = document.getElementById('whatsapp-nombre').value.trim();
            const correo = document.getElementById('whatsapp-correo').value.trim();
            const mensajeOriginal = document.getElementById('whatsapp-mensaje').value.trim();

            const textoMensaje = `*¡Hola, EatsTech! Nuevo mensaje de contacto*:%0A%0A` +
                                 `*👤 Nombre:* ${nombre}%0A` +
                                 `*✉️ Correo:* ${correo}%0A%0A` +
                                 `*💬 Mensaje:*%0A${mensajeOriginal}`;

            const urlWhatsApp = `https://api.whatsapp.com/send?phone=${numeroTelefono}&text=${textoMensaje}`;
            Swal.fire({
                title: '¡Redireccionando a WhatsApp!',
                text: 'Serás redirigido para enviar tu mensaje directamente a nuestro asesor.',
                icon: 'info',
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#FFB900',
                background: '#323232',
                color: '#FFFFFF'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Abre WhatsApp en pestaña nueva
                    window.open(urlWhatsApp, '_blank');
                    // Limpia el formulario por completo
                    formulario.reset();
                }
            });
        });
    }
});

// --- ALERTAS DE PRÓXIMAMENTE (SWEETALERT2) ---
document.addEventListener("DOMContentLoaded", function () {
    const botonesProximamente = document.querySelectorAll('.btn-proximamente');

    botonesProximamente.forEach(boton => {
        boton.addEventListener('click', function (event) {
            event.preventDefault(); 
            Swal.fire({
                title: '¡Muy pronto!',
                text: 'Aún no tenemos este restaurante disponible.',
                icon: 'info',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#FFB900',
                background: '#323232',
                color: '#FFFFFF',
                customClass: { popup: 'borde-redondeado-personalizado' },
                showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
                hideClass: { popup: 'animate__animated animate__fadeOutDown animate__faster' }
            });
        });
    });
});