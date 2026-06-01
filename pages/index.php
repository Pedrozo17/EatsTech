<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../assets/css/style1.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/logo.png">
</head>

<body>
    

<!-- 1. PANTALLA DE CARGA (LOADING) -->
    <div class="loading-page">
        <img id="svg" src="../assets/images/logo.png" alt="Logo">
        <div class="name-container">
            <div class="logo-name">EATSTECH</div>
        </div>
    </div>

    <!-- 2. BARRA DE NAVEGACIÓN (NAVBAR) -->
    <nav class="navbar">
        <div class="nav-container">
            <img src="../assets/images/logo.png" alt="Logo" class="nav-logo">
            
            <button class="menu-toggle" id="mobile-menu-btn" aria-label="Abrir menú">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <div class="nav-collapse" id="navbar-collapse-target">
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#servicios">Servicios</a></li>
                    <li><a href="#sobre-nosotros">Sobre Nosotros</a></li>
                    <li><a href="#contactanos">Contáctanos</a></li>
                </ul>
                <div class="nav-buttons">
                    <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true): ?>
                        <div class="user-logged-wrapper">
                            <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                            <a href="../modules/usuarios/logout " class="btn-login">Cerrar sesión</a>
                        </div>
                    <?php else: ?>
                        <a href="../modules/usuarios/iniciodesesion " class="btn-login">Iniciar sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- 3. CONTENEDOR PRINCIPAL FIJO (SWIPER DE RESTAURANTES) -->
    <main class="seccion-principal">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                
                <!-- 1. CAMARÓN EXPRESS (ACTIVO) -->
                <div class="swiper-slide">
                    <div class="icons">
                        <i class="fa-solid fa-circle-arrow-left"></i>
                        <img src="../assets/images/logo_producto(1).png" alt="">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <div class="product-content">
                        <div class="product-txt">
                            <h3>CAMARON EXPRESS</h3>
                            <p>
                                Somos un espacio enfocado en los cocteles de camarón, basándonos en recetas tradicionales así como innovaciones
                                constantes traída de otros departamentos y de la costa colombiana
                            </p>
                        </div>
                        <div class="product-img">
                            <img src="../assets/images/logo_empresa.webp" alt="">
                        </div>
                    </div>
                    <!-- Mantiene su enlace directo hacia la página del restaurante -->
                    <a href="./casarolla " class="btn-1 btn-entrar-restaurante">entrar</a>
                </div>

                <!-- 2. FOGÓN ANTIOQUEÑO (PRÓXIMAMENTE) -->
                <div class="swiper-slide">
                    <div class="icons">
                        <i class="fa-solid fa-circle-arrow-left"></i>
                        <img src="../assets/images/logo_producto(1).png" alt="">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <div class="product-content">
                        <div class="product-txt">
                            <h3>FOGON ANTIOQUEÑO</h3>
                            <p>
                                La mejor comida paisa con una excelente atención estamos ubicados frente al acueducto, a una
                                cuadra del comando de la policía en mosquera, cundinamarca.
                            </p>
                        </div>
                        <div class="product-img">
                            <img src="../assets/images/fogon antioqueño.webp" alt="Fogón Antioqueño">
                        </div>
                    </div>
                    <a href="#" class="btn-1 btn-proximamente">Proximamente</a>
                </div>

                <!-- 3. TOSKANA (PRÓXIMAMENTE) -->
                <div class="swiper-slide">
                    <div class="icons">
                        <i class="fa-solid fa-circle-arrow-left"></i>
                        <img src="../assets/images/logo_producto(1).png" alt="">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <div class="product-content">
                        <div class="product-txt">
                            <h3>TOSKANA</h3>
                            <p>
                                Nos enorgullece ofrecer una experiencia culinaria única que combina la auténtica cocina de
                                autor con la comida tradicional colombiana. Mezclando matices, trends y sabores llegando
                                al punto de nuestra identidad propia.
                            </p>
                        </div>
                        <div class="product-img">
                            <img src="../assets/images/la toskana.webp" alt="Toskana">
                        </div>
                    </div>
                    <a href="#" class="btn-1 btn-proximamente">proximamente</a>
                </div>

            </div>
        </div>
    </main>

