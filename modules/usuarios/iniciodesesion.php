<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/estilo2.css">
    <script defer src="../../assets/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../assets/images/logo.png">
    <title>Document</title>
</head>
<body>

    <div class="loading-page">
        <img id="svg" src="../../assets/images/logo.png" alt="Logo">
        <div class="name-container">
            <div class="logo-name">EATSTECH</div>
        </div>
    </div>

    <nav class="navbar">
        <div class="nav-container">
            <img src="../../assets/images/logo.png" alt="Logo" class="nav-logo">
            
            <button class="menu-toggle" id="mobile-menu-btn" aria-label="Abrir menú">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <div class="nav-collapse" id="navbar-collapse-target">
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Servicios</a></li>
                    <li><a href="#">Sobre Nosotros</a></li>
                    <li><a href="#">Contáctanos</a></li>
                </ul>
                <div class="nav-buttons">
                        <a href="../../pages/index.php" class="btn-login">Volver</a>
                </div>
            </div>
        </div>
    </nav>


    <div class="container right-panel-active">
        <!-- Sign Up -->
<div class="container__form container--signup">
    <h2 class="form__title">Sign up</h2>

    <?php if (isset($_GET['registro']) && $_GET['registro'] == 'exito'): ?>
    <div style="background: rgba(46, 204, 113, 0.15); border: 1px solid #2ecc71; color: #2ecc71; padding: 10px; margin-bottom: 15px; border-radius: 6px; text-align: center; font-size: 13px; font-family: sans-serif; font-weight: bold; width: 100%; box-sizing: border-box;">
        🎉 ¡Cuenta creada con éxito! Ingresa tu correo para seleccionar tu empresa.
    </div>
<?php endif; ?>
    
    <?php 
        if (isset($_GET['error'])): 
        // Definimos el mensaje según el error que venga en la URL
        $mensajeError = "";
        if ($_GET['error'] == 'duplicado') $mensajeError = "⚠️ El correo ya está registrado.";
        if ($_GET['error'] == 'password') $mensajeError = "❌ Las contraseñas no coinciden.";
        if ($_GET['error'] == 'vacio')    $mensajeError = "📝 Por favor, llene todos los campos.";
        if ($_GET['error'] == 'db')       $mensajeError = "⚙️ Error interno. Intente más tarde.";
        if ($_GET['error'] == 'no_existe') $mensajeError = "🔍 El correo no existe. ¡Regístrate aquí!";

        // SOLUCIÓN: Solo si encontramos un mensaje válido, dibujamos la caja en la pantalla
        if (!empty($mensajeError)): 
    ?>
        <div style="background: rgba(231, 76, 60, 0.15); border: 1px solid #e74c3c; color: #ff6b6b; padding: 10px; margin-bottom: 15px; border-radius: 6px; text-align: center; font-size: 13px; font-family: sans-serif; font-weight: bold; width: 100%; box-sizing: border-box;">
            <?php echo $mensajeError; ?>
        </div>
    <?php 
        endif; 
    endif; 
    ?>

    <form method="post" action="./registrar.php?redirect=<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : ''; ?>">
        <div class="role-selector">
    <label class="role-option">
        <input type="radio" name="tipo_usuario" value="persona" checked onclick="toggleRegistroEmpresa(false)">
        <span class="role-box">👤 Persona</span>
    </label>
    <label class="role-option">
        <input type="radio" name="tipo_usuario" value="empresa" onclick="toggleRegistroEmpresa(true)">
        <span class="role-box">🏢 Empresa</span>
    </label>
</div>

<div id="campos-empresa-container" style="display: none; width: 100%; margin-top: 15px;">
    <input type="text" name="nombre_restaurante" placeholder="Nombre de tu Restaurante/Negocio">
</div>
        <input class="input" type="text" name="nombre" placeholder="Nombre completo">
        <input class="input" type="correo" name="correo" placeholder="Correo">
        <input class="input" type="text" name="cedula" placeholder="Número de cédula">
        <input class="input" type="text" name="telefono" placeholder="Número de teléfono">
        <input class="input" type="text" name="direccion" placeholder="Dirección de envío">
        <input class="input" type="password" name="contraseña" placeholder="Contraseña">
        <input class="input" type="password" name="confirmar_contraseña" placeholder="Confirmar contraseña">
        <input class="btn" type="submit" name="register" value="Registrarse">
    </form>
</div>

<script>
function toggleRegistroEmpresa(show) {
    const container = document.getElementById('campos-empresa-container');
    container.style.display = show ? 'block' : 'none';
    
    // Si es persona, limpiamos y quitamos el 'required' para que no tranque el formulario
    const inputs = container.querySelectorAll('input');
    inputs.forEach(input => {
        if(input.type !== 'color') {
            input.required = show;
            if(!show) input.value = '';
        }
    });
}
</script>

