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
// EVENTOS AUTOMÁTICOS Y COMPORTAMIENTO UX (SE DISPARA AL CARGAR EL DOM)
// ==========================================================================
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. VALIDACIÓN EN TIEMPO REAL: SÓLO NÚMEROS EN CÉDULA Y TELÉFONO
    const inputCedula = document.getElementById('registro-cedula');
    const inputTelefono = document.getElementById('registro-telefono');

    if (inputCedula) {
        inputCedula.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    if (inputTelefono) {
        inputTelefono.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // 2. Escuchar cambios en los inputs de correo en tiempo real (Para empresas)
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

    // 3. Control del Menú Hamburguesa
    const menuBtn = document.getElementById("mobile-menu-btn");
    const navCollapse = document.getElementById("navbar-collapse-target");
    if (menuBtn && navCollapse) {
        menuBtn.addEventListener("click", function() {
            navCollapse.classList.toggle("show");
            menuBtn.classList.toggle("open");
        });
    }

    // 4. Manejo de Paneles Deslizantes (Sign In / Sign Up)
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container-wrapper');

    if (signUpButton && signInButton && container) {
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });
        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    }

    // 5. CAPTURA DE ERRORES DE LOGIN CON SWEETALERT2
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('error');

    if (errorParam) {
        // 🔒 CASO 1: Contraseña incorrecta (?error=1)
        if (errorParam === '1') {
            Swal.fire({
                title: 'Contraseña Incorrecta',
                text: 'La contraseña que ingresaste no coincide con nuestros registros. Inténtalo de nuevo.',
                icon: 'error',
                confirmButtonText: 'Corregir',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF',
                customClass: { popup: 'borde-redondeado-personalizado' }
            });
        }
        
        // 🔍 CASO 2: El correo no existe en la Base de Datos (?error=no_existe)
        else if (errorParam === 'no_existe') {
            Swal.fire({
                title: 'Usuario no encontrado',
                text: 'El correo electrónico ingresado no está registrado en EatsTech. Verifica tu escritura o crea una cuenta nueva.',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF',
                customClass: { popup: 'borde-redondeado-personalizado' }
            });

            if (container) {
                container.classList.add("right-panel-active");
            }
        }

                else if (errorParam === 'duplicado') {
            Swal.fire({
                title: 'Usuario ya registrado',
                text: 'El correo electrónico ingresado ya está registrado en EatsTech. Inicia sesión.',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF',
                customClass: { popup: 'borde-redondeado-personalizado' }
            });

            if (container) {
                container.classList.add("right-panel-active");
            }
        }

        // 🛡️ CASO 3: El rol no coincide (?error=rol_incorrecto)
        else if (errorParam === 'rol_incorrecto') {
            Swal.fire({
                title: 'Error de Acceso',
                text: 'El tipo de cuenta seleccionado (Persona/Empresa) no coincide con el perfil de este correo electrónico.',
                icon: 'error',
                confirmButtonText: 'Verificar Rol',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF',
                customClass: { popup: 'borde-redondeado-personalizado' }
            });
        }
        
        // 💳 CASO 4: Intento de pago sin iniciar sesión (?error=auth)
        else if (errorParam === 'auth') {
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
    }
}); // <-- Llave y paréntesis que cierran correctamente el DOMContentLoaded

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