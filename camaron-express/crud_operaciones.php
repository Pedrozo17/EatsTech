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
// INCLUSIÓN DE CONEXIÓN (Ruta corregida)
// ==========================================================================
$ruta_conexion = "../config/Configuracion.php"; 
if (!file_exists($ruta_conexion)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'No se encontró el archivo de conexión en el servidor.']);
    exit();
}
include($ruta_conexion);

// Detectamos la acción por POST o por GET
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// ==========================================================================
// 1. CREAR NUEVO PLATO (Con Stock e Imagen Incluida)
// ==========================================================================
if ($action === 'create_prod') {

    // 🔒 RESTRICCIÓN DE MODELO SAAS: LÍMITE DE PRODUCTOS
    $limite_productos = isset($dataRestaurante['limite_productos']) ? intval($dataRestaurante['limite_productos']) : 10;
    $id_restaurante_actual = (int)$dataRestaurante['restaurante_id'];

    if ($limite_productos !== -1) {
        if ($total_productos_actuales >= $limite_productos) {
            die("<script>
                alert('⚠️ Límite de productos alcanzado. Tu plan actual no te permite registrar más de " . $limite_productos . " productos. Por favor, mejora tu suscripción en la sección de planes.');
                window.location.href = 'cambiar_plan.php';
            </script>");
        }
    }

    $nombre = trim($_POST['nombre']);
    $desc   = trim($_POST['descripcion']);
    $precio = intval($_POST['precio']); 
    $stock  = intval($_POST['stock'] ?? 0);

    $nombre_imagen = ""; 

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        if ($fileExtension !== 'webp') {
            die("<script>
                alert('⚠️ Por favor, sube tu archivo en formato WEBP para mejorar la experiencia de los clientes y optimizar la velocidad de la página.');
                window.history.back();
            </script>");
        }
        
        $nombre_imagen = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", $fileNameCmps[0]) . '.' . $fileExtension;
        $directoryPath = '../assets/images/';
        $dest_path = $directoryPath . $nombre_imagen;

        if (!move_uploaded_file($fileTmpPath, $dest_path)) {
            $nombre_imagen = "";
        }
    }

    // Inyectamos el restaurante_id correspondiente en la consulta de inserción
    $stmt = $db->prepare("INSERT INTO mis_productos (restaurante_id, name, description, price, stock, imagen, status) VALUES (?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("issiis", $id_restaurante_actual, $nombre, $desc, $precio, $stock, $nombre_imagen);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_dashboard?seccion=productos");
        exit();
    } else {
        die("Error de Base de Datos al crear producto: " . $db->error);
    }
}

// ==========================================================================
// 2. ACTUALIZAR PLATO EXISTENTE (Maneja actualización opcional de imagen y seguridad de pertenencia)
// ==========================================================================
if ($action === 'update_prod') {
    $id     = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $desc   = trim($_POST['descripcion']);
    $precio = intval($_POST['precio']); 
    $stock  = intval($_POST['stock'] ?? 0);
    $id_restaurante_actual = (int)$dataRestaurante['restaurante_id'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        if ($fileExtension !== 'webp') {
            die("<script>
                alert('⚠️ Por favor, sube tu archivo en formato WEBP para mejorar la experiencia de los clientes y optimizar la velocidad de la página.');
                window.history.back();
            </script>");
        }
        
        $nombre_imagen = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", $fileNameCmps[0]) . '.' . $fileExtension;
        $directoryPath = '../assets/images/';
        $dest_path = $directoryPath . $nombre_imagen;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // El query añade un WHERE de restaurante_id para asegurar que ningún usuario altere productos de otros comercios
            $query_update = "UPDATE mis_productos SET name = ?, description = ?, price = ?, stock = ?, imagen = ? WHERE id = ? AND restaurante_id = ?";
            $stmt = $db->prepare($query_update);
            $stmt->bind_param("ssiisii", $nombre, $desc, $precio, $stock, $nombre_imagen, $id, $id_restaurante_actual);
        } else {
            die("Error al mover el nuevo archivo de imagen al servidor.");
        }
    } else {
        $query_update = "UPDATE mis_productos SET name = ?, description = ?, price = ?, stock = ? WHERE id = ? AND restaurante_id = ?";
        $stmt = $db->prepare($query_update);
        $stmt->bind_param("ssiiii", $nombre, $desc, $precio, $stock, $id, $id_restaurante_actual);
    }
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_dashboard?seccion=productos");
        exit();
    } else {
        die("Error al actualizar producto: " . $db->error);
    }
}

