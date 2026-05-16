<?php
include __DIR__ . '/../../config/Configuracion.php';
include __DIR__ . '/../menu/La-carta.php';
$cart = new Cart;
$cart_count = $cart->total_items();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - Camaron Express</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="\Eatstech\assets\css\style2.css">
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
            <li><a href="/Eatstech/modules/carrito/carritodecompras.php" class="active">Menú</a></li>
            <li><a href="/Eatstech/modules/menu/VerCarta.php">Mi Carrito</a></li>
            <li><a href="/Eatstech/modules/pagos/Pagos.php">Pagar</a></li>
        </ul>

        <a href="/Eatstech/modules/menu/VerCarta.php" class="cart-icon-btn" title="Ver carrito">
            <i class="fa-solid fa-cart-shopping"></i>
            <?php if ($cart_count > 0): ?>
                <span class="cart-badge"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </a>
    </header>

    <!-- HERO BANNER -->
    <div class="menu-hero">
        <p class="subtitle">Selección especial</p>
        <h1>Menú Camaron Express</h1>
    </div>

    <!-- PRODUCTOS -->
    <section class="products-section">
        <div class="products-grid">
            <?php
            $query = $db->query("SELECT * FROM mis_productos WHERE status = '1' ORDER BY id DESC");
            if ($query && $query->num_rows > 0):
                while ($row = $query->fetch_assoc()):
            ?>
                <div class="product-card">

                    <!-- Imagen -->
                    <div class="card-img-wrap">
                        <?php if (!empty($row['imagen'])): ?>
                            <img src="<?php echo htmlspecialchars($row['imagen']); ?>"
                                 alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <?php else: ?>
                            <div class="no-image-placeholder">
                                <i class="fa-solid fa-shrimp"></i>
                                <span>Camaron Express</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contenido -->
                    <div class="card-body">
                        <h3 class="card-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="card-description"><?php echo htmlspecialchars($row['description']); ?></p>

                        <div class="card-footer-row">
                            <span class="card-price">
                                $<?php echo number_format($row['price'], 0, ',', '.'); ?> COP
                            </span>
                            <a href="./AccionCarta.php?action=addToCart&id=<?php echo $row['id']; ?>"
                               class="btn-add-cart">
                                <i class="fa-solid fa-cart-plus"></i>
                                Agregar
                            </a>
                        </div>
                    </div>

                </div>
            <?php
                endwhile;
            else:
            ?>
                <div class="empty-state">
                    <i class="fa-solid fa-bowl-food"></i>
                    <p>No hay productos disponibles por el momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <p>© 2024 Camaron Express &mdash; Mosquera, Cundinamarca &mdash;
           <a href="tel:+573248933841">+57 324 893 3841</a>
        </p>
    </footer>

</body>
</html>