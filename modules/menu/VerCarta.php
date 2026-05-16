<?php
include './La-carta.php';
$cart = new Cart;
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
    <link rel="stylesheet" href="\Eatstech\assets\css\style3.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script>
        function updateCartItem(obj, id) {
            $.get("../carrito/AccionCarta.php", {
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
            <li><a href="/Eatstech/modules/menu/VerCarta.php" class="active">Mi Carrito</a></li>
            <li><a href="/Eatstech/modules/pagos/Pagos.php">Pagar</a></li>
        </ul>
    </header>

    <!-- HERO -->
    <div class="cart-hero">
        <p class="subtitle">Revisa tu pedido</p>
        <h1>Carrito de Compras</h1>
    </div>

    <!-- CONTENIDO -->
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
                        foreach ($cartItems as $item): ?>
                            <tr>
                                <td class="product-name"><?php echo htmlspecialchars($item['name']); ?></td>
                                <td class="price-cell">$<?php echo number_format($item['price'], 0, ',', '.'); ?> COP</td>
                                <td>
                                    <input type="number"
                                           class="qty-input"
                                           value="<?php echo $item['qty']; ?>"
                                           min="1"
                                           onchange="updateCartItem(this, '<?php echo $item['rowid']; ?>')">
                                </td>
                                <td class="price-cell">$<?php echo number_format($item['subtotal'], 0, ',', '.'); ?> COP</td>
                                <td>
                                    <a href="../carrito/AccionCarta.php?action=removeCartItem&id=<?php echo $item['rowid']; ?>"
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
                            <a href="/Eatstech/modules/carrito/carritodecompras.php" class="btn-back">
                                <i class="fa-solid fa-chevron-left"></i> Volver al menú
                            </a>
                        </td>
                        <td colspan="2"></td>
                        <?php if ($cart->total_items() > 0): ?>
                            <td class="total-label">
                                Total: <span class="total-amount">$<?php echo number_format($cart->total(), 0, ',', '.'); ?> COP</span>
                            </td>
                            <td>
                                <a href="/Eatstech/modules/pagos/Pagos.php" class="btn-pay">
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

    <!-- FOOTER -->
    <footer class="site-footer">
        <p>© 2024 Camaron Express &mdash; Mosquera, Cundinamarca &mdash;
           <a href="tel:+573248933841">+57 324 893 3841</a>
        </p>
    </footer>

</body>
</html>