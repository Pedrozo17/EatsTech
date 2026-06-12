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
    <link rel="shortcut icon" href="../../assets/images/logo_empresa-removebg-preview.png" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>

<body>

    <header class="site-header">
        <a href="../../pages/camaron" class="header-logo">
            <img src="../../assets/images/logo_empresa-removebg-preview.png" alt="Camaron Express">
            <span>Camaron Express</span>
        </a>

        <ul class="header-nav">
            <li><a href="../../pages/camaron" class="active">Inicio</a></li>
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
                        foreach ($cartItems as $item): 
                            
                            // 1. Consultamos el stock en tiempo real (dejamos la base de datos limpia)
                            $producto_id = intval($item['id']);
                            $buscar_producto = $db->query("SELECT stock FROM mis_productos WHERE id = $producto_id");
                            $stock_real = 0;
                            
                            if ($buscar_producto && $prod_data = $buscar_producto->fetch_assoc()) {
                                $stock_real = intval($prod_data['stock']);
                            }
                        
                            // 2. Leemos la imagen directamente desde la sesión que unificamos
                            $imgName = !empty($item['imagen']) ? trim($item['imagen']) : 'default.png';
                            $ruta_imagen = "../../assets/images/" . $imgName;
                        ?>
                            <tr>
                                <td class="product-name">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="<?php echo $ruta_imagen; ?>" 
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
    </footer >

    <script>
    function updateCartItem(obj, id) {
        const cantidadActual = parseInt(obj.value, 10);
        const stockMaximo = parseInt(obj.getAttribute('max'), 10);
        const nombreProducto = obj.getAttribute('data-name') || 'este producto';

        // 1. Validar si excede las existencias reales en cocina
        if (cantidadActual > stockMaximo) {
            alert('⚠️ Inventario Insuficiente: Solo quedan ' + stockMaximo + ' unidades disponibles de "' + nombreProducto + '".');
            
            // Devolvemos el valor de la pantalla al máximo permitido
            obj.value = stockMaximo;
            
            // Volvemos a disparar la función con el valor corregido para actualizar la sesión
            updateCartItem(obj, id);
            return;
        }

        // 2. Control por si ponen números negativos o vacíos
        if (cantidadActual < 1 || isNaN(cantidadActual)) {
            obj.value = 1;
            updateCartItem(obj, id);
            return;
        }

        // 3. Procesa la petición AJAX con jQuery si todo está bajo los límites
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