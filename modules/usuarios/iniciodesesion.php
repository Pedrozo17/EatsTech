<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Eatstech\assets\css\estilo2.css">
    <script defer src="/Eatstech\assets\js\main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <title>Document</title>
</head>
<body>

    <div class="loading-page">
        <img id="svg" src="/Eatstech\assets\images\logo.png" alt="Logo">
        <div class="name-container">
            <div class="logo-name">EATSTECH</div>
        </div>
    </div>

<nav class="navbar">
    <div class="nav-container">
        <img src="\Eatstech\assets\images\logo.png" alt="Logo" class="nav-logo">
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
        
    <?php endif; ?>
</div>
    </div>
</nav>


    <div class="container right-panel-active">
        <!-- Sign Up -->
<div class="container__form container--signup">
    <h2 class="form__title">Sign up</h2>
    <form method="post" action="/Eatstech/modules/usuarios/registrar.php?redirect=<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : ''; ?>">
        <input class="input" type="text" name="nombre" placeholder="Nombre completo">
        <input class="input" type="correo" name="correo" placeholder="Correo">
        <input class="input" type="text" name="cedula" placeholder="Número de cédula">
        <input class="input" type="password" name="contraseña" placeholder="Contraseña">
        <input class="input" type="password" name="confirmar_contraseña" placeholder="Confirmar contraseña">
        <input class="btn" type="submit" name="register" value="Registrarse">
    </form>
</div>
    
        <!-- Sign In -->
        <div class=" container__form container--signin">
        <h2 class="form__title">Sign In</h2>
            <form method="post" action="/Eatstech/modules/usuarios/login.php?redirect=<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : ''; ?>">
                <input type="correo" name="correo" placeholder="correo">
                <input type="password" name="contraseña" placeholder="contraseña">
                <input type="submit" name="login">
            </form>
        </div>
    
        <!-- Overlay -->
        <div class="container__overlay">
            <div class="overlay">
                <div class="overlay__panel overlay--left">
                    <button class="btn" id="signIn">Sign In</button>
                </div>
                <div class="overlay__panel overlay--right">
                    <button class="btn" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                duration: 1.0,
                delay: 2.0,
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