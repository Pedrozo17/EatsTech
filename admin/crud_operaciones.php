<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// SEGURIDAD: Si no es empresa, pa' fuera
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'empresa') { 
    header("Location: ../modules/usuarios/iniciodesesion "); 
    exit(); 
}

// CORRECCIÓN DE RUTA DE CONEXIÓN (Igual que en tu frontend)
$ruta_conexion = "../config/Configuracion.php"; 
if (!file_exists($ruta_conexion)) {
    // Si no existe ahí, probamos un nivel más arriba por si acaso
    $ruta_conexion = "../config/Configuracion.php";
    if (!file_exists($ruta_conexion)) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'No se encontró el archivo de conexión en el servidor.']);
        exit();
    }
}
include($ruta_conexion);

// Detectamos la acción ya sea por POST (Formularios/AJAX) o GET (Enlaces de eliminar)
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// ==========================================================================
// 1. CREAR NUEVO PLATO
// ==========================================================================
if ($action === 'create_prod') {
    $nombre = $db->real_escape_string(trim($_POST['nombre']));
    $desc   = $db->real_escape_string(trim($_POST['descripcion']));
    $precio = floatval($_POST['precio']);

    $query = "INSERT INTO mis_productos (name, description, price, status) VALUES ('$nombre', '$desc', $precio, 1)";
    $db->query($query);
    header("Location: admin_dashboard ?seccion=productos");
    exit();
}

// ==========================================================================
// 2. ACTUALIZAR PLATO
// ==========================================================================
if ($action === 'update_prod') {
    $id     = intval($_POST['id']);
    $nombre = $db->real_escape_string(trim($_POST['nombre']));
    $desc   = $db->real_escape_string(trim($_POST['descripcion']));
    $precio = floatval($_POST['precio']);

    $query = "UPDATE mis_productos SET name='$nombre', description='$desc', price=$precio WHERE id=$id";
    $db->query($query);
    header("Location: admin_dashboard ?seccion=productos");
    exit();
}

// ==========================================================================
// 3. ELIMINAR PLATO
// ==========================================================================
if ($action === 'delete_prod') {
    $id = intval($_GET['id']);
    $db->query("DELETE FROM mis_productos WHERE id = $id");
    header("Location: admin_dashboard ?seccion=productos");
    exit();
}
// ==========================================================================
// 4. CAMBIAR ESTADO DE PEDIDOS / ÓRDENES EN TIEMPO REAL (AJAX FETCH)
// ==========================================================================
if ($action === 'update_status') {
    // Forzamos que la respuesta sea JSON limpio pase lo que pase
    header('Content-Type: application/json; charset=utf-8');
    
    // Evitamos que los errores nativos de PHP rompan el JSON, los manejaremos nosotros
    ini_set('display_errors', 0);
    error_reporting(E_ALL);

    if (isset($_POST['id']) && isset($_POST['tabla']) && isset($_POST['estado'])) {
        
        $id = intval($_POST['id']);
        $tabla = preg_replace("/[^a-zA-Z0-9_]/", "", $_POST['tabla']); 
        $estado = $db->real_escape_string(trim($_POST['estado']));

        // Evaluamos las columnas según la tabla elegida

        if ($tabla === 'pedidos_registrados') {
            $columna_estado = "estado"; 
            $valor_final = "'$estado'"; // Guarda texto ('Pagado', 'En Cocina'...)
        } elseif ($tabla === 'orden') {
            $columna_estado = "status";
            $valor_final = "'$estado'"; // <--- ANTES TENÍA INTVAL, AHORA LLEVA COMILLAS PARA TEXTO
        } else {
            echo json_encode(['success' => false, 'message' => 'Tabla no válida.']);
            exit();
        }

        // Armamos el query estructurado
        $query = "UPDATE $tabla SET $columna_estado = $valor_final WHERE id = $id";

        // Ejecutamos la consulta validando errores
        $resultado = $db->query($query);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => '¡Estado actualizado con éxito!']);
        } else {
            // SI LA BASE DE DATOS DA ERROR, YA NO MANDA UN ERROR 500. Devuelve el por qué en texto claro:
            echo json_encode([
                'success' => false, 
                'message' => "Error de MySQL en la tabla [$tabla]: " . $db->error . " | Query intentado: " . $query
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Parámetros POST incompletos en el backend.']);
    }
    exit();
}

// Si entran directo al archivo sin acción válida
header("Location: admin_dashboard ");
exit();
?>