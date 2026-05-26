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
    <style>

    :root {
    /* ==========================================================================
       PALETA DE COLORES CORPORATIVOS - CAMARON EXPRESS / EATSTECH VINTAGE
       ========================================================================== */
    --primary-brown: #cf9465;
    --primary-cream: #ECE2C6;
    --primary-mint: #9FD5D1;
    --davys-grey: #6f675d;
    
    /* CONTROL DE CONTRASTE ESTRICTO */
    --text-main: #2a241d;          
    --text-body: #3b3128;          
    --text-muted: #5a4f44;         
    --dark-navbar: #2a241d;        
    --pure-white: #ffffff;         
    
    /* Color dinámico desde la BD mapeado a tu color principal */
    --amarillo: <?php echo isset($color_restaurante) ? $color_restaurante : '#cf9465'; ?>; 
    --amarillo-hover: #b88258;     
    
    --bg-main: #f7f2e8;            
    --bg-card: #efe6d3;            
    --bg-input: #e4d7be;           
    
    --danger: #d9534f;
    --success: #1e8449;            
}

body {
    background-color: var(--bg-main);
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: var(--text-body);
}

/* --- NAVBAR ESTILO PREMIUM FLOATING --- */
.navbar {
    background-color: var(--dark-navbar);
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 1200px;
    border-radius: 30px;
    padding: 10px 20px;
    box-sizing: border-box;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
    border: 1px solid var(--davys-grey);
    box-shadow: 0 10px 30px rgba(42, 36, 29, 0.15);
}

.nav-logo { 
    height: 40px; 
}

.btn-logout {
    background-color: var(--primary-cream);
    color: var(--dark-navbar);
    padding: 8px 18px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: bold;
    font-size: 13px;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background-color: var(--amarillo);
    color: var(--pure-white);
    box-shadow: 0 4px 12px rgba(207, 148, 101, 0.3);
}

.dashboard-container {
    width: 100%;
    max-width: 1200px;
    margin: 140px auto 40px auto;
    padding: 0 20px;
    box-sizing: border-box;
}

/* --- SUB-NAVBAR INTERNO (CONTROL DE PESTAÑAS) --- */
.submenu-admin {
    display: flex;
    background-color: var(--bg-card);
    padding: 8px;
    border-radius: 15px;
    margin-bottom: 30px;
    border: 1px solid var(--bg-input);
    gap: 10px;
}

.submenu-admin a {
    flex: 1;
    text-align: center;
    padding: 12px;
    color: var(--text-body); 
    text-decoration: none;
    font-weight: 800;        
    font-size: 14px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.submenu-admin a:hover { 
    color: var(--dark-navbar); 
    background-color: var(--bg-input); 
}

.submenu-admin a.active {
    background-color: var(--amarillo);
    color: var(--pure-white); 
    box-shadow: 0 4px 15px rgba(207, 148, 101, 0.25);
}

/* --- CONTENEDORES DE LAS TABLAS --- */
.panel-box {
    background-color: var(--bg-card);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(42, 36, 29, 0.04);
    border: 1px solid var(--bg-input);
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.panel-header h2 {
    color: var(--text-main);
    font-weight: 800;
    margin: 0;
    font-size: 1.6rem;
}

.btn-action-top {
    background: var(--amarillo);
    color: var(--pure-white);
    padding: 10px 20px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: bold;
    font-size: 13px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(207, 148, 101, 0.2);
}

.btn-action-top:hover { 
    background: var(--amarillo-hover); 
}

/* --- TABLAS RESPONSIVAS --- */
.table-responsive { 
    width: 100%; 
    overflow-x: auto; 
}

table { 
    width: 100%; 
    border-collapse: collapse; 
    text-align: left; 
    font-size: 14px; 
}

th { 
    background-color: var(--bg-input); 
    color: var(--text-main);  
    padding: 15px; 
    border-bottom: 2px solid var(--text-main); 
    font-weight: 800;         
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
}

td { 
    padding: 18px 15px;       
    border-bottom: 1px solid var(--bg-input); 
    color: var(--text-body); 
    vertical-align: middle;      
}

tr:hover td { 
    background-color: rgba(207, 148, 101, 0.05); 
}

/* --- BOTONES DE ACCIÓN (CRUD PRODUCTOS NUEVOS) --- */
.actions-cell {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-edit-premium {
    display: inline-block;
    color: var(--pure-white);
    background-color: var(--text-main);
    border: 1px solid var(--text-main);
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: bold;
    text-decoration: none;
    text-transform: uppercase;
    transition: all 0.2s ease;
}

.btn-edit-premium:hover {
    background-color: var(--amarillo);
    border-color: var(--amarillo);
}

/* --- SELECTORES DE ESTADO EN TIEMPO REAL --- */
.status-select-container {
    position: relative;
    display: inline-block;
}

.status-select {
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: bold;
    cursor: pointer;
    border: 1px solid var(--davys-grey);
    background-color: var(--bg-main);
    color: var(--text-main);
    transition: all 0.2s ease;
    outline: none;
}

/* Colores visuales automáticos según el estado seleccionado */
.status-select[data-status="Pagado"], .status-select[data-status="3"] { color: var(--success); border-color: var(--success); background: rgba(30, 132, 73, 0.08); }
.status-select[data-status="Pendiente"], .status-select[data-status="1"] { color: #a05c2c; border-color: #a05c2c; background: rgba(207, 148, 101, 0.08); }
.status-select[data-status="En Cocina"] { color: #3498db; border-color: #3498db; background: rgba(52, 152, 219, 0.08); }
.status-select[data-status="En Camino"], .status-select[data-status="2"] { color: #e67e22; border-color: #e67e22; background: rgba(230, 126, 34, 0.08); }
.status-select[data-status="Cancelado"] { color: var(--danger); border-color: var(--danger); background: rgba(217, 83, 79, 0.08); }

@media (max-width: 767px) {
    .submenu-admin { flex-direction: column; }
    .navbar { width: 95%; }
}
    </style>
</head>
<body>

    <nav class="navbar">
        <img src="../assets/images/logo.png" alt="Logo" class="nav-logo">
        <a href="../modules/usuarios/iniciodesesion.php" class="btn-logout">Cerrar Sesión</a>
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

    <script>
    function cambiarEstadoFila(selectElement) {
    const id = selectElement.getAttribute('data-id');
    const tabla = selectElement.getAttribute('data-tabla');
    const nuevoEstado = selectElement.value;
    
    selectElement.setAttribute('data-status', nuevoEstado);

    const formData = new FormData();
    formData.append('action', 'update_status'); // <--- LE DECIMOS AL CRUD QUÉ HACER
    formData.append('id', id);
    formData.append('tabla', tabla);
    formData.append('estado', nuevoEstado);

    // Apuntamos directo a tu archivo de operaciones centralizado
    fetch('crud_operaciones.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectElement.style.borderColor = "var(--success)";
            setTimeout(() => { selectElement.style.borderColor = "var(--davys-grey)"; }, 600);
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('⚙️ Hubo un problema al conectar con el servidor.');
    });
    }

    // Inicializar los colores de los selectores al cargar la página
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.setAttribute('data-status', select.value);
        });
    });
    </script>
</body>
</html>