<div>
    <div class="container__form container--signin">
        <h2 class="form__title">Sign In</h2>
        <form method="post" action="./login.php?redirect=<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : ''; ?>">
            
            <div class="role-selector">
                <label class="role-option">
                    <input type="radio" name="tipo_usuario" value="persona" checked onclick="toggleRestauranteSelector(false)">
                    <span class="role-box">👤 Persona</span>
                </label>
                <label class="role-option">
                    <input type="radio" name="tipo_usuario" value="empresa" onclick="toggleRestauranteSelector(true)">
                    <span class="role-box">🏢 Empresa</span>
                </label>
            </div>

            <input type="text" id="login_correo" name="correo" placeholder="correo" required>
            <input type="password" name="contraseña" placeholder="contraseña" required>

            <div id="restaurante-select-container" style="display: none; width: 100%; margin-bottom: 15px; text-align: left;">
                <label style="color: var(--davys-grey, #6f675d); font-size: 12px; display: block; margin-bottom: 5px; font-weight: bold; font-family: sans-serif;">
                    Selecciona el restaurante a gestionar:
                </label>
                <select name="restaurante_slug" id="restaurante_slug" style="width: 100%; padding: 12px; background: #efe6d3; border: 1px solid #6f675d; color: #2a241d; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    <option value="">Escribe tu correo primero...</option>
                </select>
            </div>

            <input type="submit" name="login">
            <a href="../../pages/OlvideClave.php" class="forgot-link">¿Olvidaste tu contraseña?</a>
            
            <?php if (isset($_GET['error']) && $_GET['error'] == 'no_existe'): ?>
            <div style="background: rgba(231, 76, 60, 0.15); border: 1px solid #e74c3c; color: #ff6b6b; padding: 10px; margin-top: 15px; margin-bottom: 15px; border-radius: 6px; text-align: center; font-size: 13px; font-family: sans-serif; font-weight: bold; width: 100%; box-sizing: border-box;">
                ⚠️ El correo no está registrado. ¡Regístrate gratis abajo!
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
function toggleRestauranteSelector(show) {
    // 1. Buscamos el contenedor y el select
    const container = document.getElementById('restaurante-select-container');
    const select = document.getElementById('restaurante_slug');
    
    if (!container || !select) return;
    
    // 2. Mostramos u ocultamos el contenedor
    container.style.display = show ? 'block' : 'none';
    
    if (show) {
        // SOLUCCIÓN ABSOLUTA: Buscamos el input name="correo" que esté dentro del mismo formulario
        const formulario = select.closest('form');
        const correoInput = formulario ? formulario.querySelector('input[name="correo"]') : null;
        const correo = correoInput ? correoInput.value.trim() : '';
        
        if (correo === '') {
            select.innerHTML = '<option value="">⚠️ Digita tu correo primero</option>';
            return;
        }

        select.innerHTML = '<option value="">⏳ Buscando restaurantes...</option>';

        // Petición AJAX al backend
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

// Evento en tiempo real: Escucha a TODOS los inputs de correo por si acaso
document.querySelectorAll('input[name="correo"]').forEach(input => {
    input.addEventListener('input', function() {
        const formulario = this.closest('form');
        if (!formulario) return;
        
        // Verificamos si en este formulario específico está marcado "empresa"
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
</script>
        <!-- Overlay -->
        <div class="container__overlay">
            <div class="overlay">
                <div class="overlay__panel overlay--left">
                    <button class="btn" id="signIn">Sign In</button>
                </div>
                <div class="overlay__panel overlay--right">
                    <button class="btn" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    

    <script>
                document.body.classList.add("loading");

        gsap.fromTo(
            ".logo-name",
            { y: 50, opacity: 0 },
            { y: 0, opacity: 1, duration: 2, delay: 0.5 }
        );

        gsap.fromTo(
            "#svg",
            { scale: 0.5, opacity: 0 },
            { scale: 1, opacity: 1, duration: 1.5, ease: "back.out(1.7)" }
        );

        gsap.fromTo(
            ".loading-page",
            { opacity: 1 },
            {
                opacity: 0,
                duration: 1.0,
                delay: 2.0,
                onComplete: () => {
                    // Cuando termina la animación, oculta la pantalla
                    // y muestra el contenido
                    document.querySelector(".loading-page").style.display = "none";
                    document.querySelector(".swiper").style.visibility = "visible";
                    document.body.classList.remove("loading");
                }
            }
        );

    // 🪄 Animación UX Automática al fallar el Login por cuenta inexistente
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error') === 'no_existe') {
        const container = document.querySelector(".container");
        if (container) {
            // Activa el panel de registro de una forma fluida
            container.classList.add("right-panel-active");
        }
    }


        // --- CONTROL DEL MENÚ DE HAMBURGUESA ---
        document.addEventListener("DOMContentLoaded", function() {
            const menuBtn = document.getElementById("mobile-menu-btn");
            const navCollapse = document.getElementById("navbar-collapse-target");

            menuBtn.addEventListener("click", function() {
                navCollapse.classList.toggle("show");
                menuBtn.classList.toggle("open");
            });
        });


    </script>
</body>
</html>