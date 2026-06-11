<?php
// Asegúrate de iniciar la sesión si tu Configuración.php no lo hace automáticamente
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../config/Configuracion.php';

// 🟢 CORREGIDO: Evaluamos con tu variable nativa de inicio de sesión
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../../pages/index");
    exit;
}

// 🟢 CORREGIDO: Ajusta 'id_usuario' por el nombre exacto de la variable que creas al loguearte
// Si en tu login guardaste $_SESSION['id_usuario'], úsalo aquí:
$user_id = $_SESSION['id_usuario'] ?? $_SESSION['user_id'] ?? 0; 

// 1. CONSULTAR DATOS ACTUALES DEL USUARIO
$stmt = $db->prepare("SELECT name, email, telefono, direccion FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

// 2. CONSULTAR HISTORIAL DE PEDIDOS
$pedidos_query = $db->prepare("SELECT id, created, grand_total, status FROM orden WHERE customer_id = ? ORDER BY id DESC");
$pedidos_query->bind_param("i", $user_id);
$pedidos_query->execute();
$historial_pedidos = $pedidos_query->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Camaron Express</title>
    <link rel="stylesheet" href="../../assets/css/style2.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #141414; color: #FFF; font-family: 'DM Sans', sans-serif;">

    <div class="perfil-container" style="max-width: 900px; margin: 40px auto; padding: 20px;">
        <h2><i class="fa-solid fa-user" style="color: #FFB900;"></i> Mi Perfil</h2>
        
        <form action="ActualizarPerfil.php" method="POST" style="background: #242424; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3>Editar Datos Personales</h3>
            <div style="margin-bottom: 15px;">
                <label>Nombre Completo:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($usuario['name']); ?>" required style="width: 100%; padding: 10px; background: #141414; border: 1px solid #333; color: #FFF;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Dirección de Domicilio:</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?>" required style="width: 100%; padding: 10px; background: #141414; border: 1px solid #333; color: #FFF;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Nueva Contraseña (dejar en blanco para no cambiar):</label>
                <input type="password" name="contrasena" placeholder="********" style="width: 100%; padding: 10px; background: #141414; border: 1px solid #333; color: #FFF;">
            </div>
            <button type="submit" style="background: #FFB900; color: #141414; padding: 10px 20px; border: none; font-weight: bold; cursor: pointer;">Guardar Cambios</button>
        </form>

        <h3><i class="fa-solid fa-clock-rotate-left" style="color: #FFB900;"></i> Historial de mis Pedidos</h3>
        <table style="width: 100%; border-collapse: collapse; background: #242424; border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background: #333; color: #FFB900; text-align: left;">
                    <th style="padding: 12px;">ID Orden</th>
                    <th style="padding: 12px;">Fecha</th>
                    <th style="padding: 12px;">Total</th>
                    <th style="padding: 12px;">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($historial_pedidos->num_rows > 0): ?>
                    <?php while ($pedido = $historial_pedidos->fetch_assoc()): ?>
                        <tr style="border-bottom: 1px solid #333;">
                            <td style="padding: 12px;">#<?php echo $pedido['id']; ?></td>
                            <td style="padding: 12px;"><?php echo $pedido['created']; ?></td>
                            <td style="padding: 12px;">$<?php echo number_format($pedido['grand_total'], 0, ',', '.'); ?> COP</td>
                            <td style="padding: 12px;">
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: <?php echo $pedido['status'] == 'Pagado' ? '#1b5e20' : '#b71c1c'; ?>;">
                                    <?php echo htmlspecialchars($pedido['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center; color: #aaa;">Aún no has realizado pedidos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>