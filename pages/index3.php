<?php
include '/config/Configuracion2.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Carrito de Compras</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
body {
  background-color: #474744; 
  background-image: "" 
 
}           

header {
  background-color: #FFFF00; /* color de fondo del encabezado */
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
  max-width: 1050px;
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
    <img src="/assets/images/logo_producto.png" class="logos">
  </div>
  <nav class="nav-pills">
    <!-- elementos de navegación -->
  </nav>
</header>
<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">

                <ul class="nav nav-pills">
                    <li role="presentation" class="active"><a href="/pages/index2.php">Inicio</a></li>
                    <li role="presentation"><a href="/modules/menu/VerCarta2.php">Carrito de Compras</a></li>
                    <li role="presentation"><a href="/modules/pagos/Pagos2.php">Pagar</a></li>
                   
                </ul>
            </div>

            <div class="panel-body">
                <h1>Nuestros Productos</h1>
                <a href="/modules/menu/VerCarta2.php" class="cart-link" title="Ver Carta"><i class="glyphicon glyphicon-shopping-cart"></i></a>
                <div id="products" class="row list-group">
                    <?php
                    //get rows query
                    $query = $db->query("SELECT * FROM mis_productos ORDER BY id DESC LIMIT 10");
                    if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {
                    ?>
                            <div class="item col-lg-4">
                                <div class="thumbnail">
                                    <div class="caption">
                                        <h4 class="list-group-item-heading"><?php echo $row["name"]; ?></h4>
                                        <p class="list-group-item-text"><?php echo $row["description"]; ?></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="lead"><?php echo '$' . $row["price"] . ' COP'; ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <a class="btn btn-success" href="/modules/carrito/AccionCarta2.php?action=addToCart&id=<?php echo $row["id"]; ?>">Enviar al Carrito</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <p>Producto(s) no existe.....</p>
                    <?php } ?>
                </div>
            </div>
        </div>
       
        <!--Panek cierra-->

    </div>
</body>

</html>