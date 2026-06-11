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
// 1. CREAR NUEVO PLATO (Con Stock incluido)
// ==========================================================================
if ($action === 'create_prod') {
    $nombre = trim($_POST['nombre']);
    $desc   = trim($_POST['descripcion']);
    $precio = intval($_POST['precio']); // Recibe el número ya limpio sin puntos desde JS
    $stock  = intval($_POST['stock'] ?? 0);

    // Usamos sentencias preparadas de mysqli por seguridad y orden
    $stmt = $db->prepare("INSERT INTO mis_productos (name, description, price, stock, status) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("ssii", $nombre, $desc, $precio, $stock);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_dashboard?seccion=productos");
        exit();
    } else {
        die("Error al crear producto: " . $db->error);
    }
}

// ==========================================================================
// 2. ACTUALIZAR PLATO EXISTENTE (Corregido y unificado)
// ==========================================================================
if ($action === 'update_prod') {
    $id     = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $desc   = trim($_POST['descripcion']);
    $precio = intval($_POST['precio']); // Recibe el número limpio sin puntos desde JS
    $stock  = intval($_POST['stock'] ?? 0);

    // 🟢 AQUÍ SE CORRIGIÓ: Usamos una variable $query_update para NO pisar el objeto de conexión $db
    $query_update = "UPDATE mis_productos SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?";
    
    $stmt = $db->prepare($query_update);
    $stmt->bind_param("ssiii", $nombre, $desc, $precio, $stock, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: admin_dashboard?seccion=productos");
        exit();
    } else {
        die("Error al actualizar producto: " . $db->error);
    }
}

// ==========================================================================
// 3. ELIMINAR PLATO (Arreglado el error Fatal Error de la línea 66)
// ==========================================================================
if ($action === 'delete_prod') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id > 0) {
        // Ahora $db está totalmente libre y conserva la conexión intacta
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