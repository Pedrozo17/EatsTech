<?php
// 1. Iniciamos sesión para poder leer el nombre del usuario logueado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../config/Configuracion.php';

if (!isset($_REQUEST['id'])) {
    header("Location: ../../pages/casarolla.php");
    exit();
}

// 2. Definimos la variable (usaremos $orderId con d minúscula en todo el archivo)
$orderId = intval($_REQUEST['id']);

// Consultar la orden - CORREGIDO: Ahora usa $orderId
$queryOrder = $db->query("SELECT * FROM orden WHERE id = $orderId");
$order = $queryOrder->fetch_assoc();

if (!$order) {
    header("Location: ../../pages/casarolla.php");
    exit();
}

$nombre  = isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : '';
$metodo = isset($order['metodo_pago']) ? htmlspecialchars($order['metodo_pago']) : '';

$queryItems = $db->query("
    SELECT oa.quantity, p.name, p.price 
    FROM orden_articulos oa 
    INNER JOIN mis_productos p ON oa.product_id = p.id 
    WHERE oa.order_id = $orderId
");

// Etiquetas legibles para el método de pago
$metodosLabel = [
    'nequi'       => 'Nequi',
    'daviplata'   => 'Daviplata',
    'bancolombia' => 'Transferencia Bancolombia',
    'efectivo'    => 'Efectivo contra entrega',
    'efecty'      => 'Efecty',
];
$metodoLabel = isset($metodosLabel[$metodo]) ? $metodosLabel[$metodo] : 'No especificado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Orden Exitosa! - Camaron Express</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../../assets/images/logo_empresa-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/style4.css">
    <link rel="stylesheet" href="../../assets/css/style5.css">
</head>
<body>

    <!-- HEADER -->
    <header class="site-header">
        <a href="../../pages/casarolla.php" class="header-logo">
            <img src="../../assets/images/logo_empresa-removebg-preview.png" alt="Camaron Express">
            <span>Camaron Express</span>
        </a>
        <ul class="header-nav">
            <li><a href="../../pages/casarolla.php">Inicio</a></li>
            <li><a href="../../modules/carrito/carritodecompras.php">Menú</a></li>
            <li><a href="../../modules/menu/VerCarta.php">Mi Carrito</a></li>
            <li><a href="../../modules/pagos/Pagos.php">Pagar</a></li>
        </ul>
    </header>

    <!-- HERO pequeño -->
    <div class="pay-hero">
        <p class="subtitle">¡Todo listo!</p>
        <h1>Pedido Confirmado</h1>
    </div>

    <!-- TARJETA PRINCIPAL -->
    <div class="success-wrapper">
        <div class="success-card">

            <!-- Barra animada superior -->
            <div class="confetti-bar"></div>

            <!-- Cabecera -->
            <div class="success-top">
                <div class="check-circle">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h1>¡Gracias<?php echo $nombre ? ', ' . $nombre : ''; ?>!</h1>
                <p>Tu pedido fue recibido con éxito</p>
            </div>

            <!-- Cuerpo -->
            <div class="success-body">

                <!-- ID de orden -->
                <div class="order-id-box">
                    <div>
                        <div class="order-id-label">N.° de Pedido</div>
                        <div class="order-id-value">#<?php echo $orderId; ?></div>
                    </div>
                    <button class="copy-btn" onclick="copiarId('<?php echo $orderId; ?>')"
                            title="Copiar ID">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>

                <!-- Detalles -->
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fa-solid fa-calendar-check"></i>
                        <div class="info-item-text">
                            <div class="label">Fecha</div>
                            <div class="value"><?php echo date('d/m/Y'); ?></div>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-clock"></i>
                        <div class="info-item-text">
                            <div class="label">Hora</div>
                            <div class="value"><?php echo date('H:i', strtotime($order['created'])); ?></div>
                        </div>
                    </div>
                    <?php if ($metodoLabel): ?>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <i class="fa-solid fa-credit-card"></i>
                        <div class="info-item-text">
                            <div class="label">Método de pago</div>
                            <div class="value"><?php echo $metodoLabel; ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <hr class="divider">

                

                <!-- Instrucción siguiente paso -->
                <div class="next-step">
                    <i class="fa-brands fa-whatsapp"></i>
                    <p>
                        Envíanos el comprobante de pago o coordina la entrega por WhatsApp.<br>
                        Menciona tu número de pedido <strong>#<?php echo $orderId; ?></strong> para una
                        atención más rápida. ¡Tu pedido está en camino! 🦐
                    </p>
                </div>

                <!-- Botones -->
                <div class="success-actions">
                    <a href="https://wa.me/573248933841?text=<?php
                        echo urlencode('¡Hola! Acabo de realizar el pedido #' . $orderId
                            . ' por ' . $metodoLabel . '. Adjunto mi comprobante.');
                    ?>" target="_blank" class="btn-wa">
                        <i class="fa-brands fa-whatsapp"></i> Enviar comprobante
                    </a>
                    <a href="../../pages/casarolla.php" class="btn-home">
                        <i class="fa-solid fa-house"></i> Volver al inicio
                    </a>
                </div>

            </div>
            <!-- fin success-body -->

        </div>
        <!-- fin success-card -->
    </div>

    <!-- FOOTER -->
    <footer class="site-footer">
        <p>© 2024 Camaron Express &mdash; Mosquera, Cundinamarca &mdash;
           <a href="tel:+573248933841">+57 324 893 3841</a>
        </p>
    </footer>

    <!-- Toast copiar -->
    <div class="toast-copy" id="toastCopy">
        <i class="fa-solid fa-circle-check"></i> ID copiado al portapapeles
    </div>

    <script>
        function copiarId(id) {
            navigator.clipboard.writeText(id).then(function() {
                const t = document.getElementById('toastCopy');
                t.classList.add('show');
                setTimeout(() => t.classList.remove('show'), 2500);
            });
        }
    </script>

</body>
</html>