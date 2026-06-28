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

function consultarRestaurantesServidor(correo, selectElement) {
    const modalAviso = document.getElementById("modal-restaurante-bloqueado");
    
    if (correo === '') {
        selectElement.innerHTML = '<option value="">⚠️ Digita tu correo primero</option>';
        return;
    }

    selectElement.innerHTML = '<option value="">⏳ Buscando restaurantes...</option>';

    fetch(`buscar_restaurantes.php?correo=${encodeURIComponent(correo)}`)
        .then(res => {
            if (!res.ok) throw new Error(`Error HTTP: ${res.status}`);
            return res.text();
        })
        .then(textoCompleto => {
            try {
                const data = JSON.parse(textoCompleto);
                
                selectElement.innerHTML = '';
                if (!data || data.length === 0) {
                    selectElement.innerHTML = '<option value="ninguno">No tienes restaurantes asignados</option>';
                } else {
                    // 🟢 CORRECCIÓN: Si encontramos restaurantes válidos, nos aseguramos de ocultar el modal de bloqueo
                    if (modalAviso) modalAviso.classList.remove("active");

                    data.forEach(rest => {
                        selectElement.innerHTML += `<option value="${rest.id}">${rest.nombre_restaurante}</option>`;
                    });
                }
            } catch (err) {
                console.error("❌ El servidor no envió un JSON válido. Respuesta recibida:");
                console.error(textoCompleto); 
                selectElement.innerHTML = '<option value="error">Error interno de PHP (Ver Consola F12)</option>';
            }
        })
        .catch(err => {
            console.error("Error de Red/Ruta:", err);
            selectElement.innerHTML = '<option value="error">Error de ruta o red</option>';
        });
}

function toggleRestauranteSelector(show) {
    const container = document.getElementById('restaurante-select-container');
    const select = document.getElementById('restaurante_slug');
    const modalAviso = document.getElementById("modal-restaurante-bloqueado");
    
    if (!container || !select) return;
    
    container.style.display = show ? 'block' : 'none';
    
    if (show) {
        const formulario = select.closest('form');
        const correoInput = formulario ? formulario.querySelector('input[name="correo"]') : null;
        const correo = correoInput ? correoInput.value.trim() : '';
        consultarRestaurantesServidor(correo, select);
    } else {
        // 🟢 Si cambia a rol Persona, ocultamos inmediatamente cualquier modal residual en móvil
        if (modalAviso) modalAviso.classList.remove("active");
    }
}

