<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
if (empty($_SESSION['reset_permitido'])) { header("Location: OlvideClave "); exit(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - Camaron Express</title>
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
            margin: 120px auto 40px auto; /* Espacio para que el navbar no lo pise */
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

        /* Icono decorativo de llave de seguridad */
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

        /* --- ESTRUCTURA DE CAMPOS --- */
        .form-group {
            margin-bottom: 25px;
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
            margin-top: 5px;
            transition: background-color 0.3s ease, transform 80ms ease;
        }

        .btn-reset:hover {
            background: var(--amarillo-hover);
        }

        .btn-reset:active {
            transform: scale(0.98);
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
           RESPONSIVE (PANTALLAS CELULARES)
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
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                </svg>
            </div>

            <h2>Establecer Nueva Contraseña</h2>
            <p class="subtitle">Ingresa tu nueva clave de seguridad para tu cuenta de EatsTech.</p>

            <form action="../modules/carrito/AccionClave " method="POST">
                <input type="hidden" name="action" value="actualizar_clave">
                
                <div class="form-group">
                    <label>Nueva Contraseña:</label>
                    <input type="password" name="nueva_clave" minlength="6" required placeholder="Mínimo 6 caracteres">
                </div>

                <button type="submit" class="btn-reset">Guardar Cambios</button>
            </form>

            <div class="recovery-footer">
                <a href="..\modules\usuarios\iniciodesesion " class="back-link">← Cancelar y volver</a>
            </div>
        </div>
    </div>
</body>
</html>