<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - Camaron Express</title>
    <link rel="stylesheet" href="\Eatstech\assets\css\style2.css">
    <style>
        .form-container { max-width: 400px; margin: 80px auto; padding: 30px; background: #111; border: 1px solid #d4af37; border-radius: 8px; text-align: center; color: #fff; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group input { width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; }
        .btn-reset { background: #d4af37; color: #000; padding: 12px; border: none; width: 100%; font-weight: bold; cursor: pointer; border-radius: 4px; }
        .error-msg { color: #ff4d4d; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>¿Olvidaste tu contraseña?</h2>
        <p style="color: #aaa; font-size: 14px; margin-bottom: 20px;">Ingresa tu correo electrónico y te enviaremos el código por WhatsApp.</p>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="error-msg">
                <?php 
                    if($_GET['error'] == 'no_existe') echo "El correo electrónico no está registrado.";
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
            <button type="submit" class="btn-reset">Generar Código</button>
        </form>
    </div>
</body>
</html>