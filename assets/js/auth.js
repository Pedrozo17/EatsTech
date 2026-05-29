
// ==========================================================================
// CONTROL DE FORMULARIOS DINÁMICOS (SELECTOR DE ROLES)
// ==========================================================================

function toggleRegistroEmpresa(show) {
    const container = document.getElementById('campos-empresa-container');
    if (!container) return;
    
    container.style.display = show ? 'block' : 'none';
    
    const inputs = container.querySelectorAll('input');
    inputs.forEach(input => {
        if(input.type !== 'color') {
            input.required = show;
            if(!show) input.value = '';
        }
    });
}

const inputCedula = document.getElementById('registro-cedula');
    const inputTelefono = document.getElementById('registro-telefono');

    if (inputCedula) {
        inputCedula.addEventListener('input', function() {
            // Reemplaza cualquier carácter que NO sea un número por un vacío ''
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    if (inputTelefono) {
        inputTelefono.addEventListener('input', function() {
            // Reemplaza cualquier carácter que NO sea un número por un vacío ''
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

function toggleRestauranteSelector(show) {
    const container = document.getElementById('restaurante-select-container');
    const select = document.getElementById('restaurante_slug');
    
    if (!container || !select) return;
    
    container.style.display = show ? 'block' : 'none';
    
    if (show) {
        const formulario = select.closest('form');
        const correoInput = formulario ? formulario.querySelector('input[name="correo"]') : null;
        const correo = correoInput ? correoInput.value.trim() : '';
        
        if (correo === '') {
            select.innerHTML = '<option value="">⚠️ Digita tu correo primero</option>';
            return;
        }

        select.innerHTML = '<option value="">⏳ Buscando restaurantes...</option>';

        fetch(`buscar_restaurantes.php?correo=${encodeURIComponent(correo)}`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = '';
                if (!data || data.length === 0) {
                    select.innerHTML = '<option value="">No tienes restaurantes asignados</option>';
                } else {
                    data.forEach(rest => {
                        select.innerHTML += `<option value="${rest.slug_carpeta}">${rest.nombre_restaurante}</option>`;
                    });
                }
            })
            .catch(err => {
                console.error("Error en Fetch:", err);
                select.innerHTML = '<option value="">Error al conectar con el servidor</option>';
            });
    }
}

// ==========================================================================
// EVENTOS AUTOMÁTICOS Y COMPORTAMIENTO UX
// ==========================================================================
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Escuchar cambios en los inputs de correo en tiempo real (Para empresas)
    document.querySelectorAll('input[name="correo"]').forEach(input => {
        input.addEventListener('input', function() {
            const formulario = this.closest('form');
            if (!formulario) return;
            
            const radioEmpresa = formulario.querySelector('input[name="tipo_usuario"]:checked');
            if (radioEmpresa && radioEmpresa.value === 'empresa') {
                const select = formulario.querySelector('#restaurante_slug');
                const correo = this.value.trim();
                
                if (select && correo !== '') {
                    fetch(`buscar_restaurantes.php?correo=${encodeURIComponent(correo)}`)
                        .then(res => res.json())
                        .then(data => {
                            select.innerHTML = '';
                            if (!data || data.length === 0) {
                                select.innerHTML = '<option value="">No tienes restaurantes asignados</option>';
                            } else {
                                data.forEach(rest => {
                                    select.innerHTML += `<option value="${rest.slug_carpeta}">${rest.nombre_restaurante}</option>`;
                                });
                            }
                        });
                }
            }
        });
    });

    // 2. Control del Menú Hamburguesa
    const menuBtn = document.getElementById("mobile-menu-btn");
    const navCollapse = document.getElementById("navbar-collapse-target");
    if (menuBtn && navCollapse) {
        menuBtn.addEventListener("click", function() {
            navCollapse.classList.toggle("show");
            menuBtn.classList.toggle("open");
        });
    }

    // 3. Manejo de Paneles Deslizantes (Sign In / Sign Up)
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container-wrapper'); // Se asignó id al contenedor común

    if (signUpButton && signInButton && container) {
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });
        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    }

    // 4. Animación UX al fallar Login por cuenta inexistente o redirección forzada
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error') === 'no_existe' && container) {
        container.classList.add("right-panel-active");
    }

    // 5. Integración SweetAlert2 para Alerta de Autenticación en Pasarela de Pagos
    if (urlParams.get('error') === 'auth') {
        Swal.fire({
            title: '¡Paso necesario!',
            text: 'Inicia sesión para poder procesar tu pedido y proceder con el pago.',
            icon: 'info',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#FFB900',
            background: '#242424',
            color: '#FFFFFF',
            customClass: { popup: 'borde-redondeado-personalizado' }
        });
    }
});

// ==========================================================================
// ANIMACIONES DE CARGA INICIAL (GSAP)
// ==========================================================================
document.body.classList.add("loading");

gsap.fromTo(".logo-name", { y: 50, opacity: 0 }, { y: 0, opacity: 1, duration: 2, delay: 0.5 });
gsap.fromTo("#svg", { scale: 0.5, opacity: 0 }, { scale: 1, opacity: 1, duration: 1.5, ease: "back.out(1.7)" });

gsap.fromTo(".loading-page", { opacity: 1 }, {
    opacity: 0,
    duration: 1.0,
    delay: 2.0,
    onComplete: () => {
        document.querySelector(".loading-page").style.display = "none";
        const swiperContainer = document.querySelector(".swiper");
        if(swiperContainer) swiperContainer.style.visibility = "visible";
        document.body.classList.remove("loading");
    }
});