<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// SEGURIDAD: Si no es empresa, pa' fuera
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: ../modules/usuarios/iniciodesesion.php");
    exit();
}

// 1. Apuntamos al nombre exacto de tu archivo
$ruta_conexion = "../config/Configuracion.php"; 

if (!file_exists($ruta_conexion)) {
    die("❌ ERROR CRÍTICO: No se encontró el archivo en: " . realpath($ruta_conexion));
}

include($ruta_conexion);

// 2. Verificamos tu variable real que es $db
if (!isset($db)) {
    die("❌ ERROR: El archivo cargó, pero la variable '$db' no está definida.");
}

// Detectar sección activa
$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'pedidos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - EatsTech</title>
    <link rel="shortcut icon" href="../../assets/images/logo_empresa-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/estiloADM.css">
</head>
<body>z

    <nav class="navbar">
        <img src="../assets/images/logo_empresa-removebg-preview.png" alt="Logo" class="nav-logo">
        <a href="../modules/usuarios/logout.php" class="btn-logout">Cerrar Sesión</a>
    </nav>

    <div class="dashboard-container">
        
        <div class="submenu-admin">
            <a href="./admin_dashboard.php?seccion=pedidos" class="<?php echo $seccion === 'pedidos' ? 'active' : ''; ?>">📦 Pedidos Registrados</a>
            <a href="./admin_dashboard.php?seccion=productos" class="<?php echo $seccion === 'productos' ? 'active' : ''; ?>">🍔 Menú de Productos</a>
            <a href="./admin_dashboard.php?seccion=ordenes" class="<?php echo $seccion === 'ordenes' ? 'active' : ''; ?>">🛒 Órdenes en Curso</a>
        </div>

        <?php if ($seccion === 'pedidos'): 
            $res = $db->query("SELECT * FROM pedidos_registrados ORDER BY id DESC");
        ?>
        <div class="panel-box">
            <div class="panel-header">
                <h2>Historial de Pedidos Registrados</h2>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Dirección de Envío</th>
                            <th>Resumen de Productos</th>
                            <th>Total Pagado</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $res->fetch_assoc()): 
                            $estado_actual = isset($row['estado']) ? $row['estado'] : 'Pagado';
                        ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nombre_cliente']); ?></strong><br><small style="color: #888;"><?php echo htmlspecialchars($row['correo_cliente']); ?></small></td>
                            <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                            <td style="font-size: 13px; color: #3b3128; max-width: 300px; white-space: pre-line;"><?php echo htmlspecialchars($row['resumen_productos']); ?></td>
                            <td style="color: var(--amarillo); font-weight: bold; font-size: 15px;">
                                $<?php echo number_format($row['total_pagar'], 0, ',', '.'); ?> COP
                            </td>
                            <td><?php echo $row['fecha_registro']; ?></td>
                            <td>
                                <div class="status-select-container">
                                    <select class="status-select" data-id="<?php echo $row['id']; ?>" data-tabla="pedidos_registrados" onchange="cambiarEstadoFila(this)">
                                        <option value="Pendiente" <?php echo ($estado_actual == 'Pendiente') ? 'selected' : ''; ?>>⏳ Pendiente</option>
                                        <option value="En Cocina" <?php echo ($estado_actual == 'En Cocina') ? 'selected' : ''; ?>>🍳 En Cocina</option>
                                        <option value="En Camino" <?php echo ($estado_actual == 'En Camino') ? 'selected' : ''; ?>>🛵 En Camino</option>
                                        <option value="Pagado" <?php echo ($estado_actual == 'Pagado') ? 'selected' : ''; ?>>✅ Pagado</option>
                                        <option value="Cancelado" <?php echo ($estado_actual == 'Cancelado') ? 'selected' : ''; ?>>❌ Cancelado</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($seccion === 'productos'): 
            $res = $db->query("SELECT * FROM mis_productos ORDER BY id ASC");
        ?>
        <div class="panel-box">
            <div class="panel-header">
                <h2>Gestión del Menú (Platos)</h2>
                <a href="form_producto.php" class="btn-action-top">➕ Nuevo Producto</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre del Plato</th>
                            <th>Descripción</th>
                            <th>Precio Base</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['description'] ?? 'Sin descripción'); ?></td>
                            <td style="color: var(--amarillo); font-weight: bold;">
                                $<?php echo number_format($row['price'], 0, ',', '.'); ?> COP
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="form_producto.php?id=<?php echo $row['id']; ?>" class="btn-edit-premium">Editar</a>
                                    <a href="crud_operaciones.php?action=delete_prod&id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('¿Quitar este plato del menú?');">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($seccion === 'ordenes'): 
            $res = $db->query("SELECT * FROM orden ORDER BY id DESC");
        ?>
        <div class="panel-box">
            <div class="panel-header">
                <h2>Monitoreo de Órdenes en Tiempo Real</h2>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID Orden</th>
                            <th>ID Cliente</th>
                            <th>Total de la Orden</th>
                            <th>Método de Pago</th>
                            <th>Fecha Entrada</th>
                            <th>Estado Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <span style="background: rgba(0,0,0,0.05); padding: 4px 8px; border-radius: 5px; font-family: monospace;">
                                    User ID: <?php echo $row['customer_id']; ?>
                                </span>
                            </td>
                            <td style="color: var(--amarillo); font-weight: bold; font-size: 15px;">
                                $<?php echo number_format($row['total_price'], 0, ',', '.'); ?> COP
                            </td>
                            <td style="text-transform: capitalize; color: #5a4f44;">
                                💳 <?php echo htmlspecialchars($row['metodo_pago']); ?>
                            </td>
                            <td><?php echo $row['created']; ?></td>
                            <td>
                                <div class="status-select-container">
                                    <select class="status-select" data-id="<?php echo $row['id']; ?>" data-tabla="orden" onchange="cambiarEstadoFila(this)">
                                        <option value="Pendiente" <?php echo ($row['status'] == 'Pendiente') ? 'selected' : ''; ?>>⏳ Pendiente</option>
                                        <option value="En Cocina" <?php echo ($row['status'] == 'En Cocina') ? 'selected' : ''; ?>>🍳 En Cocina</option>
                                        <option value="En Camino" <?php echo ($row['status'] == 'En Camino') ? 'selected' : ''; ?>>🛵 En Camino</option>
                                        <option value="Pagado" <?php echo ($row['status'] == 'Pagado') ? 'selected' : ''; ?>>✅ Pagado</option>
                                        <option value="Cancelado" <?php echo ($row['status'] == 'Cancelado') ? 'selected' : ''; ?>>❌ Cancelado</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <div class="card-qr" style="background: #242424; padding: 25px; border-radius: 12px; text-align: center; border: 1px solid #333; max-width: 300px; margin: 20px auto;">
        <h3 style="color: #FFFFFF; font-family: sans-serif; font-size: 18px; margin-bottom: 10px;">📋 Tu Menú Digital</h3>
        <p style="color: #8a8a8a; font-size: 13px; margin-bottom: 20px;">Coloca este código en tus mesas físicas para que los clientes escaneen el menú.</p>
    
    <div id="contenedor-qr" 
     data-slug="<?php echo (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'empresa') ? strtolower($_SESSION['nombre']) : 'index'; ?>" 
     style="display: inline-block; padding: 15px; background: white; border-radius: 8px;">
    </div>
    
    <div style="margin-top: 15px;">
        <button onclick="window.print();" style="background: #FFB900; color: #141414; border: none; padding: 10px 15px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 13px;">
            🖨️ Imprimir QR
        </button>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script src="../assets/js/admin.js"></script>
</body>
</html>