// ==========================================================================
// 3. ELIMINAR PLATO (Filtro por comercio)
// ==========================================================================
if ($action === 'delete_prod') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $id_restaurante_actual = (int)$dataRestaurante['restaurante_id'];
    
    if ($id > 0) {
        $stmt = $db->prepare("DELETE FROM mis_productos WHERE id = ? AND restaurante_id = ?");
        $stmt->bind_param("ii", $id, $id_restaurante_actual);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: admin_dashboard?seccion=productos");
    exit();
}

// ==========================================================================
// 4. CAMBIAR ESTADO DE PEDIDOS / ÓRDENES (AJAX FETCH)
// ==========================================================================
if ($action === 'update_status') {
    header('Content-Type: application/json; charset=utf-8');
    ini_set('display_errors', 0);
    error_reporting(E_ALL);

    if (isset($_POST['id']) && isset($_POST['tabla']) && isset($_POST['estado'])) {
        
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

        $query_status = "UPDATE $tabla SET $columna_estado = ? WHERE id = ?";
        $stmt = $db->prepare($query_status);
        $stmt->bind_param("si", $estado, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => '¡Estado actualizado con éxito!']);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "Error de MySQL en la tabla [$tabla]: " . $db->error
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Parámetros POST incompletos en el backend.']);
    }
    exit();
}

if ($action === 'registrar_empleado') {
    // Capturamos y sanitizamos los datos enviados desde el formulario premium
    $nombre = mysqli_real_escape_string($db, trim($_POST['nombre_empleado']));
    $correo = mysqli_real_escape_string($db, trim($_POST['correo_empleado']));
    $password_clara = trim($_POST['password_empleado']);
    $restaurante_id_asociado = (int)$_POST['restaurante_id'];

    // 🔒 1. Validación de seguridad: Evitar correos duplicados en el sistema
    $check_email = $db->query("SELECT id FROM datos WHERE correo = '$correo'");
    if ($check_email && $check_email->num_rows > 0) {
        die("<script>
            alert('⚠️ El correo electrónico ya está registrado por otro usuario.');
            window.history.back();
        </script>");
    }

    // 🔒 2. Encriptación segura de la contraseña usando el estándar nativo de PHP
    $password_encriptada = password_hash($password_clara, PASSWORD_BCRYPT);
    
    // Forzamos el tipo 'empresa' para que el sistema le conceda acceso al Dashboard administrativo
    $tipo_usuario = 'empresa'; 

    // 3. Sentencia preparada para inyectar el nuevo empleado amarrado al restaurante actual
    $query_empleado = "INSERT INTO datos (nombre, correo, contraseña, tipo, restaurante_id) 
                       VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($query_empleado);
    $stmt->bind_param("ssssi", $nombre, $correo, $password_encriptada, $tipo_usuario, $restaurante_id_asociado);
    
    if ($stmt->execute()) {
        $stmt->close();
        // Redirección limpia de vuelta al panel con un aviso amigable de éxito
        echo "<script>
            alert('🎉 ¡Colaborador vinculado exitosamente a tu sucursal!');
            window.location.href = 'admin_dashboard?seccion=empleados';
        </script>";
        exit();
    } else {
        die("❌ Error interno al registrar el empleado: " . $db->error);
    }
}

header("Location: admin_dashboard");
exit();
?>