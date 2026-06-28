<?php
// Quitamos el session_start de aquí porque 'La-carta' ya lo inicia automáticamente
date_default_timezone_set("America/Bogota");

// Iniciamos la clase de la carta
include '../menu/La-carta.php';
$cart = new Cart;

// Include database configuration file (Conectado a la BD 'carrito')
include '../../config/Configuracion.php';

$action = $_REQUEST['action'] ?? '';

if (!empty($action)) {
    
    if ($action == 'addToCart' && !empty($_REQUEST['id'])) {
        $productID = intval($_REQUEST['id']);
        
        // Sentencia preparada para mayor seguridad
        $stmt = $db->prepare("SELECT * FROM mis_productos WHERE id = ?");
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        if ($row) {
            // Guardamos también la columna 'imagen' en la sesión del carrito
            $itemData = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'imagen' => $row['imagen'], 
                'qty' => 1
            );
            
            $insertItem = $cart->insert($itemData);
            $redirectLoc = $insertItem ? '../carrito/carritodecompras' : '../../pages/index';
            header("Location: " . $redirectLoc);
            exit();
        }
        
    } elseif ($action == 'updateCartItem' && !empty($_REQUEST['id'])) {
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
        echo $updateItem ? 'ok' : 'err';
        die;
        
    } elseif ($action == 'removeCartItem' && !empty($_REQUEST['id'])) {
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: ../menu/VerCarta");
        exit();
        
    } elseif ($action == 'placeOrder' && $cart->total_items() > 0) {
        
        if (empty($_SESSION['logueado']) || empty($_SESSION['correo'])) {
            header("Location: ../../pages/index"); 
            exit();
        }

        // 🟢 NUEVO: Capturamos el restaurante actual desde la sesión
        $restauranteID = isset($_SESSION['restaurante_id']) ? intval($_SESSION['restaurante_id']) : 0;

        $metodoPago = $_REQUEST['metodo'] ?? 'efectivo';
        $nombreCliente   = $_SESSION['nombre'] ?? '';
        $telefonoCliente = $_SESSION['telefono'] ?? '';
        $direccionCliente= $_SESSION['direccion'] ?? '';
        $correoSession   = $_SESSION['correo'];
        
        // Buscamos el ID del usuario
        $stmt_user = $db->prepare("SELECT id FROM datos WHERE correo = ?");
        $stmt_user->bind_param("s", $correoSession);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        
        if ($result_user && $result_user->num_rows > 0) {
            $clienteRow = $result_user->fetch_assoc();
            $customerID = $clienteRow['id']; 
        } else {
            $stmt_user->close();
            header("Location: ../pagos/Pagos?error=usuario_no_encontrado");
            exit();
        }
        $stmt_user->close();
        
        $fechaActual = date("Y-m-d H:i:s");

        // 🟢 NUEVO: Agregamos restaurante_id al INSERT de la tabla 'orden'
        $stmt_order = $db->prepare("INSERT INTO orden (customer_id, restaurante_id, total_price, created, modified, metodo_pago, nombre_cliente, telefono, direccion, correo_cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $totalCart = $cart->total();
        $stmt_order->bind_param("iidsssssss", $customerID, $restauranteID, $totalCart, $fechaActual, $fechaActual, $metodoPago, $nombreCliente, $telefonoCliente, $direccionCliente, $correoSession);
        
        if ($stmt_order->execute()) {
            $orderID = $db->insert_id;
            $stmt_order->close();
            
            $cartItems = $cart->contents();
            
            // Insertamos los artículos
            $stmt_items = $db->prepare("INSERT INTO orden_articulos (order_id, product_id, quantity) VALUES (?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmt_items->bind_param("iii", $orderID, $item['id'], $item['qty']);
                $stmt_items->execute();
            }
            $stmt_items->close();
            
            // Construimos la lista legible de productos
            $db_productos = "";
            foreach ($cartItems as $item) {
                $db_productos .= "• " . $item['name'] . " x" . $item['qty'] . " — $" . number_format($item['subtotal'], 0, ',', '.') . " COP\n";
            }
            
            $db_telefono  = $telefonoCliente ?: 'No registrado';
            $db_direccion = $direccionCliente ?: 'No registrada';

            // 🟢 NUEVO: Agregamos restaurante_id al INSERT de 'pedidos_registrados'
            $stmt_pedidos = $db->prepare("INSERT INTO pedidos_registrados (restaurante_id, nombre_cliente, correo_cliente, telefono, direccion, resumen_productos, total_pagar, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_pedidos->bind_param("isssssds", $restauranteID, $nombreCliente, $correoSession, $db_telefono, $db_direccion, $db_productos, $totalCart, $fechaActual);
            $stmt_pedidos->execute();
            $stmt_pedidos->close();

            $cart->destroy(); 
            header("Location: ../pagos/OrdenExito?id=" . $orderID);
            exit();
        } else {
            $stmt_order->close();
            header("Location: ../pagos/Pagos?error=orden");
            exit();
        }
    }
}

header("Location: ../../pages/index");
exit();
?>