// ==========================================================================
// EVENTOS AUTOMÁTICOS Y COMPORTAMIENTO UX (AL CARGAR EL DOM)
// ==========================================================================
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. VALIDACIÓN EN TIEMPO REAL: SÓLO NÚMEROS EN CÉDULA Y TELÉFONO
    const inputCedula = document.getElementById('registro-cedula');
    const inputTelefono = document.getElementById('registro-telefono');
    const inputCodigo = document.getElementById('codigo-verificacion');

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

    if (inputCodigo) {
        inputCodigo.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // 2. ESCUCHAR CAMBIOS EN LOS INPUTS DE CORREO EN TIEMPO REAL
    document.querySelectorAll('input[name="correo"]').forEach(input => {
        input.addEventListener('input', function() {
            const formulario = this.closest('form');
            if (!formulario || formulario.id !== 'formulario-login') return;
            
            const radioEmpresa = formulario.querySelector('input[name="tipo_usuario"]:checked');
            if (radioEmpresa && radioEmpresa.value === 'empresa') {
                const select = formulario.querySelector('#restaurante_slug');
                if (select) {
                    const correo = this.value.trim();
                    consultarRestaurantesServidor(correo, select);
                }
            }
        });
    });

    // 3. INTERCEPTOR DEL ENVÍO DE FORMULARIO (VALIDA EMPRESAS NO DISPONIBLES O INEXISTENTES)
    const formularioLogin = document.getElementById("formulario-login");
    const selectRestaurante = document.getElementById("restaurante_slug");
    const radioEmpresa = document.getElementById("role-empresa");
    const modalAviso = document.getElementById("modal-restaurante-bloqueado");
    const btnCerrarAviso = document.getElementById("btn-cerrar-aviso");

    if (formularioLogin) {
        formularioLogin.addEventListener("submit", function(e) {
            if (radioEmpresa && radioEmpresa.checked) {
                const opcionSeleccionada = selectRestaurante.options[selectRestaurante.selectedIndex];
                const textoSeleccionado = opcionSeleccionada ? opcionSeleccionada.text.toLowerCase() : '';
                const valorSeleccionado = opcionSeleccionada ? opcionSeleccionada.value : '';

                // CASO A: Si el correo no tiene empresas asociadas o el campo está vacío/con error
                if (valorSeleccionado === '' || valorSeleccionado === 'ninguno' || valorSeleccionado === 'error' || textoSeleccionado.includes('digita tu correo')) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Acceso Denegado',
                        text: 'Este correo electrónico no tiene ningún restaurante o empresa registrada en nuestra plataforma.',
                        icon: 'error',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#FFB900',
                        background: '#242424',
                        color: '#FFFFFF'
                    });
                    return;
                }

                // CASO B: Si la empresa existe pero NO es Camaron Express (Despliega aviso modal corporativo)
                // 💡 NOTA: Si estás probando otros restaurantes en tu entorno local, puedes comentar temporalmente estas líneas
                if (!textoSeleccionado.includes('camaron express') && !textoSeleccionado.includes('camaron-express')) {
                    e.preventDefault(); 
                    if (modalAviso) modalAviso.classList.add("active"); 
                }
            }
        });
    }

    if (btnCerrarAviso && modalAviso) {
        btnCerrarAviso.addEventListener("click", function() {
            modalAviso.classList.remove("active");
        });
    }

    if (modalAviso) {
        modalAviso.addEventListener("click", function(e) {
            if (e.target === modalAviso) {
                modalAviso.classList.remove("active");
            }
        });
    }

    // 4. CONTROL DEL MENÚ HAMBURGUESA
    const menuBtn = document.getElementById("mobile-menu-btn");
    const navCollapse = document.getElementById("navbar-collapse-target");
    if (menuBtn && navCollapse) {
        menuBtn.addEventListener("click", function() {
            navCollapse.classList.toggle("show");
            menuBtn.classList.toggle("open");
        });
    }

    // 5. MANEJO DE PANELES DESLIZANTES (SIGN IN / SIGN UP)
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

    // 6. CONTROLADORES DE ALERTAS GLOBALES DE URL (SWEETALERT2)
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('error');

    if (errorParam) {
        if (errorParam === '1') {
            Swal.fire({
                title: 'Contraseña Incorrecta',
                text: 'La contraseña que ingresaste no coincide con nuestros registros. Inténtalo de nuevo.',
                icon: 'error',
                confirmButtonText: 'Corregir',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF'
            });
        }
        else if (errorParam === 'no_existe') {
            Swal.fire({
                title: 'Usuario no encontrado',
                text: 'El correo electrónico ingresado no está registrado en EatsTech. Verifica tu escritura o crea una cuenta nueva.',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF'
            });
            if (container) container.classList.add("right-panel-active");
        }
        else if (errorParam === 'duplicado') {
            Swal.fire({
                title: 'Usuario ya registrado',
                text: 'El correo electrónico ingresado ya está registrado en EatsTech. Inicia sesión.',
                icon: 'warning',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF'
            });
            if (container) container.classList.add("right-panel-active");
        }
        else if (errorParam === 'rol_incorrecto') {
            Swal.fire({
                title: 'Error de Acceso',
                text: 'El tipo de cuenta seleccionado (Persona/Empresa) no coincide con el perfil de este correo electrónico.',
                icon: 'error',
                confirmButtonText: 'Verificar Rol',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF'
            });
        }
        else if (errorParam === 'auth') {
            Swal.fire({
                title: '¡Paso necesario!',
                text: 'Inicia sesión para poder procesar tu pedido y proceder con el pago.',
                icon: 'info',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#FFB900',
                background: '#242424',
                color: '#FFFFFF'
            });
        }
    }
}); 

// ==========================================================================
// ANIMACIONES DE CARGA INICIAL (GSAP)
// ==========================================================================
document.body.classList.add("loading");

if (document.querySelector(".logo-name")) {
    gsap.fromTo(".logo-name", { y: 50, opacity: 0 }, { y: 0, opacity: 1, duration: 2, delay: 0.5 });
}
if (document.getElementById("svg")) {
    gsap.fromTo("#svg", { scale: 0.5, opacity: 0 }, { scale: 1, opacity: 1, duration: 1.5, ease: "back.out(1.7)" });
}

gsap.fromTo(".loading-page", { opacity: 1 }, {
    opacity: 0,
    duration: 1.0,
    delay: 2.0,
    onComplete: () => {
        const loadPage = document.querySelector(".loading-page");
        if (loadPage) loadPage.style.display = "none";
        const swiperContainer = document.querySelector(".swiper");
        if(swiperContainer) swiperContainer.style.visibility = "visible";
        document.body.classList.remove("loading");
    }
});