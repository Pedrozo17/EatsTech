<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

include_once '../modules/usuarios/control_plan.php';

// SEGURIDAD: Si no es empresa, pa' fuera
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: ../modules/usuarios/iniciodesesion");
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

// 1. Sumar las ventas reales de la tabla orden
$queryVentas = $db->query("SELECT SUM(total_price) as total_finanzas FROM orden WHERE status = 'Pagado'");
$rowVentas = $queryVentas->fetch_assoc();
$total_ventas = $rowVentas['total_finanzas'] ?? 0;

// 2. Tráfico comercial: Total de órdenes históricas
$queryVisitas = $db->query("SELECT COUNT(*) as total_ordenes FROM orden");
$rowVisitas = $queryVisitas->fetch_assoc();
$total_visitas = $rowVisitas['total_ordenes'] ?? 0;

// =================================================================
// 🍔 TRUCO ALGORÍTMICO: EXTRAER EL PLATO MÁS VENDIDO DEL RESUMEN
// =================================================================

// Traemos todos los productos existentes en tu menú para buscar coincidencias exactas
$queryMenu = $db->query("SELECT name FROM mis_productos");
$todos_los_platos = [];
while ($platoMenu = $queryMenu->fetch_assoc()) {
    $todos_los_platos[] = $platoMenu['name'];
}

// Traemos todos los textos de los resúmenes de los pedidos exitosos
$queryResumenes = $db->query("SELECT resumen_productos FROM pedidos_registrados WHERE estado != 'Pendiente'");
$conteo_platos = array_fill_keys($todos_los_platos, 0); // Inicializamos el contador de cada plato en 0

while ($pedido = $queryResumenes->fetch_assoc()) {
    $texto_resumen = $pedido['resumen_productos'];
    
    // Buscamos si el nombre de cada plato del menú aparece dentro del texto del pedido
    foreach ($todos_los_platos as $plato) {
        if (!empty($texto_resumen) && stripos($texto_resumen, $plato) !== false) {
            // Si el plato aparece en el texto, le sumamos una unidad vendida
            $conteo_platos[$plato]++;
        }
    }
}

// Ordenamos los platos de mayor a menor venta y tomamos los 4 principales
arsort($conteo_platos);
$top_platos = array_slice($conteo_platos, 0, 4, true);

// Preparamos los arreglos limpios que se enviarán a Chart.js
$platos_nombres = array_keys($top_platos);
$platos_cantidades = array_values($top_platos);

