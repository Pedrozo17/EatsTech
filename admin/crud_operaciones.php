<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

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

    if (isset($_FILES['imagen'])) {
        if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagen']['tmp_name'];
            $fileName = $_FILES['imagen']['name'];
            
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            // Nombre único
            $nombre_imagen = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", $fileNameCmps[0]) . '.' . $fileExtension;
            
            $directoryPath = '../assets/images/';
            $dest_path = $directoryPath . $nombre_imagen;

            // Verificación física del directorio
            if (!is_dir($directoryPath)) {
                die("Error crítico: La carpeta '$directoryPath' no existe en el servidor. Créala manualmente.");
            }

            if (!is_writable($directoryPath)) {
                die("Error de permisos: La carpeta '$directoryPath' existe pero PHP no tiene permiso de escritura/subida.");
            }

            // Intento de mover el archivo
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                die("Error del sistema: move_uploaded_file falló al mover el archivo temporal a: " . $dest_path);
            }
        } else {
            // Si el error no es OK, evaluamos qué pasó en el navegador
            $codigo_error = $_FILES['imagen']['error'];
            die("Error al cargar el archivo de imagen. Código de error de PHP: " . $codigo_error . ". (Si es 4, es porque el formulario no envió ningún archivo).");
        }
    } else {
        die("Error de comunicación: El backend no recibió ninguna variable llamada 'imagen'. Revisa el atributo 'name' de tu <input type='file' name='imagen'>");
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
    $precio = intval($_POST['precio']); // Recibe el número limpio sin puntos desde JS
    $stock  = intval($_POST['stock'] ?? 0);

    // 🟢 VALIDAMOS SI SUBIERON UNA NUEVA IMAGEN PARA REEMPLAZAR LA ANTERIOR
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        $nombre_imagen = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", $fileNameCmps[0]) . '.' . $fileExtension;
        
        $directoryPath = '../assets/images/';
        $dest_path = $directoryPath . $nombre_imagen;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Si la foto subió bien, actualizamos todos los campos incluyendo la nueva ruta de la imagen
            $query_update = "UPDATE mis_productos SET name = ?, description = ?, price = ?, stock = ?, imagen = ? WHERE id = ?";
            $stmt = $db->prepare($query_update);
            $stmt->bind_param("ssiisi", $nombre, $desc, $precio, $stock, $nombre_imagen, $id);
        } else {
            die("Error al mover el nuevo archivo de imagen al servidor.");
        }
    } else {
        // 🟢 SI NO SUBIÓ FOTO NUEVA: Mantenemos intacta la foto que ya tenía el producto anteriormente
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