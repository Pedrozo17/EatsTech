<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/estilo2.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
    <title>EatsTech - Iniciar Sesión / Registro</title>
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
                    <li><a href="../../pages/index">Home</a></li>
                    <li><a href="../../pages/index#servicios">Servicios</a></li>
                    <li><a href="../../pages/index#sobre-nosotros">Sobre Nosotros</a></li>
                    <li><a href="../../pages/index#contactanos">Contáctanos</a></li>
                </ul>
                <div class="nav-buttons">
                    <a href="../../pages/index" class="btn-login">Volver</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container right-panel-active" id="container-wrapper">
        
        <div class="container__form container--signup">
            <h2 class="form__title">Registrate</h2>

            <?php if (isset($_GET['registro']) && $_GET['registro'] == 'exito'): ?>
                <div style="background: rgba(46, 204, 113, 0.15); border: 1px solid #2ecc71; color: #2ecc71; padding: 10px; margin-bottom: 15px; border-radius: 6px; text-align: center; font-size: 13px; font-family: sans-serif; font-weight: bold; width: 100%; box-sizing: border-box;">
                    🎉 ¡Cuenta creada con éxito! Ingresa tu correo para seleccionar tu empresa.
                </div>
            <?php endif; ?>
            
            <?php 
            if (isset($_GET['error'])): 
                $mensajeError = "";
                if ($_GET['error'] == 'duplicado') $mensajeError = "⚠️ El correo ya está registrado.";
                if ($_GET['error'] == 'terminos') $mensajeError = "⚖️ Debe aceptar los Términos y Condiciones para registrarse.";
                if ($_GET['error'] == 'password') $mensajeError = "❌ Las contraseñas no coinciden.";
                if ($_GET['error'] == 'vacio')    $mensajeError = "📝 Por favor, llene todos los campos.";
                if ($_GET['error'] == 'db')       $mensajeError = "⚙️ Error interno. Intente más tarde.";
                if ($_GET['error'] == 'no_existe') $mensajeError = "🔍 El correo no existe. ¡Regístrate aquí!";

                if (!empty($mensajeError)): 
            ?>
                <div style="background: rgba(231, 76, 60, 0.15); border: 1px solid #e74c3c; color: #ff6b6b; padding: 10px; margin-bottom: 15px; border-radius: 6px; text-align: center; font-size: 13px; font-family: sans-serif; font-weight: bold; width: 100%; box-sizing: border-box;">
                    <?php echo $mensajeError; ?>
                </div>
            <?php 
                endif; 
            endif; 
            ?>

            <form method="post" action="./registrar?redirect=<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : ''; ?>">
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
                <input class="input" type="text" name="nombre" placeholder="Nombre completo" required>
                <input class="input" type="email" name="correo" placeholder="Correo" required>
                <input class="input" type="tel" id="registro-cedula" name="cedula" placeholder="Número de cédula" maxlength="10" required>
                <input class="input" type="tel" id="registro-telefono" name="telefono" placeholder="Número de teléfono" maxlength="10" required>
                <input class="input" type="text" name="direccion" placeholder="Dirección de envío" required>
                <input class="input" type="password" name="contraseña" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número." placeholder="Contraseña" required>
                <input class="input" type="password" name="confirmar_contraseña" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número." placeholder="Confirmar contraseña" required>

                <div class="terms-container" style="display: flex; align-items: flex-start; gap: 8px; margin: 15px 0; text-align: left; width: 100%; box-sizing: border-box;">
                    <input type="checkbox" name="terminos" id="terminos" required style="margin-top: 4px; cursor: pointer;">
                    <label for="terminos" style="font-family: sans-serif; font-size: 12px; color: #666; line-height: 1.4; cursor: pointer;">
                        Acepto los <a href="./terminos-y-condiciones" target="_blank" style="color: #3498db; text-decoration: none; font-weight: bold;">Términos y Condiciones</a> y la política de tratamiento de datos.
                    </label>
                </div>

                <input class="btn" type="submit" name="register" value="Registrarse">
            </form>
        </div>

        <div class="container__form container--signin">
            <h2 class="form__title">Inicia sesion</h2>
            <form method="post" action="./login?redirect=<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : ''; ?>">
                
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

                <input type="submit" name="login" value="Entrar">
                <a href="../../pages/OlvideClave" class="forgot-link">¿Olvidaste tu contraseña?</a>
                
                <?php if (isset($_GET['error']) && $_GET['error'] == 'no_existe'): ?>
                    <div style="background: rgba(231, 76, 60, 0.15); border: 1px solid #e74c3c; color: #ff6b6b; padding: 10px; margin-top: 15px; margin-bottom: 15px; border-radius: 6px; text-align: center; font-size: 13px; font-family: sans-serif; font-weight: bold; width: 100%; box-sizing: border-box;">
                        ⚠️ El correo no está registrado. ¡Regístrate gratis abajo!
                    </div>
                <?php endif; ?>
            </form>
        </div>

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/auth.js"></script>
</body>
</html>