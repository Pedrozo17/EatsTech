<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

include_once 'control_plan.php';

// ==========================================================================
// SEGURIDAD & AUTENTICACIÓN
// ==========================================================================
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'empresa') { 
    header("Location: ../modules/usuarios/iniciodesesion"); 
    exit(); 
}

// ==========================================================================
// INCLUSIÓN DE CONEXIÓN
// ==========================================================================
$ruta_conexion = "../config/Configuracion.php"; 
if (!file_exists($ruta_conexion)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'No se encontró el archivo de conexión en el servidor.']);
    exit();
}
include($ruta_conexion);

// Detectamos la acción solicitada
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$id_restaurante_actual = (int)$dataRestaurante['restaurante_id'];

// ==========================================================================
// PROCESAMIENTO DE OPERACIONES CRUD
// ==========================================================================
switch ($action) {

    // 1. CREAR PRODUCTO
    case 'create_prod':
        $limite_productos = isset($dataRestaurante['limite_productos']) ? intval($dataRestaurante['limite_productos']) : 10;

        if ($limite_productos !== -1 && $total_productos_actuales >= $limite_productos) {
            die("<script>
                alert('⚠️ Límite de productos alcanzado. Tu plan actual no te permite registrar más de " . $limite_productos . " productos. Por favor, mejora tu suscripción en la sección de planes.');
                window.location.href = 'cambiar_plan.php';
            </script>");
        }

        $nombre = trim($_POST['nombre']);
        $desc   = trim($_POST['descripcion']);
        $precio = intval($_POST['precio']); 
        $stock  = intval($_POST['stock'] ?? 0);
        $nombre_imagen = ""; 

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['imagen']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            if ($fileExtension !== 'webp') {
                die("<script>
                    alert('⚠️ Por favor, sube tu archivo en formato WEBP para mejorar la experiencia de los clientes y optimizar la velocidad de la página.');
                    window.history.back();
                </script>");
            }
            
            $nombre_imagen = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", pathinfo($fileName, PATHINFO_FILENAME)) . '.' . $fileExtension;
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], '../assets/images/' . $nombre_imagen)) {
                $nombre_imagen = "";
            }
        }

        $stmt = $db->prepare("INSERT INTO mis_productos (restaurante_id, name, description, price, stock, imagen, status) VALUES (?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("issiis", $id_restaurante_actual, $nombre, $desc, $precio, $stock, $nombre_imagen);
        
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: admin_dashboard?seccion=productos");
            exit();
        } else {
            die("Error de Base de Datos al crear producto: " . $db->error);
        }
        break;

    // 2. ACTUALIZAR PRODUCTO
    case 'update_prod':
        $id     = intval($_POST['id']);
        $nombre = trim($_POST['nombre']);
        $desc   = trim($_POST['descripcion']);
        $precio = intval($_POST['precio']); 
        $stock  = intval($_POST['stock'] ?? 0);

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['imagen']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            if ($fileExtension !== 'webp') {
                die("<script>
                    alert('⚠️ Por favor, sube tu archivo en formato WEBP para mejorar la experiencia de los clientes y optimizar la velocidad de la página.');
                    window.history.back();
                </script>");
            }
            
            $nombre_imagen = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", pathinfo($fileName, PATHINFO_FILENAME)) . '.' . $fileExtension;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], '../assets/images/' . $nombre_imagen)) {
                $stmt = $db->prepare("UPDATE mis_productos SET name = ?, description = ?, price = ?, stock = ?, imagen = ? WHERE id = ? AND restaurante_id = ?");
                $stmt->bind_param("ssiisii", $nombre, $desc, $precio, $stock, $nombre_imagen, $id, $id_restaurante_actual);
            } else {
                die("Error al mover el nuevo archivo de imagen al servidor.");
            }
        } else {
            $stmt = $db->prepare("UPDATE mis_productos SET name = ?, description = ?, price = ?, stock = ? WHERE id = ? AND restaurante_id = ?");
            $stmt->bind_param("ssiiii", $nombre, $desc, $precio, $stock, $id, $id_restaurante_actual);
        }
        
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: admin_dashboard?seccion=productos");
            exit();
        } else {
            die("Error al actualizar producto: " . $db->error);
        }
        break;

    // 3. ELIMINAR PRODUCTO
    case 'delete_prod':
        $id = intval($_GET['id'] ?? 0);
        
        if ($id > 0) {
            $stmt = $db->prepare("DELETE FROM mis_productos WHERE id = ? AND restaurante_id = ?");
            $stmt->bind_param("ii", $id, $id_restaurante_actual);
            $stmt->execute();
            $stmt->close();
        }
        
        header("Location: admin_dashboard?seccion=productos");
        exit();
        break;

    // 4. CAMBIAR ESTADO DE PEDIDOS / ÓRDENES (AJAX)
    case 'update_status':
        header('Content-Type: application/json; charset=utf-8');
        ini_set('display_errors', 0);
        error_reporting(E_ALL);

        if (isset($_POST['id'], $_POST['tabla'], $_POST['estado'])) {
            $id = intval($_POST['id']);
            $tabla = preg_replace("/[^a-zA-Z0-9_]/", "", $_POST['tabla']); 
            $estado = trim($_POST['estado']);

            if ($tabla === 'pedidos_registrados') {
                $columna_estado = "estado"; 
            } elseif ($tabla === 'orden') {
                $columna_estado = "status";
            } else {
                echo json_encode(['success' => false, 'message' => 'Tabla no válida.']);
                exit();
            }

            $stmt = $db->prepare("UPDATE $tabla SET $columna_estado = ? WHERE id = ?");
            $stmt->bind_param("si", $estado, $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => '¡Estado actualizado con éxito!']);
            } else {
                echo json_encode(['success' => false, 'message' => "Error de MySQL: " . $db->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Parámetros POST incompletos.']);
        }
        exit();
        break;

    // 5. REGISTRAR EMPLEADO
    case 'registrar_empleado':
        $nombre = trim($_POST['nombre_empleado']);
        $correo = trim($_POST['correo_empleado']);
        $password_clara = trim($_POST['password_empleado']);
        $restaurante_id_asociado = (int)$_POST['restaurante_id'];

        // Sentencia preparada para comprobar duplicados de email de forma segura
        $stmt_check = $db->prepare("SELECT id FROM datos WHERE correo = ?");
        $stmt_check->bind_param("s", $correo);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            die("<script>
                alert('⚠️ El correo electrónico ya está registrado por otro usuario.');
                window.history.back();
            </script>");
        }
        $stmt_check->close();

        $password_encriptada = password_hash($password_clara, PASSWORD_BCRYPT);
        $tipo_usuario = 'empresa'; 

        $stmt = $db->prepare("INSERT INTO datos (nombre, correo, contraseña, tipo, restaurante_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nombre, $correo, $password_encriptada, $tipo_usuario, $restaurante_id_asociado);
        
        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>
                alert('🎉 ¡Colaborador vinculado exitosamente a tu sucursal!');
                window.location.href = 'admin_dashboard?seccion=empleados';
            </script>";
            exit();
        } else {
            die("❌ Error interno al registrar el empleado: " . $db->error);
        }
        break;

    default:
        header("Location: admin_dashboard");
        exit();
        break;
}
?>