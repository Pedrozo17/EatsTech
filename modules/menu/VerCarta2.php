<?php
// initializ shopping cart class
include './La-carta2.php';
$cart = new Cart;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Cart - PHP Shopping Cart Tutorial</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .container {
            padding: 20px;
        }

        input[type="number"] {
            width: 20%;

          }
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
</header>
<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">

                <ul class="nav nav-pills">
                    <li role="presentation"><a href="index2.php">Inicio</a></li>
                    <li role="presentation" class="active"><a href="VerCarta2.php">Carrito de Compras</a></li>
                    <li role="presentation"><a href="Pagos2.php">Pagar</a></li>
                  
                </ul>
            </div>

            <div class="panel-body">


                <h1>Carrito de compras</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Sub total</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($cart->total_items() > 0) {
                            //get cart items from session
                            $cartItems = $cart->contents();
                            foreach ($cartItems as $item) {
                        ?>
                                <tr>
                                    <td><?php echo $item["name"]; ?></td>
                                    <td><?php echo '$' . $item["price"] . ' COP'; ?></td>
                                    <td><input type="number" class="form-control text-center" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')"></td>
                                    <td><?php echo '$' . $item["subtotal"] . ' COP'; ?></td>
                                    <td>
                                        <a href="AccionCarta2.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>" class="btn btn-danger" onclick="return confirm('Confirma eliminar?')"><i class="glyphicon glyphicon-trash"></i></a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="5">
                                    <p>No has solicitado ningún producto.....</p>
                                </td>
                            <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><a href="index2.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Volver a la tienda</a></td>
                            <td colspan="2"></td>
                            <?php if ($cart->total_items() > 0) { ?>
                                <td class="text-center"><strong>Total <?php echo '$' . $cart->total() . ' COP'; ?></strong></td>
                                <td><a href="Pagos2.php" class="btn btn-success btn-block">Pagos <i class="glyphicon glyphicon-menu-right"></i></a></td>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>

            </div>
            
        </div>
        <!--Panek cierra-->

    </div>
</body>

</html>