// Si ningún plato ha sido vendido aún, ponemos valores por defecto para que la gráfica no salga vacía
if (array_sum($platos_cantidades) === 0) {
    $platos_nombres = ["Sin ventas aún"];
    $platos_cantidades = [1];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - EatsTech</title>
    <link rel="shortcut icon" href="../../assets/images/logo_empresa-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/estiloADM.css">
    <!-- Cargamos Chart.js solo si estamos en la sección de estadísticas -->
    <?php if ($seccion === 'estadisticas'): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>
</head>
<body>

    <nav class="navbar">
        <img src="../assets/images/logo_empresa-removebg-preview.png" alt="Logo" class="nav-logo">
        <a href="../modules/usuarios/logout" class="btn-logout">Cerrar Sesión</a>
    </nav>

    <div class="dashboard-container">
        
        <div class="submenu-admin">
            <a href="./admin_dashboard?seccion=pedidos" class="<?php echo $seccion === 'pedidos' ? 'active' : ''; ?>">📦 Pedidos Registrados</a>
            <a href="./admin_dashboard?seccion=productos" class="<?php echo $seccion === 'productos' ? 'active' : ''; ?>">🍔 Menú de Productos</a>
            <a href="./admin_dashboard?seccion=ordenes" class="<?php echo $seccion === 'ordenes' ? 'active' : ''; ?>">🛒 Órdenes en Curso</a>
            <!-- NUEVO BOTÓN SOLICITADO -->
            <a href="./admin_dashboard?seccion=estadisticas" class="<?php echo $seccion === 'estadisticas' ? 'active' : ''; ?>">📊 Estadísticas de mi Restaurante</a>
        </div>

        <!-- SECCIÓN: PEDIDOS -->
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
                                        <option value="Entregado" <?php echo ($estado_actual == 'Entregado') ? 'selected' : ''; ?>>📦 Entregado</option>
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

        <!-- SECCIÓN: PRODUCTOS -->
        <?php if ($seccion === 'productos'): 
            $res = $db->query("SELECT * FROM mis_productos ORDER BY id ASC");
        ?>
        <div class="panel-box">
            <div class="panel-header">
                <h2>Gestión del Menú (Platos)</h2>
                <a href="form_producto" class="btn-action-top">➕ Nuevo Producto</a>
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
                                    <a href="form_producto?id=<?php echo $row['id']; ?>" class="btn-edit-premium">Editar</a>
                                    <a href="crud_operaciones?action=delete_prod&id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('¿Quitar este plato del menú?');">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- SECCIÓN: ÓRDENES -->
        <?php if ($seccion === 'ordenes'): 
            $res = $db->query("SELECT o.*, u.nombre AS nombre_cliente 
                               FROM orden o 
                               INNER JOIN datos u ON o.customer_id = u.id 
                               ORDER BY o.id DESC");
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
                            <th>Cliente</th>
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
                                <strong><?php echo htmlspecialchars($row['nombre_cliente'] ?? 'Cliente Desconocido'); ?></strong>
                                <br>
                                <span style="background: rgba(0,0,0,0.05); padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 11px; color: #666;">
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

        <!-- NUEVA SECCIÓN: ESTADÍSTICAS DEL RESTAURANTE -->
        <?php if ($seccion === 'estadisticas'): ?>
            <div class="panel-box">
                <div class="panel-header">
                    <h2>Rendimiento Comercial y Analíticas</h2>
                </div>
                
                <?php if (isset($dataRestaurante['tiene_estadisticas']) && $dataRestaurante['tiene_estadisticas'] == true): ?>
                    
                    <div class="metrics-grid">
                        <div class="metric-card" style="border-left-color: #28a745;">
                            <h3>Suma Total Ventas</h3>
                            <div class="value">$<?php echo number_format($total_ventas, 0, ',', '.'); ?> COP</div>
                        </div>

                        <div class="metric-card" style="border-left-color: #17a2b8;">
                            <h3>Tráfico / Visitas</h3>
                            <div class="value"><?php echo number_format($total_visitas, 0, ',', '.'); ?> Usuarios</div>
                        </div>
                    </div>

                    <div class="charts-grid">
                        <div class="chart-card">
                            <h3 style="font-family: sans-serif; color: #333;">📊 Distribución del Plato Más Vendido</h3>
                            <div class="chart-container">
                                <canvas id="chartPlatosMasVendidos"></canvas>
                            </div>
                        </div>
                        <div class="chart-card" style="justify-content: center; text-align: center; font-family: sans-serif;">
                            <h4 style="color: #28a745; margin: 0;">Soporte Premium Activo</h4>
                            <p style="font-size: 13px; color: #555;">Canal directo con la mesa de servicio de EatsTech habilitado.</p>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="blocked-module">
                        <h3>🔒 Módulo de Analíticas Bloqueado</h3>
                        <p>Las analíticas de ventas financieras y gráficos avanzados están reservados únicamente para clientes de planes Premium.</p>
                        <a href="cambiar_plan" class="btn-upgrade">Adquirir Plan Premium</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

    <!-- TARJETA QR DEL MENÚ -->
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

    <?php if ($seccion === 'estadisticas' && isset($dataRestaurante['tiene_estadisticas']) && $dataRestaurante['tiene_estadisticas'] == true): ?>
    <script>
        const nombresPlatos = <?php echo json_encode($platos_nombres); ?>;
        const cantidadesPlatos = <?php echo json_encode($platos_cantidades); ?>;
    </script>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
</body>
</html>