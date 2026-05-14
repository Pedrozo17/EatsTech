<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Camaron Express | EATSTECH</title>
  <meta name="title" content="Camaron Express | EATSTECH">

  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../assets/css/style.css">

  <link rel="preload" as="image" href="../assets/images/hero-slider-1.jpg">
  <link rel="preload" as="image" href="../assets/images/hero-slider-2.jpg">
  <link rel="preload" as="image" href="../assets/images/hero-slider-3.jpg">

  
</head>

<body id="top">

  <div class="preload" data-preaload>
    <div class="circle"></div>
    <p class="text">CAMARON EXPRESS</p>
  </div>

  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-container">
      <span class="topbar-label">Restaurantes</span>
      <div class="topbar-links">

        <a href="/Eatstech/pages/casarolla.php" class="topbar-item active" title="Camaron Express">
          <img src="\Eatstech\assets\images\logo_empresa.png" alt="Camaron Express">
          <span>Camaron Express</span>
        </a>

        <a href="/Eatstech/pages/fogon.php" class="topbar-item proximamente" title="Fogón Antioqueño">
          <img src="../assets/images/logo_fogon.png" alt="Fogón Antioqueño">
          <span>Fogón</span>
        </a>

        <a href="#" class="topbar-item proximamente" title="Toskana - Próximamente">
          <img src="../assets/images/logo_toskana.png" alt="Toskana">
          <span>Toskana</span>
        </a>

      </div>
    </div>
  </div>

  <!-- HEADER -->
  <header class="header" data-header>
    <div class="container">

      <a href="#" class="logo" >
        <img src="\Eatstech\assets\images\logo_empresa-removebg-preview.png" alt="EATSTECH - Home">
      </a>

      <nav class="navbar" data-navbar>

        <button class="close-btn" aria-label="close menu" data-nav-toggler>
          <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
        </button>

        <a href="#" class="logo">
          <img src="\Eatstech\assets\images\logo_empresa-removebg-preview.png" alt="EATSTECH - Home">
        </a>

        <ul class="navbar-list">
          <li class="navbar-item">
            <a href="#home" class="navbar-link hover-underline active">
              <div class="separator"></div>
              <span class="span">Home</span>
            </a>
          </li>
          <li class="navbar-item">
            <a href="#menu" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="span">Menú</span>
            </a>
          </li>
          <li class="navbar-item">
            <a href="#about" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="span">Sobre Nosotros</span>
            </a>
          </li>
          <li class="navbar-item">
            <a href="#reservation" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="span">Contacto</span>
            </a>
          </li>
          <li class="navbar-item">
            <a href="./index.php" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="span">Volver</span>
            </a>
          </li>
        </ul>

        <div class="text-center">
          <p class="headline-1 navbar-title">Visitanos</p>

          <address class="body-4">
            Calle 20 #5-94<br>
            Mosquera, Cundinamarca
          </address>

          <p class="body-4 navbar-text">
            Lun–Jue: 5pm – 9pm<br>
            Vie–Dom: 2pm – 9pm
          </p>

          <a href="mailto:eatstech24@gmail.com" class="body-4 sidebar-link">eatstech24@gmail.com</a>

          <div class="separator"></div>

          <p class="contact-label">Reservas</p>

          <a href="tel:+573248933841" class="body-1 contact-number hover-underline">
            +57 324 893 3841
          </a>
        </div>

      </nav>

      <!-- Botón sesión -->
      <a href="/Eatstech/modules/usuarios/iniciodesesion.php?redirect=casarolla" class="btn btn-secondary">
        <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true): ?>
          <span class="text text-1">👤 <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
          <span class="text text-2" aria-hidden="true">👤 <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
        <?php else: ?>
          <span class="text text-1">Iniciar sesión</span>
          <span class="text text-2" aria-hidden="true">Iniciar sesión</span>
        <?php endif; ?>
      </a>

      <?php if (isset($_SESSION['logueado']) && $_SESSION['logueado'] === true): ?>
        <a href="/Eatstech/modules/usuarios/logout.php?from=/Eatstech/pages/casarolla.php" class="btn btn-secondary">
          <span class="text text-1">Cerrar sesión</span>
          <span class="text text-2" aria-hidden="true">Cerrar sesión</span>
        </a>
      <?php endif; ?>

      <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
        <span class="line line-1"></span>
        <span class="line line-2"></span>
        <span class="line line-3"></span>
      </button>

      <div class="overlay" data-nav-toggler data-overlay></div>

    </div>
  </header>


  <main>
    <article>

      <!-- HERO -->
      <section class="hero text-center" aria-label="home" id="home">
        <ul class="hero-slider" data-hero-slider>

          <li class="slider-item active" data-hero-slider-item>
            <div class="slider-bg">
              <img src="\Eatstech\assets\images\foto-slider.png" width="1880" height="950" alt="" class="img-cover">
            </div>
            <p class="label-2 section-subtitle slider-reveal">Camaron Express</p>
            <h1 class="display-1 hero-title slider-reveal">
              Cocteles de camarón<br>que enamoran
            </h1>
            <p class="body-2 hero-text slider-reveal">
              Recetas tradicionales e innovaciones de la costa colombiana, directo a tu mesa.
            </p>
            <a href="#menu" class="btn btn-primary slider-reveal">
              <span class="text text-1">Ver nuestro menú</span>
              <span class="text text-2" aria-hidden="true">Ver nuestro menú</span>
            </a>
          </li>

          <li class="slider-item" data-hero-slider-item>
            <div class="slider-bg">
              <img src="\Eatstech\assets\images\foto-slider2.png" width="1880" height="950" alt="" class="img-cover">
            </div>
            <p class="label-2 section-subtitle slider-reveal">Sabor de la Costa</p>
            <h1 class="display-1 hero-title slider-reveal">
              Innovaciones traídas<br>desde Armenia
            </h1>
            <p class="body-2 hero-text slider-reveal">
              Cada receta lleva un viaje, una historia y el mejor camarón.
            </p>
            <a href="#about" class="btn btn-primary slider-reveal">
              <span class="text text-1">Conoce nuestra historia</span>
              <span class="text text-2" aria-hidden="true">Conoce nuestra historia</span>
            </a>
          </li>

          <li class="slider-item" data-hero-slider-item>
            <div class="slider-bg">
              <img src="\Eatstech\assets\images\foto-slider4.png" width="1880" height="950" alt="" class="img-cover">
            </div>
            <p class="label-2 section-subtitle slider-reveal">Mosquera, Cundinamarca</p>
            <h1 class="display-1 hero-title slider-reveal">
              Una oportunidad<br>hecha negocio
            </h1>
            <p class="body-2 hero-text slider-reveal">
              Ven con tu familia y disfruta de una experiencia única en la sabana.
            </p>
            <a href="#reservation" class="btn btn-primary slider-reveal">
              <span class="text text-1">Hacer una reserva</span>
              <span class="text text-2" aria-hidden="true">Hacer una reserva</span>
            </a>
          </li>

        </ul>

        <button class="slider-btn prev" aria-label="slide to previous" data-prev-btn>
          <ion-icon name="chevron-back"></ion-icon>
        </button>
        <button class="slider-btn next" aria-label="slide to next" data-next-btn>
          <ion-icon name="chevron-forward"></ion-icon>
        </button>

        <a href="#menu" class="hero-btn has-after">
          <img src="../assets/images/hero-icon.png" width="48" height="48" alt="menu icon">
          <span class="label-2 text-center span">Ver el menú</span>
        </a>
      </section>


      <!-- SERVICE -->
      <section class="section service bg-black-10 text-center" aria-label="service">
        <div class="container">

          <p class="section-subtitle label-2">Sabores de la Costa</p>
          <h2 class="headline-1 section-title">Lo mejor del camarón en la sabana</h2>
          <p class="section-text">
            En Camaron Express nos especializamos en cocteles de camarón con recetas tradicionales
            e innovaciones constantes traídas de la costa colombiana y de Armenia.
          </p>

          <ul class="grid-list">
            <li>
              <div class="service-card">
                <a href="#menu" class="has-before hover:shine">
                  <figure class="card-banner img-holder" style="--width: 285; --height: 336;">
                    <img src="\Eatstech\assets\images\coctel intermedio.png" width="285" height="336" loading="lazy"
                      alt="Cocteles" class="img-cover">
                  </figure>
                </a>
                <div class="card-content">
                  <h3 class="title-4 card-title"><a href="#menu">Cocteles</a></h3>
                  <a href="#menu" class="btn-text hover-underline label-2">Ver Menú</a>
                </div>
              </div>
            </li>
            <li>
              <div class="service-card">
                <a href="#menu" class="has-before hover:shine">
                  <figure class="card-banner img-holder" style="--width: 285; --height: 336;">
                    <img src="\Eatstech\assets\images\Sánguche de camaron.png" width="285" height="336" loading="lazy"
                      alt="Entradas" class="img-cover">
                  </figure>
                </a>
                <div class="card-content">
                  <h3 class="title-4 card-title"><a href="#menu">Platos</a></h3>
                  <a href="#menu" class="btn-text hover-underline label-2">Ver Menú</a>
                </div>
              </div>
            </li>
            <li>
              <div class="service-card">
                <a href="#menu" class="has-before hover:shine">
                  <figure class="card-banner img-holder" style="--width: 285; --height: 336;">
                    <img src="\Eatstech\assets\images\cocacola.png" width="285" height="336" loading="lazy"
                      alt="Bebidas" class="img-cover">
                  </figure>
                </a>
                <div class="card-content">
                  <h3 class="title-4 card-title"><a href="#menu">Bebidas</a></h3>
                  <a href="#menu" class="btn-text hover-underline label-2">Ver Menú</a>
                </div>
              </div>
            </li>
          </ul>

          <img src="../assets/images/shape-1.png" width="246" height="412" loading="lazy" alt="shape" class="shape shape-1 move-anim">
          <img src="../assets/images/shape-2.png" width="343" height="345" loading="lazy" alt="shape" class="shape shape-2 move-anim">

        </div>
      </section>


      <!-- ABOUT -->
      <section class="section about text-center" aria-labelledby="about-label" id="about">
        <div class="container">

          <div class="about-content">

            <p class="label-2 section-subtitle" id="about-label">Quiénes somos</p>
            <h2 class="headline-1 section-title">Un espacio para el sabor del camarón</h2>

            <p class="section-text">
              Somos un espacio enfocado en los cocteles de camarón, basándonos en recetas tradicionales
              así como en innovaciones constantes traídas de otros departamentos y de la costa colombiana.
            </p>

            <p class="section-text" style="margin-block-start: 16px;">
              Nuestro proyecto surgió por la necesidad de tener un ingreso adicional en casa.
              Nos inspiramos en viajes a Armenia donde encontramos distintas innovaciones en este
              tipo de producto, viendo así una gran oportunidad en la comunidad de la sabana.
            </p>

            <div class="contact-label" style="margin-block-start: 20px;">Reservar por llamada</div>
            <a href="tel:+573248933841" class="body-1 contact-number hover-underline">+57 324 893 3841</a>

            <a href="#menu" class="btn btn-primary" style="margin-block-start: 26px;">
              <span class="text text-1">Ver el menú</span>
              <span class="text text-2" aria-hidden="true">Ver el menú</span>
            </a>

          </div>

          <figure class="about-banner">
            <img src="\Eatstech\assets\images\foto-complemento.png" width="570" height="570" loading="lazy"
              alt="about banner" class="w-100" data-parallax-item data-parallax-speed="1">
            <div class="abs-img abs-img-1 has-before" data-parallax-item data-parallax-speed="1.75">
              <img src="\Eatstech\assets\images\foto-complemento2.png" width="285" height="285" loading="lazy"
                alt="" class="w-100">
            </div>
            <div class="abs-img abs-img-2 has-before">
              <img src="../assets/images/badge-2.png" width="133" height="134" loading="lazy" alt="">
            </div>
          </figure>

          <img src="../assets/images/shape-3.png" width="197" height="194" loading="lazy" alt="" class="shape">

        </div>
      </section>


      <!-- PLATO ESPECIAL — CAMARONES -->
      <section class="special-dish text-center" aria-labelledby="dish-label">

        <div class="special-dish-banner">
          <img src="\Eatstech\assets\images\Camarones — Los Camarítos.png" width="940" height="900" loading="lazy"
            alt="Camarones" class="img-cover">
        </div>

        <div class="special-dish-content bg-black-10">
          <div class="container">

            <p class="section-subtitle label-2">Nuestro más vendido</p>
            <h2 class="headline-1 section-title">Camarones — Los Camarítos</h2>

            <p class="section-text">
              Nuestros camarones son la estrella del menú. Preparados al momento con recetas
              de la costa colombiana, cada coctel tiene el balance perfecto entre frescura,
              sazón y tradición. ¡El favorito de la casa!
            </p>

            <div class="wrapper">
              <span class="span body-1">Desde $24.000</span>
            </div>

            <a href="https://wa.me/c/573248933841" target="_blank" class="btn btn-primary">
              <span class="text text-1">Ver catálogo completo</span>
              <span class="text text-2" aria-hidden="true">Ver catálogo completo</span>
            </a>

          </div>
        </div>

        <img src="../assets/images/shape-4.png" width="179" height="359" loading="lazy" alt="" class="shape shape-1">

      </section>


      <!-- MENU -->
      <section class="section menu" aria-label="menu-label" id="menu">
        <div class="container">

          <p class="section-subtitle text-center label-2">Selección especial</p>
          <h2 class="headline-1 section-title text-center">Menú Camaron Express</h2>

          <ul class="grid-list">

            <li>
              <div class="menu-card hover:card">
                <figure class="card-banner img-holder" style="--width: 100; --height: 100;">
                  <img src="\Eatstech\assets\images\Camarones — Los Camarítos.png"
                    alt="Coctel de Camarón" class="img-menu">
                </figure>
                <div>
                  <div class="title-wrapper">
                    <h3 class="title-3"><a href="#" class="card-title">Camarones Camaclitos</a></h3>
                    <span class="badge label-1">Más vendido</span>
                    <span class="span title-2">Desde $22.000</span>
                  </div>
                  <p class="card-text label-1">
                      Un bowl con una cama de lechuga, pico de gallo, aguacate y choclitos.
                  </p>
                </div>
              </div>
            </li>

            <li>
              <div class="menu-card hover:card">
                <figure class="card-banner img-holder" style="--width: 100; --height: 100;">
                  <img src="\Eatstech\assets\images\Sánguche de camaron.png" 
                    alt="Coctel Armenio" class="img-menu">
                </figure>
                <div>
                  <div class="title-wrapper">
                    <h3 class="title-3"><a href="#" class="card-title">Sánguche de camaron</a></h3>
                    <span class="badge label-1">Innovación</span>
                    <span class="span title-2">Desde $18.000</span>
                  </div>
                  <p class="card-text label-1">
                    Camarones, aguacate, salsa de la casa, queso, tomate, lechuga
                  </p>
                </div>
              </div>
            </li>

            <li>
              <div class="menu-card hover:card">
                <figure class="card-banner img-holder" style="--width: 100; --height: 100;">
                  <img src="\Eatstech\assets\images\Cardumen de Camarón 12oz.png"
                    alt="Coctel Costa Caribe" class="img-menu">
                </figure>
                <div>
                  <div class="title-wrapper">
                    <h3 class="title-3"><a href="#" class="card-title">Cardumen de Camarón 12oz</a></h3>
                    <span class="span title-2">Desde $26.000</span>
                  </div>
                  <p class="card-text label-1">
                    Vaso 12onz Camarones Aguacate Mango salsa de la casa Galletas saltin
                  </p>
                </div>
              </div>
            </li>

            <li>
              <div class="menu-card hover:card">
                <figure class="card-banner img-holder" style="--width: 100; --height: 100;">
                  <img src="\Eatstech\assets\images\Camaclitos.png" 
                    alt="Porción Especial" class="img-menu">
                </figure>
                <div>
                  <div class="title-wrapper">
                    <h3 class="title-3"><a href="#" class="card-title">Camaclitos</a></h3>
                    <span class="badge label-1">Nuevo</span>
                    <span class="span title-2">Desde $22.000</span>
                  </div>
                  <p class="card-text label-1">
                    un bowl con una cama de lechuga, pico de gallo, aguacate y choclitos
                  </p>
                </div>
              </div>
            </li>

            <li>
              <div class="menu-card hover:card">
                <figure class="card-banner img-holder" style="--width: 100; --height: 100;">
                  <img src="\Eatstech\assets\images\coctel intermedio.png" 
                    alt="Coctel Light" class="img-menu">
                </figure>
                <div>
                  <div class="title-wrapper">
                    <h3 class="title-3"><a href="#" class="card-title">Coctel intermedio 9oz</a></h3>
                    <span class="span title-2">Desde $20.000</span>
                  </div>  
                  <p class="card-text label-1">
                    Un vaso 9onz con Camarones Salsa de la casa Mango o aguacate ( Tu eliges ) Galletas saltin
                  </p>
                </div>
              </div>
            </li>

            <li>
              <div class="menu-card hover:card">
                <figure class="card-banner img-holder" style="--width: 100; --height: 100;">
                  <img src="\Eatstech\assets\images\Coctel tradicional 7oz.png" 
                    alt="Combo Familiar" class="img-menu">
                </figure>
                <div>
                  <div class="title-wrapper">
                    <h3 class="title-3"><a href="#" class="card-title">Coctel tradicional 7oz</a></h3>
                    <span class="span title-2">Desde $15.000</span>
                  </div>
                  <p class="card-text label-1">
                    Un vaso 7onz con Camarones Salsa de la casa Galletas Saltin
                  </p>
                </div>
              </div>
            </li>

          </ul>

          <p class="menu-text text-center">
            Disponible de <span class="span">lunes a jueves 5pm–9pm</span> y
            <span class="span">viernes a domingo 2pm–9pm</span>
          </p>

          <a href="\Eatstech\modules\carrito\carritodecompras.php" target="_blank" class="btn btn-primary">
            <span class="text text-1">Ver menú</span>
          </a>

          <img src="../assets/images/shape-5.png" width="921" height="1036" loading="lazy" alt="shape" class="shape shape-2 move-anim">
          <img src="../assets/images/shape-6.png" width="343" height="345" loading="lazy" alt="shape" class="shape shape-3 move-anim">

        </div>
      </section>


      <!-- TESTIMONIALS -->
      <section class="section testi text-center has-bg-image"
        style="background-image: url('../assets/images/foto-slider2.png')" aria-label="testimonials">
        <div class="container">

          <div class="quote">"</div>

          <p class="headline-2 testi-text">
            "Los camarones de Camaron Express son una experiencia única.
            Cada coctel tiene el sabor exacto de la costa, con un toque especial
            que no encuentras en ningún otro lugar de Mosquera."
          </p>

          <div class="wrapper">
            <div class="separator"></div>
            <div class="separator"></div>
            <div class="separator"></div>
          </div>

          <div class="profile">
            <img src="../assets/images/testi.jpg" width="100" height="100" loading="lazy"
              alt="Cliente satisfecho" class="img">
            <p class="label-2 profile-name">Cliente satisfecho</p>
          </div>

        </div>
      </section>


      <!-- RESERVATION / CONTACTO -->
      <section class="reservation" id="reservation">
        <div class="container">

          <div class="form reservation-form bg-black-10">

            <form method="post" action="../config/conx.php" class="form-left">

              <h2 class="headline-1 text-center">Reservación Online</h2>

              <p class="form-text text-center">
                Solicitar reserva al
                <a href="tel:+573248933841" class="link">+57 324 893 3841</a>
                o llena el formulario
              </p>

              <div class="input-wrapper">
                <input type="text" name="nombre" placeholder="Tu Nombre" autocomplete="off" class="input-field">
                <input type="tel" name="celular" placeholder="Número de Celular" autocomplete="off" class="input-field">
              </div>

              <div class="input-wrapper">
                <div class="icon-wrapper">
                  <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                  <select name="personas" class="input-field">
                    <option value="1-person">1 Persona</option>
                    <option value="2-person">2 Personas</option>
                    <option value="3-person">3 Personas</option>
                    <option value="4-person">4 Personas</option>
                    <option value="5-person">5 Personas</option>
                    <option value="6-person">6 Personas</option>
                    <option value="7-person">7 Personas</option>
                  </select>
                  <ion-icon name="chevron-down" aria-hidden="true"></ion-icon>
                </div>

                <div class="icon-wrapper">
                  <ion-icon name="calendar-clear-outline" aria-hidden="true"></ion-icon>
                  <input type="date" name="fecha" class="input-field">
                  <ion-icon name="chevron-down" aria-hidden="true"></ion-icon>
                </div>

                <div class="icon-wrapper">
                  <ion-icon name="time-outline" aria-hidden="true"></ion-icon>
                  <select name="hora" class="input-field">
                    <option value="17:00">05:00 pm</option>
                    <option value="18:00">06:00 pm</option>
                    <option value="19:00">07:00 pm</option>
                    <option value="20:00">08:00 pm</option>
                    <option value="21:00">09:00 pm</option>
                  </select>
                  <ion-icon name="chevron-down" aria-hidden="true"></ion-icon>
                </div>
              </div>

              <textarea name="mensaje" placeholder="Mensaje o pedido especial" autocomplete="off" class="input-field"></textarea>

              <button type="submit" class="btn btn-secondary">
                <span class="text text-1">Reservar una mesa</span>
                <span class="text text-2" aria-hidden="true">Reservar una mesa</span>
              </button>

            </form>

            <div class="form-right text-center">

              <h2 class="headline-1 text-center">Contactanos</h2>

              <p class="contact-label">WhatsApp / Llamadas</p>
              <a href="tel:+573248933841" class="body-1 contact-number hover-underline">+57 324 893 3841</a>

              <div class="separator" style="margin: 20px auto;"></div>

              <p class="contact-label">Ubicación</p>
              <address class="body-4">
                Calle 20 #5-94<br>
                Mosquera, Cundinamarca
              </address>

              <div class="separator" style="margin: 20px auto;"></div>

              <p class="contact-label">Horarios</p>
              <div class="horarios-grid">
                <div class="horario-item">
                  <p class="dia">Lunes – Jueves</p>
                  <p class="hora">5:00 pm – 9:00 pm</p>
                </div>
                <div class="horario-item">
                  <p class="dia">Viernes – Domingo</p>
                  <p class="hora">2:00 pm – 9:00 pm</p>
                </div>
              </div>

              <div class="separator" style="margin: 20px auto;"></div>

              <p class="contact-label">Síguenos</p>
              <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=camaronexpressdeliverysc"
                   target="_blank" class="social-link">
                  <ion-icon name="logo-facebook"></ion-icon>
                  <span>Camaron Express Delivery SC</span>
                </a>
              </div>
              <div class="social-links">
                <a href="https://www.instagram.com/camaron_express_delivery"
                   target="_blank" class="social-link">
                  <ion-icon name="logo-instagram"></ion-icon>
                  <span>camaron_express_delivery</span>
                </a>
              </div>

            </div>

          </div>

        </div>
      </section>


      <!-- FEATURES -->
      <section class="section features text-center" aria-label="features">
        <div class="container">

          <p class="section-subtitle label-2">Por qué elegirnos</p>
          <h2 class="headline-1 section-title">Nuestra fuerza</h2>

          <ul class="grid-list">
            <li class="feature-item">
              <div class="feature-card">
                <div class="card-icon">
                  <img src="../assets/images/features-icon-1.png" width="100" height="80" loading="lazy" alt="icon">
                </div>
                <h3 class="title-2 card-title">Recetas de la Costa</h3>
                <p class="label-1 card-text">Cada coctel está basado en recetas tradicionales de la costa colombiana.</p>
              </div>
            </li>
            <li class="feature-item">
              <div class="feature-card">
                <div class="card-icon">
                  <img src="../assets/images/features-icon-2.png" width="100" height="80" loading="lazy" alt="icon">
                </div>
                <h3 class="title-2 card-title">Innovación constante</h3>
                <p class="label-1 card-text">Incorporamos nuevas recetas de Armenia y otras regiones del país.</p>
              </div>
            </li>
            <li class="feature-item">
              <div class="feature-card">
                <div class="card-icon">
                  <img src="../assets/images/features-icon-3.png" width="100" height="80" loading="lazy" alt="icon">
                </div>
                <h3 class="title-2 card-title">Producto fresco</h3>
                <p class="label-1 card-text">Usamos camarón fresco para garantizar el mejor sabor en cada porción.</p>
              </div>
            </li>
            <li class="feature-item">
              <div class="feature-card">
                <div class="card-icon">
                  <img src="../assets/images/features-icon-4.png" width="100" height="80" loading="lazy" alt="icon">
                </div>
                <h3 class="title-2 card-title">Sabor local</h3>
                <p class="label-1 card-text">Un negocio nacido en Mosquera para la comunidad de la sabana.</p>
              </div>
            </li>
          </ul>

          <img src="../assets/images/shape-7.png" width="208" height="178" loading="lazy" alt="shape" class="shape shape-1">
          <img src="../assets/images/shape-8.png" width="120" height="115" loading="lazy" alt="shape" class="shape shape-2">

        </div>
      </section>

    </article>
  </main>

  <!-- BACK TO TOP -->
  <a href="#top" class="back-top-btn active" aria-label="back to top" data-back-top-btn>
    <ion-icon name="chevron-up" aria-hidden="true"></ion-icon>
  </a>

  <script src="../assets/js/script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>