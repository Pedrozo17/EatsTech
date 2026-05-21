<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
if (empty($_SESSION['reset_permitido'])) { header("Location: OlvideClave.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Contraseña - Camaron Express</title>
    <link rel="stylesheet" href="\Eatstech\assets\css\style2.css">
    <style>
        .form-container { max-width: 400px; margin: 80px auto; padding: 30px; background: #111; border: 1px solid #d4af37; border-radius: 8px; text-align: center; color: #fff; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group input { width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: #fff; border-radius: 4px; box-sizing: border-box; }
        .btn-reset { background: #d4af37; color: #000; padding: 12px; border: none; width: 100%; font-weight: bold; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Establecer Nueva Contraseña</h2>
        <p style="color: #aaa; font-size: 14px; margin-bottom: 20px;">Ingresa tu nueva clave de seguridad para tu cuenta.</p>

        <form action="../modules/carrito/AccionClave.php" method="POST">
            <input type="hidden" name="action" value="actualizar_clave">
            <div class="form-group">
                <label>Nueva Contraseña:</label>
                <input type="password" name="nueva_clave" required placeholder="Mínimo 6 caracteres">
            </div>
            <button type="submit" class="btn-reset">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>