<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<!-- resto de tu HTML igual -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../assets/css/style1.css">
</head>

<body>


    <div class="loading-page">
        <img id="svg" src="../assets/images/logo.png" alt="Logo">
        <div class="name-container">
            <div class="logo-name">EATSTECH</div>
        </div>
    </div>

<nav class="navbar">
    <div class="nav-container">
        <img src="../assets/images/logo.png" alt="Logo" class="nav-logo">
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">Servicios</a></li>
            <li><a href="#">Sobre Nosotros</a></li>
            <li><a href="#">Contáctanos</a></li>
        </ul>
        <div class="nav-buttons">
            
            <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true): ?>
        <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
        <a href="../modules/usuarios/logout.php" class="btn-login">Cerrar sesión</a>
    <?php else: ?>
        <a href="../modules/usuarios/iniciodesesion.php" class="btn-login">Iniciar sesión</a>
    <?php endif; ?>
</div>
    </div>
</nav>

    <div class="swiper mySwiper">

        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="icons">
                    <i class="fa-solid fa-circle-arrow-left"></i>
                    <img src="../assets/images/logo_producto(1).png" alt="">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <div class="product-content">
                    <div class="product-txt">
                        <h3>CASSAROLA</h3>
                        <p>
                            Somos el retaurante numero 1 de mosquera por lo cual todos los platos son elaborados
                            artesanalmente al momento de hacer su pedido, esto puede generar ligeras diferencias en el
                            tiempo de elaboración y la presentación de cada plato.

                        </p>
                    </div>
                    <div class="product-img">
                        <img src="data:image/png;base64,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"
                            alt="">

                    </div>
                </div>
                <a href="./casarolla.php" class="btn-1">entrar</a>
            </div>

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
                            cuadra del comando de la policía . en mosquera , cundinamarca.

                        </p>
                    </div>
                    <div class="product-img">
                        <img src="data:image/jpeg;base64,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"
                            alt="">

                    </div>
                </div>
                <a href="./index.html" class="btn-1">Proximamente</a>
            </div>

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
                            nos enorgullece ofrecer una experiencia culinaria única que combina la auténtica cocina de
                            autor con la comida tradicional colombiana. Mezclando matices, tendencias y sabores llegando
                            al punto de nuestra identidad propia.
                        </p>
                    </div>
                    <div class="product-img">
                        <img
                            src="data:image/jpeg;base64,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">

                    </div>
                </div>
                <a href="./index.html" class="btn-1">proximamente</a>
            </div>

            x
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"> </script>
    <script>

        var swiper = new Swiper(".mySwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,
            coverFlowEffect: {
                depth: 500,
                modifier: 1,
                slidesShadows: true,
                rotate: 0,
                stretch: 0
            }

        }

        );

        // Agrega la clase 'loading' al body al inicio
        document.body.classList.add("loading");

        gsap.fromTo(
            ".logo-name",
            { y: 50, opacity: 0 },
            { y: 0, opacity: 1, duration: 2, delay: 0.5 }
        );

        gsap.fromTo(
            "#svg",
            { scale: 0.5, opacity: 0 },
            { scale: 1, opacity: 1, duration: 1.5, ease: "back.out(1.7)" }
        );

        gsap.fromTo(
            ".loading-page",
            { opacity: 1 },
            {
                opacity: 0,
                duration: 1.5,
                delay: 2.5,
                onComplete: () => {
                    // Cuando termina la animación, oculta la pantalla
                    // y muestra el contenido
                    document.querySelector(".loading-page").style.display = "none";
                    document.querySelector(".swiper").style.visibility = "visible";
                    document.body.classList.remove("loading");
                }
            }
        );

    </script>


</body>

</html>