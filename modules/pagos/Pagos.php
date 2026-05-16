<?php
include '../../config/Configuracion.php';
include '../menu/La-carta.php';
$cart = new Cart;

if ($cart->total_items() <= 0) {
    header("Location: ../carrito/carritodecompras.php");
    exit();
}

// Traer datos del cliente desde sesión (login)
$nombre   = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$correo   = isset($_SESSION['correo']) ? $_SESSION['correo'] : '';

// Si además tienes tabla clientes, traer más datos
$_SESSION['sessCustomerID'] = 1;
$query   = $db->query("SELECT * FROM clientes WHERE id = " . $_SESSION['sessCustomerID']);
$custRow = $query ? $query->fetch_assoc() : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar - Camaron Express</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="\Eatstech\assets\css\style4.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

</head>

<body>

    <!-- HEADER -->
    <header class="site-header">
        <a href="/Eatstech/pages/casarolla.php" class="header-logo">
            <img src="../../assets/images/logo_empresa-removebg-preview.png" alt="Camaron Express">
            <span>Camaron Express</span>
        </a>
        <ul class="header-nav">
            <li><a href="/Eatstech/pages/casarolla.php">Inicio</a></li>
            <li><a href="/Eatstech/modules/carrito/carritodecompras.php">Menú</a></li>
            <li><a href="/Eatstech/modules/menu/VerCarta.php">Mi Carrito</a></li>
            <li><a href="/Eatstech/modules/pagos/Pagos.php" class="active">Pagar</a></li>
        </ul>
    </header>

    <!-- HERO -->
    <div class="pay-hero">
        <p class="subtitle">Último paso</p>
        <h1>Confirmar Pedido</h1>
    </div>

    <!-- CONTENIDO -->
    <div class="pay-wrapper">

        <!-- COLUMNA IZQUIERDA: Resumen + Métodos de pago -->
        <div class="left-col">

            <!-- Resumen del pedido -->
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-receipt"></i>
                    <h2>Resumen del Pedido</h2>
                </div>
                <div class="card-body">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cant.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($cart->total_items() > 0):
                                $cartItems = $cart->contents();
                                foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td class="product-name"><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td class="price-cell">$<?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                        <td><?php echo $item['qty']; ?></td>
                                        <td class="price-cell">$<?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr><td colspan="4" style="text-align:center; padding:20px; color:#7a5c44;">No hay artículos.</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if ($cart->total_items() > 0): ?>
                        <tfoot>
                            <tr class="order-total-row">
                                <td colspan="3" style="text-align:right;">Total:</td>
                                <td class="price-cell total-amount">
                                    $<?php echo number_format($cart->total(), 0, ',', '.'); ?> COP
                                </td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>

                    <!-- Botones -->
                    <div class="action-buttons">
                        <a href="../menu/VerCarta.php" class="btn-back">
                            <i class="fa-solid fa-chevron-left"></i> Volver
                        </a>
                        <a href="../carrito/AccionCarta.php?action=placeOrder" class="btn-order">
                            Confirmar pedido <i class="fa-solid fa-check"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Métodos de pago -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <i class="fa-solid fa-credit-card"></i>
                    <h2>Método de Pago</h2>
                </div>
                <div class="card-body">
                    <div class="payment-methods">

                        <!-- Nequi -->
                        <label class="payment-option" onclick="selectPayment(this, 'nequi')">
                            <input type="radio" name="payment" value="nequi">
                            <div class="payment-icon icon-nequi"><i class="fa-solid fa-mobile-screen"></i></div>
                            <div class="payment-info">
                                <p class="payment-name">Nequi</p>
                                <p class="payment-desc">Transferencia inmediata</p>
                            </div>
                            <i class="fa-solid fa-circle-check check-icon"></i>
                        </label>
                        <div class="payment-detail" id="detail-nequi">
                            <p>Envía el pago a:<br>
                               <strong>Número Nequi: 324 893 3841</strong><br>
                               Nombre: Camaron Express Delivery SC<br>
                               Luego envía el comprobante por WhatsApp.</p>
                            <a href="https://wa.me/573248933841?text=Hola!%20Acabo%20de%20realizar%20mi%20pago%20por%20Nequi%20para%20mi%20pedido."
                               target="_blank" class="whatsapp-btn">
                                <i class="fa-brands fa-whatsapp"></i> Enviar comprobante
                            </a>
                        </div>

                        <!-- Daviplata -->
                        <label class="payment-option" onclick="selectPayment(this, 'daviplata')">
                            <input type="radio" name="payment" value="daviplata">
                            <div class="payment-icon icon-daviplata"><i class="fa-solid fa-d"></i></div>
                            <div class="payment-info">
                                <p class="payment-name">Daviplata</p>
                                <p class="payment-desc">Pago desde tu app Davivienda</p>
                            </div>
                            <i class="fa-solid fa-circle-check check-icon"></i>
                        </label>
                        <div class="payment-detail" id="detail-daviplata">
                            <p>Envía el pago a:<br>
                               <strong>Número Daviplata: 324 893 3841</strong><br>
                               Nombre: Camaron Express Delivery SC<br>
                               Luego envía el comprobante por WhatsApp.</p>
                            <a href="https://wa.me/573248933841?text=Hola!%20Acabo%20de%20realizar%20mi%20pago%20por%20Daviplata%20para%20mi%20pedido."
                               target="_blank" class="whatsapp-btn">
                                <i class="fa-brands fa-whatsapp"></i> Enviar comprobante
                            </a>
                        </div>

                        <!-- Bancolombia -->
                        <label class="payment-option" onclick="selectPayment(this, 'bancolombia')">
                            <input type="radio" name="payment" value="bancolombia">
                            <div class="payment-icon icon-bancol"><i class="fa-solid fa-building-columns"></i></div>
                            <div class="payment-info">
                                <p class="payment-name">Transferencia Bancolombia</p>
                                <p class="payment-desc">Cuenta de ahorros o corriente</p>
                            </div>
                            <i class="fa-solid fa-circle-check check-icon"></i>
                        </label>
                        <div class="payment-detail" id="detail-bancolombia">
                            <p>Datos bancarios:<br>
                               <strong>Banco: Bancolombia</strong><br>
                               Tipo: Cuenta de ahorros<br>
                               Número: <strong>32737289229</strong><br>
                               Titular: Camaron Express Delivery SC<br>
                               Cédula: 2193903403<br>
                               Luego envía el comprobante por WhatsApp.</p>
                            <a href="https://wa.me/573248933841?text=Hola!%20Acabo%20de%20realizar%20mi%20transferencia%20para%20mi%20pedido."
                               target="_blank" class="whatsapp-btn">
                                <i class="fa-brands fa-whatsapp"></i> Enviar comprobante
                            </a>
                        </div>

                        <!-- Efectivo -->
                        <label class="payment-option" onclick="selectPayment(this, 'efectivo')">
                            <input type="radio" name="payment" value="efectivo">
                            <div class="payment-icon icon-efectivo"><i class="fa-solid fa-money-bill-wave"></i></div>
                            <div class="payment-info">
                                <p class="payment-name">Efectivo contra entrega</p>
                                <p class="payment-desc">Paga cuando recibas tu pedido</p>
                            </div>
                            <i class="fa-solid fa-circle-check check-icon"></i>
                        </label>
                        <div class="payment-detail" id="detail-efectivo">
                            <p>Tu pedido será entregado en:<br>
                               <strong>Calle 20 #5-94, Mosquera</strong><br>
                               Horario: Lun–Jue 5pm–9pm / Vie–Dom 2pm–9pm<br>
                               Ten el dinero exacto listo al momento de la entrega.</p>
                            <a href="https://wa.me/573248933841?text=Hola!%20Quiero%20pagar%20en%20efectivo%20mi%20pedido."
                               target="_blank" class="whatsapp-btn">
                                <i class="fa-brands fa-whatsapp"></i> Confirmar por WhatsApp
                            </a>
                        </div>

                        <!-- Efecty -->
                        <label class="payment-option" onclick="selectPayment(this, 'efecty')">
                            <input type="radio" name="payment" value="efecty">
                            <div class="payment-icon icon-efecty"><i class="fa-solid fa-store"></i></div>
                            <div class="payment-info">
                                <p class="payment-name">Efecty</p>
                                <p class="payment-desc">Paga en cualquier punto Efecty</p>
                            </div>
                            <i class="fa-solid fa-circle-check check-icon"></i>
                        </label>
                        <div class="payment-detail" id="detail-efecty">
                            <p>Realiza el pago en cualquier punto Efecty a nombre de:<br>
                               <strong>Camaron Express Delivery SC</strong><br>
                               Cédula: Agrega tu cédula<br>
                               Luego envía el comprobante por WhatsApp.</p>
                            <a href="https://wa.me/573248933841?text=Hola!%20Acabo%20de%20pagar%20por%20Efecty%20mi%20pedido."
                               target="_blank" class="whatsapp-btn">
                                <i class="fa-brands fa-whatsapp"></i> Enviar comprobante
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA: Info del cliente -->
        <div class="right-col">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-user"></i>
                    <h2>Tus Datos</h2>
                </div>
                <div class="card-body">
                    <div class="client-info">

                        <?php if (!empty($nombre)): ?>
                        <div class="client-row">
                            <i class="fa-solid fa-user"></i>
                            <span><?php echo htmlspecialchars($nombre); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($correo)): ?>
                        <div class="client-row">
                            <i class="fa-solid fa-envelope"></i>
                            <span><?php echo htmlspecialchars($correo); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($custRow['phone'])): ?>
                        <div class="client-row">
                            <i class="fa-solid fa-phone"></i>
                            <span><?php echo htmlspecialchars($custRow['phone']); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($custRow['address'])): ?>
                        <div class="client-row">
                            <i class="fa-solid fa-location-dot"></i>
                            <span><?php echo htmlspecialchars($custRow['address']); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if (empty($nombre) && empty($correo) && empty($custRow['phone'])): ?>
                        <div class="client-row">
                            <i class="fa-solid fa-circle-info"></i>
                            <span style="color:#7a5c44;">Inicia sesión para ver tus datos.</span>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Resumen de totales -->
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-calculator"></i>
                    <h2>Total a Pagar</h2>
                </div>
                <div class="card-body" style="text-align: center; padding: 30px;">
                    <p style="font-family: var(--forum); font-size: 3.5rem; color: var(--camaron-gold);">
                        $<?php echo number_format($cart->total(), 0, ',', '.'); ?>
                    </p>
                    <p style="font-size: 1.4rem; color: #7a5c44; margin-top: 6px;">COP — <?php echo $cart->total_items(); ?> producto(s)</p>

                    <a href="../carrito/AccionCarta.php?action=placeOrder"
                       class="btn-order" style="margin-top: 24px; width: 100%; justify-content: center;">
                        <i class="fa-solid fa-check"></i> Confirmar pedido
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <footer class="site-footer">
        <p>© 2024 Camaron Express &mdash; Mosquera, Cundinamarca &mdash;
           <a href="tel:+573248933841">+57 324 893 3841</a>
        </p>
    </footer>

    <script>
        function selectPayment(el, method) {
            // Quitar selección de todos
            document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
            document.querySelectorAll('.payment-detail').forEach(d => d.classList.remove('active'));

            // Activar el seleccionado
            el.classList.add('selected');
            const detail = document.getElementById('detail-' + method);
            if (detail) detail.classList.add('active');
        }
    </script>

</body>
</html>