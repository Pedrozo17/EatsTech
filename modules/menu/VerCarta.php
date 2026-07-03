<?php
include './La-carta.php';
$cart = new Cart;

// 🟢 1. INCLUIMOS LA CONEXIÓN A LA BASE DE DATOS EN LA CABECERA
include("../../config/Configuracion.php"); 
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Camaron Express</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style3.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/images/favicon-camaron.png">
    
    <!-- 📱 Para Dispositivos Móviles (iOS y Android) -->
    <!-- Apple (Safari y accesos directos en iPhone/iPad) -->
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/images/apple-touch-icon-camaron.png">
    
    <!-- Android (Chrome Mobile y pantallas de inicio) -->
    <link rel="icon" type="image/png" sizes="192x192" href="../../assets/images/web-app-manifest-192x192-camaron.png">
    <link rel="icon" type="image/png" sizes="512x512" href="../../assets/images/web-app-manifest-512x512-camaron.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>

<body>

    <header class="site-header">
        <a href="../../pages/camaron" class="header-logo">
            <img src="../../assets/images/logo_empresa-removebg-preview.png" alt="Camaron Express">
            <span>Camaron Express</span>
        </a>

        <ul class="header-nav">
            <li><a href="../../pages/camaron">Inicio</a></li>
            <li><a href="../../modules/carrito/carritodecompras">Menú</a></li>
            <li><a href="../../modules/menu/VerCarta" class="active">Mi Carrito</a></li>
            <li><a href="../../modules/pagos/Pagos">Pagar</a></li>
        </ul>
    </header>

    <div class="cart-hero">
        <p class="subtitle">Revisa tu pedido</p>
        <h1>Carrito de Compras</h1>
    </div>

    <div class="cart-wrapper">
        <div class="cart-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($cart->total_items() > 0):
                        $cartItems = $cart->contents();
                        
                        // Preparar la consulta de stock una sola vez afuera del bucle por rendimiento
                        $stmt_stock = $db->prepare("SELECT stock FROM mis_productos WHERE id = ?");

                        foreach ($cartItems as $item): 
                            
                            // 🟢 1. Consultamos el stock en tiempo real con Sentencias Preparadas
                            $producto_id = intval($item['id']);
                            $stock_real = 0;
                            
                            $stmt_stock->bind_param("i", $producto_id);
                            $stmt_stock->execute();
                            $result_stock = $stmt_stock->get_result();
                            
                            if ($prod_data = $result_stock->fetch_assoc()) {
                                $stock_real = intval($prod_data['stock']);
                            }
                        
                            // 🟢 2. LO NUEVO: Forzar la extensión .webp de las imágenes optimizadas
                            $imgName = !empty($item['imagen']) ? trim($item['imagen']) : '';
                            if (!empty($imgName)) {
                                $info = pathinfo($imgName);
                                $imgName = $info['filename'] . '.webp';
                            } else {
                                $imgName = 'default.png';
                            }
                            $ruta_imagen = "../../assets/images/" . $imgName;
                        ?>
                            <tr>
                                <td class="product-name">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="<?php echo htmlspecialchars($ruta_imagen); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             style="width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td class="price-cell">$<?php echo number_format($item['price'], 0, ',', '.'); ?> COP</td>
                                <td>
                                    <input type="number"
                                           class="qty-input"
                                           value="<?php echo $item['qty']; ?>"
                                           min="1"
                                           max="<?php echo $stock_real; ?>" 
                                           data-name="<?php echo htmlspecialchars($item['name']); ?>"
                                           onchange="updateCartItem(this, '<?php echo $item['rowid']; ?>')">
                                </td>
                                <td class="price-cell">$<?php echo number_format($item['subtotal'], 0, ',', '.'); ?> COP</td>
                                <td>
                                    <a href="../carrito/AccionCarta?action=removeCartItem&id=<?php echo $item['rowid']; ?>"
                                       class="btn-delete"
                                       onclick="return confirm('¿Eliminar este producto del carrito?')"
                                       title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;
                        $stmt_stock->close(); // Cerramos la sentencia al finalizar el bucle
                    else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-cart">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <p>Tu carrito está vacío.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <a href="../carrito/carritodecompras" class="btn-back">
                                <i class="fa-solid fa-chevron-left"></i> Volver al menú
                            </a>
                        </td>
                        <td colspan="2"></td>
                        <?php if ($cart->total_items() > 0): ?>
                            <td class="total-label">
                                Total: <span class="total-amount">$<?php echo number_format($cart->total(), 0, ',', '.'); ?> COP</span>
                            </td>
                            <td>
                                <a href="../pagos/Pagos" class="btn-pay">
                                    Pagar <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </td>
                        <?php else: ?>
                            <td colspan="2"></td>
                        <?php endif; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <footer class="site-footer">
        <p>© 2026 Camaron Express &mdash; Mosquera, Cundinamarca &mdash;
            <a href="tel:+573248933841">+57 324 893 3841</a>
        </p>
    </footer>

    <script>
    function updateCartItem(obj, id) {
        const cantidadActual = parseInt(obj.value, 10);
        const stockMaximo = parseInt(obj.getAttribute('max'), 10);
        const nombreProducto = obj.getAttribute('data-name') || 'este producto';

        if (cantidadActual > stockMaximo) {
            alert('⚠️ Inventario Insuficiente: Solo quedan ' + stockMaximo + ' unidades disponibles de "' + nombreProducto + '".');
            obj.value = stockMaximo;
            updateCartItem(obj, id);
            return;
        }

        if (cantidadActual < 1 || isNaN(cantidadActual)) {
            obj.value = 1;
            updateCartItem(obj, id);
            return;
        }

        $.get("../carrito/AccionCarta", {
            action: "updateCartItem",
            id: id,
            qty: obj.value
        }, function(data) {
            if (data == 'ok') {
                location.reload();
            } else {
                alert('No se pudo actualizar el carrito, intenta de nuevo.');
            }
        });
    }
    </script>

</body>
</html>