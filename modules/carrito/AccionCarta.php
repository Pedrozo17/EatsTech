<?php
// Quitamos el session_start de aquí porque 'La-carta.php' ya lo inicia automáticamente
date_default_timezone_set("America/Bogota");

// Iniciamos la clase de la carta
include '../menu/La-carta.php';
$cart = new Cart;

// Include database configuration file (Conectado a la BD 'carrito')
include '../../config/Configuracion.php';

if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
    
    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])){
        $productID = $_REQUEST['id'];
        $query = $db->query("SELECT * FROM mis_productos WHERE id = ".$productID);
        $row = $query->fetch_assoc();
        
        // Guardamos también la columna 'imagen' en la sesión del carrito
        $itemData = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image' => $row['imagen'], 
            'qty' => 1
        );
        
        $insertItem = $cart->insert($itemData);
        $redirectLoc = $insertItem ? '../carrito/carritodecompras.php' : '../../pages/index.php';
        header("Location: ".$redirectLoc);
        exit();
        
    } elseif($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])){
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
        echo $updateItem ? 'ok' : 'err';
        die;
        
    } elseif($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: ../menu/VerCarta.php");
        exit();
        
    } elseif($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0){
        
        // 1. Verificamos si el usuario realmente está logueado según tu sesión
        if(empty($_SESSION['logueado']) || empty($_SESSION['correo'])){
            header("Location: ../../pages/index.php"); 
            exit();
        }

        // 2. Capturamos el método de pago que viene por la URL
        $metodoPago = isset($_REQUEST['metodo']) ? $db->real_escape_string($_REQUEST['metodo']) : 'efectivo';

        // 3. Obtenemos los datos del usuario desde la sesión para rellenar la tabla orden
        $nombreCliente   = isset($_SESSION['nombre']) ? $db->real_escape_string($_SESSION['nombre']) : '';
        $telefonoCliente = isset($_SESSION['telefono']) ? $db->real_escape_string($_SESSION['telefono']) : '';
        $direccionCliente= isset($_SESSION['direccion']) ? $db->real_escape_string($_SESSION['direccion']) : '';
        $correoSession   = $db->real_escape_string($_SESSION['correo']);
        
        // Buscamos el ID del usuario en la base de datos 'registro'
        $correoSeguro = $db->real_escape_string($correoSession);
        $buscarCliente = $db->query("SELECT id FROM datos WHERE correo = '$correoSeguro'");
        
        if($buscarCliente && $buscarCliente->num_rows > 0){
            $clienteRow = $buscarCliente->fetch_assoc();
            $customerID = $clienteRow['id']; 
        } else {
            header("Location: ../pagos/Pagos.php?error=usuario_no_encontrado");
            exit();
        }
        
        // Aseguramos la zona horaria correcta de Colombia
        $fechaActual = date("Y-m-d H:i:s");

        // 4. INSERT de la Orden Principal
        $insertOrder = $db->query("INSERT INTO orden (
            customer_id, total_price, created, modified, metodo_pago, nombre_cliente, telefono, direccion, correo_cliente
        ) VALUES (
            '".$customerID."', 
            '".$cart->total()."', 
            '".$fechaActual."', 
            '".$fechaActual."', 
            '".$metodoPago."', 
            '".$nombreCliente."', 
            '".$telefonoCliente."', 
            '".$direccionCliente."', 
            '".$correoSession."'
        )");
        

        if($insertOrder){
            $orderID = $db->insert_id;
            $sql = '';
            
            // Obtenemos los productos del carrito
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                $sql .= "INSERT INTO orden_articulos (order_id, product_id, quantity) VALUES ('".$orderID."', '".$item['id']."', '".$item['qty']."');";
            }
            
            // Insertamos los artículos en lote
            $insertOrderItems = $db->multi_query($sql);
            
            if($insertOrderItems){
                
                while($db->more_results() && $db->next_result());
                
                $db_nombre    = $db->real_escape_string($nombreCliente);
                $db_correo    = $db->real_escape_string($correoSession);
                $db_telefono  = $db->real_escape_string($telefonoCliente ?: 'No registrado');
                $db_direccion = $db->real_escape_string($direccionCliente ?: 'No registrada');
                $db_total     = $cart->total();
                
                // Construimos la lista legible de productos
                $db_productos = "";
                foreach($cartItems as $item){
                    $db_productos .= "• " . $item['name'] . " x" . $item['qty'] . " — $" . number_format($item['subtotal'], 0, ',', '.') . " COP\n";
                }
                $db_productos = $db->real_escape_string($db_productos);

                // Ejecutamos el query directo en tu nueva tabla sin conflictos
                $db->query("INSERT INTO pedidos_registrados (
                    nombre_cliente, correo_cliente, telefono, direccion, resumen_productos, total_pagar, fecha_registro
                ) VALUES (
                    '$db_nombre', '$db_correo', '$db_telefono', '$db_direccion', '$db_productos', '$db_total', '$fechaActual'
                )");
                // =========================================================================

                $cart->destroy(); // Vaciamos el carrito
                header("Location: ../pagos/OrdenExito.php?id=" . $orderID);
                exit();
            } else {
                header("Location: ../pagos/Pagos.php?error=articulos");
                exit();
            }
        } else {
            header("Location: ../pagos/Pagos.php?error=orden");
            exit();
        }
    } else {
        header("Location: ../../pages/index.php");
        exit();
    }
} else {
    header("Location: ../../pages/index.php");
    exit();
}
?>