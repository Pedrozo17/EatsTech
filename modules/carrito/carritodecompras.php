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
    <link rel="shortcut icon" href="../../assets/images/logo_empresa-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/style2.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <header class="site-header">
        <a href="../../pages/camaron" class="header-logo">
            <img src="../../assets/images/logo_empresa-removebg-preview.png" alt="Camaron Express">
            <span>Camaron Express</span>
        </a>

        <ul class="header-nav">
            <li><a href="../../pages/camaron" class="active">Inicio</a></li>
            <li><a href="../carrito/carritodecompras" class="active">Menú</a></li>
            <li><a href="../menu/VerCarta">Mi Carrito</a></li>
            <li><a href="../pagos/Pagos">Pagar</a></li>
        </ul>

        <a href="../menu/VerCarta" class="cart-icon-btn" title="Ver carrito">
            <i class="fa-solid fa-cart-shopping"></i>
            <?php if ($cart_count > 0): ?>
                <span class="cart-badge"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </a>
    </header>

    <div class="menu-hero">
        <p class="subtitle">Selección especial</p>
        <h1>Menú Camaron Express</h1>
    </div>

    <section class="products-section">
        <div class="products-grid">
            <?php
            $query = $db->query("SELECT * FROM mis_productos WHERE status = '1' ORDER BY id ASC");
            if ($query && $query->num_rows > 0):
                while ($row = $query->fetch_assoc()):
            ?>
                <div class="product-card">

                    <div class="card-img-wrap">
                        <?php if (!empty($row['imagen'])): 
                            // 🟢 AQUÍ ESTÁ EL TRUCO: Concatenamos la ruta física de la carpeta assets
                            $ruta_completa = "../../assets/images/" . $row['imagen'];
                        ?>
                            <img src="<?php echo htmlspecialchars($ruta_completa); ?>" loading="lazy"
                                 alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <?php else: ?>
                            <div class="no-image-placeholder">
                                <i class="fa-solid fa-shrimp"></i>
                                <span>Camaron Express</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <h3 class="card-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="card-description"><?php echo htmlspecialchars($row['description']); ?></p>

                    <div class="card-footer-row">
                            <span class="card-price">
                                $<?php echo number_format($row['price'], 0, ',', '.'); ?> COP
                            </span>

                            <a href="#" 
                               data-id="<?php echo $row['id']; ?>" 
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

    <footer class="site-footer">
        <p>© 2026 Camaron Express &mdash; Mosquera, Cundinamarca &mdash;
           <a href="tel:+573248933841">+57 324 893 3841</a>
        </p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
    // Capturamos todos los botones con la clase especificada
    document.querySelectorAll('.btn-add-cart').forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault(); // Evita que la página salte al inicio '#'
            
            const productoId = this.getAttribute('data-id');
            
            if (!productoId) {
                console.error("Error: No se encontró el atributo data-id en el botón.");
                return;
            }
            
            // Creamos los datos estructurados para simular el envío del formulario tradicional
            const datosFormulario = new URLSearchParams();
            datosFormulario.append('action', 'addToCart');
            datosFormulario.append('id', productoId);

            // Enviamos los datos directamente a tu controlador existente mediante POST asíncrono
            fetch('./AccionCarta.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: datosFormulario.toString()
            })
            .then(res => {
                // Si tu archivo 'AccionCarta.php' redirige o devuelve HTML en vez de JSON, 
                // con que responda exitoso (status 200) sabemos que procesó la inserción.
                if (res.ok) {
                    // Alerta sutil, limpia y estética en la esquina superior derecha
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto añadido al carrito',
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#242424',
                        color: '#FFF',
                        iconColor: '#FFB900'
                    });

                    // 🔄 ACTUALIZACIÓN DINÁMICA DEL CONTADOR (Badge)
                    let cartBadge = document.querySelector('.cart-badge');
                    let cartIconBtn = document.querySelector('.cart-icon-btn');
                    
                    if (cartBadge) {
                        // Si ya existe el contador, le sumamos 1 de forma visual
                        let cantidadActual = parseInt(cartBadge.textContent) || 0;
                        cartBadge.textContent = cantidadActual + 1;
                    } else if (cartIconBtn) {
                        // Si no existía (estaba en 0), creamos la etiqueta dinámicamente
                        const nuevoBadge = document.createElement('span');
                        nuevoBadge.className = 'cart-badge';
                        nuevoBadge.textContent = '1';
                        cartIconBtn.appendChild(nuevoBadge);
                    }
                }
            })
            .catch(err => {
                console.error("Error al procesar la petición Fetch:", err);
            });
        });
    });
});
    </script>

</body>
</html>