<?php
// AccionCarta.php
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
            // 🟢 SOLUCIÓN CRÍTICA: Guardamos en la sesión el restaurante_id al que pertenece este plato
            if (isset($row['restaurante_id'])) {
                $_SESSION['carrito_restaurante_id'] = intval($row['restaurante_id']);
            }

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
        
        // Si el carrito se queda vacío, limpiamos la variable del restaurante
        if ($cart->total_items() <= 0) {
            unset($_SESSION['carrito_restaurante_id']);
        }
        
        header("Location: ../menu/VerCarta");
        exit();
        
    } elseif ($action == 'placeOrder' && $cart->total_items() > 0) {
        
        if (empty($_SESSION['logueado']) || empty($_SESSION['correo'])) {
            header("Location: ../../pages/index"); 
            exit();
        }

        // 🟢 SOLUCIÓN CRÍTICA: Priorizamos el ID del restaurante donde se origina el carrito
        if (isset($_SESSION['carrito_restaurante_id'])) {
            $restauranteID = intval($_SESSION['carrito_restaurante_id']);
        } else {
            // Si por alguna razón no está en el carrito, usamos el de la sesión general
            $restauranteID = isset($_SESSION['restaurante_id']) ? intval($_SESSION['restaurante_id']) : 0;
        }

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
        $totalCart = $cart->total();

        // =========================================================================
        // 🚀 LÓGICA DE NEGOCIO EN DETALLE: CÁLCULO DE COMISIÓN ANTES DEL INSERT
        // =========================================================================
        $planRestaurante = 'start'; 
        $comisionPlataforma = 0.00;

        // Consultamos la columna real 'plan_id'
        $stmt_plan = $db->prepare("SELECT plan_id FROM restaurantes WHERE id = ? LIMIT 1");
        if ($stmt_plan) {
            $stmt_plan->bind_param("i", $restauranteID);
            $stmt_plan->execute();
            $res_plan = $stmt_plan->get_result();
            if ($row_plan = $res_plan->fetch_assoc()) {
                $planRestaurante = trim(strtolower((string)$row_plan['plan_id']));
            }
            $stmt_plan->close();
        }

        // Evaluamos el plan para asignar la comisión correcta
        if ($planRestaurante === 'start' || $planRestaurante === 'gratis' || $planRestaurante === '1') {
            $comisionPlataforma = $totalCart * 0.15; // 15% Plan Start
        } elseif ($planRestaurante === 'basic' || $planRestaurante === '2' || strpos($planRestaurante, 'basic') !== false) {
            $comisionPlataforma = $totalCart * 0.05; // 5% Plan Basic
        }
        // =========================================================================

        // 🚀 CAMBIO CRÍTICO: Agregamos 'comision_plataforma' al INSERT de la tabla 'orden'
        // Agregamos un "?" en los VALUES y una "d" en bind_param para el número decimal de la comisión
        $stmt_order = $db->prepare("INSERT INTO orden (customer_id, restaurante_id, total_price, comision_plataforma, created, modified, metodo_pago, nombre_cliente, telefono, direccion, correo_cliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_order->bind_param("iiddsssssss", $customerID, $restauranteID, $totalCart, $comisionPlataforma, $fechaActual, $fechaActual, $metodoPago, $nombreCliente, $telefonoCliente, $direccionCliente, $correoSession);
        
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

            // Agregamos restaurante_id y comision_plataforma al INSERT de 'pedidos_registrados' (Se mantiene como ya funcionaba)
            $stmt_pedidos = $db->prepare("INSERT INTO pedidos_registrados (restaurante_id, nombre_cliente, correo_cliente, telefono, direccion, resumen_productos, total_pagar, comision_plataforma, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_pedidos->bind_param("isssssdds", $restauranteID, $nombreCliente, $correoSession, $db_telefono, $db_direccion, $db_productos, $totalCart, $comisionPlataforma, $fechaActual);
            $stmt_pedidos->execute();
            $stmt_pedidos->close();

            // 🟢 Al finalizar el pedido con éxito, eliminamos la variable temporal
            unset($_SESSION['carrito_restaurante_id']);

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