<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
if (empty($_SESSION['reset_correo'])) { header("Location: OlvideClave.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código - Camaron Express</title>
    <link rel="stylesheet" href="\Eatstech\assets\css\style2.css">
    <style>
        .form-container { max-width: 400px; margin: 80px auto; padding: 30px; background: #111; border: 1px solid #d4af37; border-radius: 8px; text-align: center; color: #fff; }
        .form-group input { width: 100%; padding: 12px; background: #222; border: 1px solid #444; color: #fff; text-align: center; font-size: 20px; letter-spacing: 5px; border-radius: 4px; }
        .btn-reset { background: #d4af37; color: #000; padding: 12px; border: none; width: 100%; font-weight: bold; cursor: pointer; border-radius: 4px; margin-top: 15px; }
        .error-msg { color: #ff4d4d; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Verificar Código</h2>
        
        <p style="color: #aaa; font-size: 14px; margin-bottom: 20px;">Hemos enviado un código de verificación a tu WhatsApp registrado. Por favor, digítalo a continuación para continuar.</p>

        <?php if(isset($_GET['error'])): ?>
            <div class="error-msg">
                <?php 
                    if($_GET['error'] == 'incorrecto') echo "El código ingresado es incorrecto.";
                    if($_GET['error'] == 'expirado') echo "El código ha expirado (Límite 15 min).";
                ?>
            </div>
        <?php endif; ?>

        <form action="../modules/carrito/AccionClave.php" method="POST">
            <input type="hidden" name="action" value="verificar_codigo">
            <div class="form-group">
                <input type="text" name="codigo" maxlength="6" required placeholder="000000" autocomplete="off">
            </div>
            <button type="submit" class="btn-reset">Validar Código</button>
        </form>
    </div>
</body>
</html>