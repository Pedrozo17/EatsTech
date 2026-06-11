<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../config/Configuracion.php';

if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../../pages/index");
    exit;
}

$user_id = $_SESSION['id_usuario'] ?? 0; 
$user_email = $_SESSION['correo'] ?? ''; 

// 1. CONSULTAR DATOS DEL USUARIO
$stmt = $db->prepare("SELECT nombre, correo, telefono, direccion FROM datos WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

// 2. PESTAÑA 1: PEDIDOS REGISTRADOS (🟢 ÚNICA CON RESUMEN DE PRODUCTOS)
$sql_pedidos = "SELECT pr.id, pr.fecha_registro AS created, pr.total_pagar AS total_price, pr.estado AS status,
                COALESCE((
                    SELECT r.nombre_restaurante 
                    FROM orden_articulos oa 
                    INNER JOIN mis_productos mp ON oa.product_id = mp.id 
                    INNER JOIN restaurantes r ON mp.id = r.id 
                    WHERE oa.order_id = pr.id LIMIT 1
                ), 'Camaron Express') AS restaurante,
                (
                    SELECT GROUP_CONCAT(CONCAT(oa.quantity, 'x ', mp.name) SEPARATOR ', ')
                    FROM orden_articulos oa
                    INNER JOIN mis_productos mp ON oa.product_id = mp.id
                    WHERE oa.order_id = pr.id
                ) AS resumen_productos
                FROM pedidos_registrados pr
                WHERE pr.correo_cliente = ? 
                ORDER BY pr.id DESC";

$pedidos_query = $db->prepare($sql_pedidos);
$pedidos_query->bind_param("s", $user_email); 
$pedidos_query->execute();
$historial_pedidos = $pedidos_query->get_result();

// 3. PESTAÑA 2: ÓRDENES EN TIEMPO REAL 
// (🟢 SOLUCIÓN FINAL: Jalamos de 'orden' usando 'customer_id' y mapeamos correctamente el negocio)
$sql_ordenes = "SELECT o.id, o.total_price, o.metodo_pago, o.created, o.status,
                COALESCE((
                    SELECT r.nombre_restaurante 
                    FROM orden_articulos oa 
                    INNER JOIN mis_productos mp ON oa.product_id = mp.id 
                    INNER JOIN restaurantes r ON mp.id = r.id 
                    LIMIT 1
                ), 'Camaron Express') AS restaurante
                FROM orden o
                WHERE o.customer_id = ? 
                ORDER BY o.id DESC";

$ordenes_query = $db->prepare($sql_ordenes);
$ordenes_query->bind_param("i", $user_id);
$ordenes_query->execute();
$monitoreo_ordenes = $ordenes_query->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Camaron Express</title>
    <link rel="stylesheet" href="../../assets/css/style2.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
</head>
<body style="background-color: #141414; color: #FFF; font-family: 'DM Sans', sans-serif;">

    <div class="perfil-container" style="max-width: 900px; margin: 40px auto; padding: 20px;">
        <h2><i class="fa-solid fa-user" style="color: #FFB900;"></i> Mi Perfil</h2>
        
        <form action="ActualizarPerfil.php" method="POST" style="background: #242424; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3>Editar Datos Personales</h3>
            <div style="margin-bottom: 15px;">
                <label>Nombre Completo:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required style="width: 100%; padding: 10px; background: #141414; border: 1px solid #333; color: #FFF;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Dirección de Domicilio:</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?>" required style="width: 100%; padding: 10px; background: #141414; border: 1px solid #333; color: #FFF;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Nueva Contraseña (dejar en blanco para no cambiar):</label>
                <input type="password" name="contrasena" placeholder="********" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número." style="width: 100%; padding: 10px; background: #141414; border: 1px solid #333; color: #FFF;">
            </div>
            <button type="submit" style="background: #FFB900; color: #141414; padding: 10px 20px; border: none; font-weight: bold; cursor: pointer;">Guardar Cambios</button>
        </form>

        <div class="tabs-container" style="margin-top: 40px; width: 100%;">
            
            <div class="tabs-header" style="display: flex; flex-direction: row; gap: 15px; margin-bottom: 25px; border-bottom: 2px solid #333; padding-bottom: 10px;">
                <button class="tab-btn active" onclick="switchTab(event, 'pedidos-registrados')">
                    📦 Pedidos Registrados
                </button>
                <button class="tab-btn" onclick="switchTab(event, 'ordenes-curso')">
                    🛒 Órdenes en Curso / Monitoreo
                </button>
            </div>

            <div id="pedidos-registrados" class="tab-content active-content">
                <h3 style="margin-bottom: 15px; font-size: 22px; font-weight: bold;"><i class="fa-solid fa-box" style="color: #FFB900;"></i> Historial de Pedidos Registrados</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; background: #242424; border-radius: 8px; overflow: hidden;">
                        <thead>
                            <tr style="background: #333; color: #FFB900; text-align: left;">
                                <th style="padding: 14px 12px;">ID Orden</th>
                                <th style="padding: 14px 12px;">Restaurante</th>
                                <th style="padding: 14px 12px;">Productos</th> <!-- Nueva columna añadida -->
                                <th style="padding: 14px 12px;">Fecha Registro</th>
                                <th style="padding: 14px 12px;">Total Pagado</th>
                                <th style="padding: 14px 12px;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($historial_pedidos->num_rows > 0): ?>
                                <?php while ($pedido = $historial_pedidos->fetch_assoc()): ?>
                                    <tr style="border-bottom: 1px solid #333; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#2d2d2d'" onmouseout="this.style.backgroundColor='transparent'">
                                        <td style="padding: 14px 12px;">#<?php echo $pedido['id']; ?></td>
                                        <td style="padding: 14px 12px; font-weight: bold; color: #dca872;"><?php echo htmlspecialchars($pedido['restaurante']); ?></td>

                                        <!-- Celda del Resumen con control de texto largo para proteger el diseño gráfico -->
                                        <!-- Celda del Resumen Corregida: Texto fluido que baja de renglón si es muy largo -->
                                        <td style="padding: 14px 12px; color: #eee; font-size: 13px; max-width: 300px; word-wrap: break-word; tr-text-wrap: pretty; line-height: 1.4;">
                                            <i class="fa-solid fa-utensils" style="color: #FFB900; font-size: 11px; margin-right: 5px;"></i>
                                            <?php echo htmlspecialchars($pedido['resumen_productos'] ?? 'Sin especificar'); ?>
                                        </td>

                                        <td style="padding: 14px 12px; color: #ccc;"><?php echo $pedido['created']; ?></td>
                                        <td style="padding: 14px 12px; color: #FFB900; font-weight: bold;">$<?php echo number_format($pedido['total_price'], 0, ',', '.'); ?> COP</td>
                                        <td style="padding: 14px 12px;">
                                            <span style="padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; background: <?php echo $pedido['status'] == 'Pagado' ? '#1b5e20' : '#7f5f00'; ?>; color: #FFF;">
                                                <?php echo htmlspecialchars($pedido['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="padding: 30px; text-align: center; color: #aaa;">Aún no registras pedidos en el sistema.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="ordenes-curso" class="tab-content">
                <h3 style="margin-bottom: 15px; font-size: 22px; font-weight: bold;"><i class="fa-solid fa-clock" style="color: #FFB900;"></i> Monitoreo de Órdenes en Tiempo Real</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; background: #242424; border-radius: 8px; overflow: hidden;">
                        <thead>
                            <tr style="background: #333; color: #FFB900; text-align: left;">
                                <th style="padding: 14px 12px;">ID Orden</th>
                                <th style="padding: 14px 12px;">Restaurante</th>
                                <th style="padding: 14px 12px;">Total de la Orden</th>
                                <th style="padding: 14px 12px;">Método de Pago</th>
                                <th style="padding: 14px 12px;">Fecha Entrada</th>
                                <th style="padding: 14px 12px;">Estado Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($monitoreo_ordenes->num_rows > 0): ?>
                                <?php while ($orden = $monitoreo_ordenes->fetch_assoc()): ?>
                                    <tr style="border-bottom: 1px solid #333; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#2d2d2d'" onmouseout="this.style.backgroundColor='transparent'">
                                        <td style="padding: 14px 12px;">#<?php echo $orden['id']; ?></td>
                                        <td style="padding: 14px 12px; font-weight: bold; color: #dca872;"><?php echo htmlspecialchars($orden['restaurante']); ?></td>
                                        <td style="padding: 14px 12px; color: #FFB900; font-weight: bold;">$<?php echo number_format($orden['total_price'], 0, ',', '.'); ?> COP</td>
                                        <td style="padding: 14px 12px; color: #ccc; text-transform: capitalize;"><i class="fa-solid fa-wallet" style="margin-right: 5px; color: #FFB900;"></i> <?php echo htmlspecialchars($orden['metodo_pago'] ?? 'Efectivo'); ?></td>
                                        <td style="padding: 14px 12px; color: #ccc;"><?php echo $orden['created']; ?></td>
                                        <td style="padding: 14px 12px;">
                                            <span style="padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; background: <?php echo $orden['status'] == 'Pagado' ? '#1b5e20' : '#7f5f00'; ?>; color: #FFF;">
                                                ⏳ <?php echo htmlspecialchars($orden['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="padding: 30px; text-align: center; color: #aaa;">No tienes órdenes activas bajo monitoreo.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <style>
        .tab-btn {
            background-color: #242424;
            color: #ccc;
            padding: 12px 28px;
            border: 1px solid #333;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            border-radius: 6px 6px 0 0;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .tab-btn.active {
            background-color: #c4935c;
            color: #141414;
            border-color: #c4935c;
        }

        .tab-btn:hover {
            background-color: #c4935c;
            color: #141414;
            border-color: #c4935c;
        }

        .tab-content {
            display: none;
            animation: smoothFade 0.4s ease;
        }

        .active-content {
            display: block;
        }

        @keyframes smoothFade {
            from { opacity: 0; transform: translateY(3px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        function switchTab(evt, tabId) {
            const tabContents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active-content");
            }

            const tabButtons = document.getElementsByClassName("tab-btn");
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove("active");
            }

            document.getElementById(tabId).classList.add("active-content");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html> 