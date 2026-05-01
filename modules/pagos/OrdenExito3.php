<?php
if (!isset($_REQUEST['id'])) {
  header("Location: index2.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <title>Orden Completado - PHP Carrito de Compras</title>
  <meta charset="utf-8">
  <style>
    body {
  background-color: #474744; 
  background-image: "" 
 
}           

header {
  background-color: #b38061; /* color de fondo del encabezado */
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.logo-container {
  display: flex;
  align-items: center;
}

.logo {
  width: 50px; /* ancho del logo */
  height: 50px; /* alto del logo */
  margin-right: 20px;
}
.logo-containerr {
  display: flex;
  align-items: center;
  justify-content: flex-end; /* para que el logo esté en la parte superior derecha */
}
.logos {
  width: 50px; /* ancho del logo derecho */
  height: 50px; /* alto del logo derecho */
  margin-left: 20px;
}
.container {
  max-width: 1000px;
  margin: 40px auto;
  text-align: center;
  padding: 20px;
  border: 1px solid #E5B8A3; 
  border-radius: 10px; 
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  font-family: 'Great Vibes', cursive;
}


.title {
  font-size: 36px;
  font-weight: bold;
  margin-bottom: 20px;
  color: #292825; /* color marrón oscuro de Cassarola */
}

.nav-pills {
  list-style: none;
  padding: 0;
  margin: 0;
 
}

.nav-pills li {
  margin-right: 20px;
}

.nav-pills li a {
  color: #292825;
  text-decoration: none;
}

.nav-pills li a:hover {
  color:#292825;
}

.nav-pills {
  position: absolute;
  top: 0;
  right: 0;
  margin-top: 10px;
  margin-right: 20px;
}

.nav-pills li {
  display: inline-block;
  margin-right: 20px;
}

.nav-pills li a {
  color:#292825;
  text-decoration: none;
}

.nav-pills li a:hover {
  color:#292825;
}

.cart-link {
  position: absolute;
  top: 0;
  right: 0;
  margin-top: 10px;
  margin-right: 40px;
  font-size: 22px;
  color:#292825;
}

.cart-link:hover {
  color: #292825;
}
.cart-link {
  position: absolute;
  top: 0;
  right: 0;
  margin-top: 10px;
  margin-right: 40px;
  font-size: 22px;
  color: #292825;
}

.cart-link:hover {
  color: #292825;
}

    </style>
</head>
</head>
<header>
  <div class="logo-container">
    <img src="https://images.rappi.com/restaurants_logo/900404749-1712883258282.png?e=webp&d=10x10&q=10" class="logo">
    <div class="logo-containerr">
    <img src="logo_producto.png" class="logos">
  </div>
  <nav class="nav-pills">
    <!-- elementos de navegación -->
  </nav>
</style>
</header>
<body>
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">

        <ul class="nav nav-pills">
          <li role="presentation" class="active"><a href="index2.php">Volver</a></li>
      
      </div>

      <div class="panel-body">

        <h1>Estado de tu Requerimiento</h1>
        <p>La Orden se ha enviado exitósamente. El ID de tu pedido es <?php echo $_GET['id']; ?></p>
      </div>
      
    </div>
    <!--Panek cierra-->
  </div>
</body>

</html>