<div id="contenido-restaurante" class="contenido-oculto">
    
    <section id="servicios" class="seccion-info">
        <div class="info-container">
            <h2 class="titulo-seccion">Nuestros <span>Servicios</span></h2>
            <p class="subtitulo-seccion">Descubre todo lo que EatsTech y nuestros restaurantes aliados tienen preparado para ti.</p>
            
            <div class="grid-servicios">
                <div class="tarjeta-servicio">
                    <div class="icono-servicio"><i class="fa-solid fa-rocket"></i></div>
                    <h3>Marketing & Visibilidad Digital</h3>
                    <p>Impulsamos el alcance de tu negocio. Diseñamos estrategias de posicionamiento SEO local y vitrinas dinámicas premium para que tu menú resalte y atraiga cientos de nuevos clientes en la región y todo a la medida que necesitas.</p>
                </div>
                
                <div class="tarjeta-servicio">
                    <div class="icono-servicio"><i class="fa-solid fa-qrcode"></i></div>
                    <h3>Menú QR e Inteligente</h3>
                    <p>Moderniza tus mesas físicas: los comensales escanean el código, visualizan el menú en tiempo real y gestionan su carrito de compras al instante.</p>
                </div>
                
                <div class="tarjeta-servicio">
                    <div class="icono-servicio"><i class="fa-solid fa-sliders"></i></div>
                    <h3>Gestión de Menú & Pedidos en Tiempo Real</h3>
                    <p>Control absoluto desde un solo panel inteligente. Actualiza precios, gestiona el stock disponible y procesa órdenes de cocina al instante sin enredos, minimizando errores y agilizando cada despacho.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="sobre-nosotros" class="seccion-info bg-alterno">
        <div class="info-container sobre-nosotros-flex">
            <div class="sobre-nosotros-txt">
                <h2 class="titulo-seccion">Sobre <span>Nosotros</span></h2>
                <p class="parrafo-destacado">EatsTech nace como una solución tecnológica innovadora pensada para conectar la pasión culinaria con la comodidad digital.</p>
                <p>Somos un ecosistema enfocado en potenciar la visibilidad de los restaurantes locales en Mosquera, Madrid y Funza, permitiendo a los usuarios explorar la gastronomía tradicional y moderna a través de una experiencia interactiva fluida y de alta calidad.</p>
                <div class="metricas">
                    <div class="metrica-item"><span>3+</span><p>Restaurantes</p></div>
                    <div class="metrica-item"><span>100%</span><p>Garantía UX</p></div>
                </div>
            </div>
            <div class="sobre-nosotros-img">
                <img src="../assets/images/logo.png" alt="EatsTech Plataforma">
            </div>
        </div>
    </section>

    <section id="contactanos" class="seccion-info">
        <div class="info-container">
            <h2 class="titulo-seccion">¿Tienes dudas? <span>Contáctanos</span></h2>
            <p class="subtitulo-seccion">Estamos aquí para ayudarte a integrar tu restaurante o resolver cualquier inconveniente técnico.</p>
            
            <div class="contacto-wrapper">
                <div class="contacto-info">
                    <div class="contacto-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <div>
                            <h4>Ubicación</h4>
                            <p>Mosquera, Cundinamarca</p>
                        </div>
                    </div>
                    <div class="contacto-item">
                        <i class="fa-solid fa-envelope"></i>
                        <div>
                            <h4>Correo Electrónico</h4>
                            <p>soporte@eatstech.com</p>
                        </div>
                    </div>
                    <div class="contacto-item">
                        <i class="fa-solid fa-phone"></i>
                        <div>
                            <h4>Teléfono / WhatsApp</h4>
                            <p>+57 314 2756300</p>
                        </div>
                    </div>
                </div>

                <form class="contacto-form" id="formulario-whatsapp">
                    <div class="form-grupo">
                        <input type="text" id="whatsapp-nombre" placeholder="Tu Nombre completo" required>
                    </div>
                    <div class="form-grupo">
                        <input type="email" id="whatsapp-correo" placeholder="Tu Correo Electrónico" required>
                    </div>
                    <div class="form-grupo">
                        <textarea id="whatsapp-mensaje" placeholder="Escribe tu mensaje aquí..." rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn-enviar-formulario">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </section>
    
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="../assets/js/main2.js"></script>
</body>
</html>