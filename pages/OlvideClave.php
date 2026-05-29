<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Camaron Express</title>
    <link rel="stylesheet" href="../assets/css/style2.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
    
    <style>
        /* Variables Corporativas de EatsTech */
        :root {
            --white: #e9e9e9;
            --black: #323232;
            --amarillo: #FFB900;
            --amarillo-hover: #e0a800;
            --bg-card: #1a1a1a;
            --bg-input: #2a2a2a;
            --button-radius: 20px;
        }

        body {
            background-color: var(--black);
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            overflow-x: hidden;
            box-sizing: border-box;
        }

        /* --- CONTENEDOR FLOTANTE PREMIUM --- */
        .recovery-wrapper {
            width: 100%;
            max-width: 450px;
            margin: 120px auto 40px auto; /* Margen superior para que el navbar no lo tape */
            padding: 0 20px;
            box-sizing: border-box;
        }

        .form-container {
            background-color: var(--bg-card);
            border-radius: 1.5rem;
            box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.6);
            padding: 2.5rem;
            width: 100%;
            text-align: center;
            color: #fff;
            box-sizing: border-box;
            border: 1px solid #2a2a2a;
        }

        /* Icono decorativo de seguridad */
        .recovery-icon {
            width: 70px;
            height: 70px;
            background-color: rgba(255, 185, 0, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem auto;
        }

        .form-container h2 {
            color: var(--amarillo);
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 0 0.8rem 0;
        }

        .form-container .subtitle {
            color: #ccc;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        /* --- ESTRUCTURA DEL FORMULARIO --- */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--white);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            background: var(--bg-input);
            border: 1px solid #444;
            color: #fff;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--amarillo);
            box-shadow: 0 0 8px rgba(255, 185, 0, 0.25);
        }

        /* --- BOTÓN PRINCIPAL --- */
        .btn-reset {
            background: var(--amarillo);
            color: #000;
            padding: 14px;
            border: none;
            width: 100%;
            font-weight: bold;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            border-radius: var(--button-radius);
            margin-top: 10px;
            transition: background-color 0.3s ease, transform 80ms ease;
        }

        .btn-reset:hover {
            background: var(--amarillo-hover);
        }

        .btn-reset:active {
            transform: scale(0.98);
        }

        /* --- ALERTAS DE ERROR --- */
        .error-msg {
            background-color: rgba(255, 77, 77, 0.15);
            border: 1px solid #ff4d4d;
            color: #ff4d4d;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
        }

        /* --- ENLACE INFERIOR --- */
        .recovery-footer {
            margin-top: 1.5rem;
            border-top: 1px solid #2a2a2a;
            padding-top: 1.2rem;
        }

        .back-link {
            color: #888;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: var(--amarillo);
        }

        /* ==========================================================================
           RESPONSIVE DICTADO POR TU NAVBAR COMPACTO (PANTALLAS CELULARES)
           ========================================================================== */
        @media (max-width: 767px) {
            .recovery-wrapper {
                margin-top: 100px;
            }

            .form-container {
                padding: 1.8rem 1.2rem;
                border-radius: 1rem;
            }

            .form-container h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

    <div class="recovery-wrapper">
        <div class="form-container">
            
            <div class="recovery-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#FFB900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>

            <h2>¿Olvidaste tu contraseña?</h2>
            <p class="subtitle">Por seguridad, confirma tu correo y tu número de teléfono registrado para recibir el código.</p>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="error-msg">
                    <?php 
                        if($_GET['error'] == 'no_coincide') echo "⚠️ Los datos ingresados no coinciden con nuestros registros.";
                        if($_GET['error'] == 'db') echo "Error en el sistema. Inténtalo de nuevo.";
                    ?>
                </div>
            <?php endif; ?>

            <form action="../modules/carrito/AccionClave.php" method="POST" onsubmit="window.open('', 'enlace_whatsapp');">
                <input type="hidden" name="action" value="solicitar_codigo">
                
                <div class="form-group">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="correo" required placeholder="ejemplo@correo.com">
                </div>

                <div class="form-group">
                    <label>Número de Teléfono Celular:</label>
                    <input type="text" name="telefono" required placeholder="3248933841" maxlength="10" pattern="[0-9]+">
                </div>

                <button type="submit" class="btn-reset">Generar Código Seguro</button>
            </form>

            <div class="recovery-footer">
                <a href="../modules/usuarios/iniciodesesion.php" class="back-link">← Volver al inicio de sesión</a>
            </div>
        </div>
    </div>

</body>
</html>