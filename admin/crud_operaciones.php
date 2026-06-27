<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

include_once '../modules/usuarios/control_plan.php';

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
// 1. CREAR NUEVO PLATO (Con Stock e Imagen Incluida) - VERSIÓN DE PRUEBA
// ==========================================================================
if ($action === 'create_prod') {
    $nombre = trim($_POST['nombre']);
    $desc   = trim($_POST['descripcion']);
    $precio = intval($_POST['precio']); 
    $stock  = intval($_POST['stock'] ?? 0);

    $nombre_imagen = ""; 

    // DETECTOR DE ERRORES: Si quieres ver qué está llegando, descomenta las dos líneas de abajo:
    // echo "<pre>"; print_r($_FILES); echo "</pre>"; die();

    // 🟢 VALIDAR SUBIDA DE IMAGEN DESDE EL COMPUTADOR DEL USUARIO
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // 🚨 CONTROL DE FORMATO ESTRICTO:
        if ($fileExtension !== 'webp') {
            // Detiene el proceso y muestra tu mensaje personalizado
            die("<script>
                alert('⚠️ Por favor, sube tu archivo en formato WEBP para mejorar la experiencia de los clientes y optimizar la velocidad de la página.');
                window.history.back();
            </script>");
        }
        
        // Si pasa la validación (es webp), creamos el nombre único usando la marca de tiempo
        $nombre_imagen = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", $fileNameCmps[0]) . '.' . $fileExtension;
        
        $directoryPath = '../assets/images/';
        $dest_path = $directoryPath . $nombre_imagen;

        // Si falla la subida física, dejamos la variable vacía por seguridad
        if (!move_uploaded_file($fileTmpPath, $dest_path)) {
            $nombre_imagen = "";
        }
    }

    // Insertamos en la columna 'imagen'
    $stmt = $db->prepare("INSERT INTO mis_productos (name, description, price, stock, imagen, status) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("ssiis", $nombre, $desc, $precio, $stock, $nombre_imagen);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_dashboard?seccion=productos");
        exit();
    } else {
        die("Error de Base de Datos al crear producto: " . $db->error);
    }
}

// ==========================================================================
// 2. ACTUALIZAR PLATO EXISTENTE (Maneja actualización opcional de imagen)
// ==========================================================================
if ($action === 'update_prod') {
    $id     = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $desc   = trim($_POST['descripcion']);
    $precio = intval($_POST['precio']); 
    $stock  = intval($_POST['stock'] ?? 0);

    // 🟢 VALIDAMOS SI SUBIERON UNA NUEVA IMAGEN PARA REEMPLAZAR LA ANTERIOR
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // 🚨 CONTROL DE FORMATO ESTRICTO AL EDITAR:
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
            // Si la foto subió bien y es webp, actualizamos todo incluyendo la nueva ruta
            $query_update = "UPDATE mis_productos SET name = ?, description = ?, price = ?, stock = ?, imagen = ? WHERE id = ?";
            $stmt = $db->prepare($query_update);
            $stmt->bind_param("ssiisi", $nombre, $desc, $precio, $stock, $nombre_imagen, $id);
        } else {
            die("Error al mover el nuevo archivo de imagen al servidor.");
        }
    } else {
        // 🟢 SI NO SUBIÓ FOTO NUEVA (Campo vacío en el HTML): Mantenemos intacta la foto que ya tenía
        $query_update = "UPDATE mis_productos SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?";
        $stmt = $db->prepare($query_update);
        $stmt->bind_param("ssiii", $nombre, $desc, $precio, $stock, $id);
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
// 3. ELIMINAR PLATO 
// ==========================================================================
if ($action === 'delete_prod') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id > 0) {
        $stmt = $db->prepare("DELETE FROM mis_productos WHERE id = ?");
        $stmt->bind_param("i", $id);
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

        // Evaluación de las columnas según la tabla elegida
        if ($tabla === 'pedidos_registrados') {
            $columna_estado = "estado"; 
        } elseif ($tabla === 'orden') {
            $columna_estado = "status";
        } else {
            echo json_encode(['success' => false, 'message' => 'Tabla no válida.']);
            exit();
        }

        // Armamos el query dinámico seguro para la tabla, pero parametrizamos el valor
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

// Si intentan entrar directo a la URL de este archivo sin enviar una acción
header("Location: admin_dashboard");
exit();
?>