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

// 2. PESTAÑA 1: PEDIDOS REGISTRADOS
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Camaron Express</title>
    <link rel="stylesheet" href="../../assets/css/perfil.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/tu-kit.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
</head>
<body style="background-color: #141414; color: #FFF; font-family: 'DM Sans', sans-serif;">

    <nav class="navbar">
        <div class="nav-container">
            <img src="../../assets/images/logo.png" href="../../pages/index" alt="Logo" class="nav-logo">
            
            <button class="menu-toggle" id="mobile-menu-btn" aria-label="Abrir menú">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <div class="nav-collapse" id="navbar-collapse-target">
                <ul class="nav-links">
                    <li><a href="../../pages/index">Home</a></li>
                    <li><a href="../../pages/index#servicios">Servicios</a></li>
                    <li><a href="../../pages/index#sobre-nosotros">Sobre Nosotros</a></li>
                    <li><a href="../../pages/index#contactanos">Contáctanos</a></li>
                </ul>
                
                <div class="nav-buttons">
                    <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true): ?>
                        <div class="user-logged-wrapper" style="display: flex; align-items: center; gap: 15px;">
                            <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></span>
                            <a href="./logout" class="btn-login">Cerrar sesión</a>
                        </div>
                    <?php else: ?>
                        <?php endif; ?> </div>
            </div>
        </div>
    </nav>

<div class="perfil-container">
    
    <aside class="perfil-sidebar">
        <h2>Actualizar Perfil</h2>
        <span class="role">Cliente Frecuente</span>
        
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div style="background: #1b5e20; color: #fff; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                ¡Datos actualizados correctamente!
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div style="background: #b71c1c; color: #fff; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                Hubo un error al actualizar los datos.
            </div>
        <?php endif; ?>

        <form action="ActualizarPerfil.php" method="POST" style="text-align: left;">
            <div class="info-group" style="background: #1a1a1a; border-left-color: #555;">
                <label style="color: #888;">Correo Electrónico (Fijo)</label>
                <input type="email" value="<?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>" 
                       style="background: transparent; border: none; color: #888; width: 100%; font-size: 14px; outline: none;" readonly>
            </div>
            
            <div class="info-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required
                       style="background: transparent; border: none; color: #fff; width: 100%; font-size: 14px; outline: none; padding-top: 2px;">
            </div>
            
            <div class="info-group">
                <label for="direccion">Dirección de Entrega</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?>" required
                       style="background: transparent; border: none; color: #fff; width: 100%; font-size: 14px; outline: none; padding-top: 2px;">
            </div>

            <div class="info-group" style="border-left-color: #dca872;">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número." placeholder="Dejar en blanco para no cambiar"
                       style="background: transparent; border: none; color: #fff; width: 100%; font-size: 14px; outline: none; padding-top: 2px;">
            </div>
            
            <button type="submit" style="background: #FFB900; color: #000; border: none; width: 100%; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: background 0.2s;"
                    onmouseover="this.style.backgroundColor='#e0a300'" onmouseout="this.style.backgroundColor='#FFB900'">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
            </button>
        </form>
    </aside>

    <main class="perfil-main">
        <div class="tabs-container">
            <button class="tab-btn active" onclick="cambiarPestaña('pedidos')">
                <i class="fa-solid fa-box"></i> Pedidos Registrados
            </button>
            <button class="tab-btn" onclick="cambiarPestaña('monitoreo')">
                <i class="fa-solid fa-clock"></i> Órdenes en Curso / Monitoreo
            </button>
        </div>

        <div id="tab-pedidos" class="tab-content">
            <h3 style="color: #fff; margin-bottom: 20px;">Historial de Pedidos Registrados</h3>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>ID Orden</th>
                            <th>Restaurante</th>
                            <th>Productos</th>
                            <th>Fecha Registro</th>
                            <th>Total Pagado</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($historial_pedidos->num_rows > 0): ?>
                            <?php while ($pedido = $historial_pedidos->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $pedido['id']; ?></td>
                                    <td style="font-weight: bold; color: #dca872;"><?php echo htmlspecialchars($pedido['restaurante']); ?></td>
                                    <td style="max-width: 320px; line-height: 1.5; color: #ddd;">
                                        <i class="fa-solid fa-utensils" style="color: #FFB900; font-size: 11px; margin-right: 5px;"></i>
                                        <?php echo htmlspecialchars($pedido['resumen_productos'] ?? 'Sin especificar'); ?>
                                    </td>
                                    <td style="color: #aaa;"><?php echo $pedido['created']; ?></td>
                                    <td style="color: #FFB900; font-weight: bold;">$<?php echo number_format($pedido['total_price'], 0, ',', '.'); ?> COP</td>
                                    <td>
                                        <?php 
                                            $clase_estado = 'warning';
                                            if ($pedido['status'] == 'Pagado') $clase_estado = 'success';
                                            if ($pedido['status'] == 'Cancelado') $clase_estado = 'danger';
                                        ?>
                                        <span class="badge <?php echo $clase_estado; ?>">
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

        <div id="tab-monitoreo" class="tab-content" style="display: none;">
            <h3 style="color: #fff; margin-bottom: 20px;">Monitoreo de Órdenes en Tiempo Real</h3>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>ID Orden</th>
                            <th>Restaurante</th>
                            <th>Total de la Orden</th>
                            <th>Método de Pago</th>
                            <th>Fecha Entrada</th>
                            <th>Estado Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($monitoreo_ordenes->num_rows > 0): ?>
                            <?php while ($orden = $monitoreo_ordenes->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $orden['id']; ?></td>
                                    <td style="font-weight: bold; color: #dca872;"><?php echo htmlspecialchars($orden['restaurante']); ?></td>
                                    <td style="color: #FFB900; font-weight: bold;">$<?php echo number_format($orden['total_price'], 0, ',', '.'); ?> COP</td>
                                    <td style="text-transform: capitalize;"><i class="fa-solid fa-wallet" style="margin-right: 5px; color: #FFB900;"></i> <?php echo htmlspecialchars($orden['metodo_pago'] ?? 'Efectivo'); ?></td>
                                    <td style="color: #aaa;"><?php echo $orden['created']; ?></td>
                                    <td>
                                        <span class="badge warning">
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
    </main>
</div>

<script>
    // Toggle del menú móvil
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        this.classList.toggle('open');
        document.getElementById('navbar-collapse-target').classList.toggle('show');
    });

    // Control de pestañas
    function cambiarPestaña(tipo) {
        document.getElementById('tab-pedidos').style.display = (tipo === 'pedidos') ? 'block' : 'none';
        document.getElementById('tab-monitoreo').style.display = (tipo === 'monitoreo') ? 'block' : 'none';
        
        const botones = document.querySelectorAll('.tab-btn');
        botones[0].classList.toggle('active', tipo === 'pedidos');
        botones[1].classList.toggle('active', tipo === 'monitoreo');
    }
</script>
</body>
</html>