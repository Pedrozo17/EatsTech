<?php
// include database configuration file
include 'Configuracion2.php';

// initializ shopping cart class
include 'La-carta2.php';
$cart = new Cart;

// redirect to home if cart is empty
if ($cart->total_items() <= 0) {
    header("Location: index2.php");
}

// set customer ID in session
$_SESSION['sessCustomerID'] = 1;

// get customer details by session customer ID
$query = $db->query("SELECT * FROM clientes WHERE id = " . $_SESSION['sessCustomerID']);
$custRow = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pagos - PHP Carrito de compras Tutorial</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .container {
            padding: 20px;
        }

        .table {
            width: 65%;
            float: left;
        }

        .shipAddr {
            width: 30%;
            float: left;
            margin-left: 30px;
        }

        .footBtn {
            width: 95%;
            float: left;
        }

        .orderBtn {
            float: right;
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
                    <li role="presentation"><a href="VerCarta2.php">Carrito de Compras</a></li>
                    <li role="presentation" class="active"><a href="Pagos2.php">Pagar</a></li>
                </ul>
            </div>

            <div class="panel-body">
                <h1>Vista previa de la Orden</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Pricio</th>
                            <th>Cantidad</th>
                            <th>Sub total</th>
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
                                    <td><?php echo $item["qty"]; ?></td>
                                    <td><?php echo '$' . $item["subtotal"] . ' COP'; ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="4">
                                    <p>No hay articulos en tu carta......</p>
                                </td>
                            <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <?php if ($cart->total_items() > 0) { ?>
                                <td class="text-center"><strong>Total <?php echo '$' . $cart->total() . ' COP'; ?></strong></td>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>
                <div class="shipAddr">
                    <h4>Detalles de envío</h4>
                    <p><?php echo $custRow['name']; ?></p>
                    <p><?php echo $custRow['email']; ?></p>
                    <p><?php echo $custRow['phone']; ?></p>
                    <p><?php echo $custRow['address']; ?></p>
                </div>
                <div class="footBtn">
                    <a href="index2.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continue Comprando</a>
                    <a href="AccionCarta2.php?action=placeOrder" class="btn btn-success orderBtn">Realizar pedido <i class="glyphicon glyphicon-menu-right"></i></a>
                </div>
            </div>
            
        <!--Panek cierra-->
    </div>
</body>

</html>