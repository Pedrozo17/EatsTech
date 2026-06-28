<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión segura usando la ruta absoluta
$ruta_db = dirname(__DIR__, 1) . '/config/con_db.php';
if (file_exists($ruta_db)) {
    require_once $ruta_db;
} else {
    die("Error crítico: No se encontró con_db.php");
}

if (!isset($_SESSION['id_usuario'])) {
    echo "<script>window.location.href = '../modules/usuarios/iniciodesesion';</script>";
    exit();
}

$usuario_logueado = (int)$_SESSION['id_usuario'];

// =================================================================
// 🛠️ CONSULTAR DATOS DEL RESTAURANTE Y SU PLAN
// =================================================================
$query = "SELECT r.id AS restaurante_id, r.nombre_restaurante, r.color_principal, 
                 p.id AS plan_id, p.nombre AS nombre_plan, p.limite_productos, p.tiene_estadisticas
          FROM restaurantes r
          LEFT JOIN planes p ON r.plan_id = p.id
          WHERE r.usuario_id = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $usuario_logueado);
$stmt->execute();
$resultado = $stmt->get_result();
$dataRestaurante = $resultado->fetch_assoc();
$stmt->close();

// 👑 MODO ENTERPRISE FORZADO PARA TU USUARIO DE PRUEBA (ID 17)
if ($usuario_logueado == 17) { 
    if (!$dataRestaurante) {
        $dataRestaurante = [
            'restaurante_id' => 1,
            'nombre_restaurante' => 'Camaron Express (Test)'
        ];
    }
    $dataRestaurante['plan_id'] = 4; 
    $dataRestaurante['nombre_plan'] = 'Enterprise (Developer Mode)';
    $dataRestaurante['limite_productos'] = -1; 
    $dataRestaurante['tiene_estadisticas'] = true; 
} else {
    if (!$dataRestaurante) {
        die("Error: Este usuario no tiene un restaurante configurado.");
    }
}

// =================================================================
// 📊 CONTEO SEGURO EN LA TABLA REAL 'mis_productos'
// =================================================================
$total_productos_actuales = 0;
$restaurante_id = $dataRestaurante['restaurante_id'];

// 1. Validamos primero si la columna 'restaurante_id' ya existe en tu tabla 'mis_productos'
$checkColumn = $db->query("SHOW COLUMNS FROM mis_productos LIKE 'restaurantes_id'"); 
// Nota: Revisa en tu BD si la creas como restaurante_id o usuario_id

if ($checkColumn && $checkColumn->num_rows > 0) {
    // Si ya tienes la columna de relación lista:
    $queryContar = "SELECT COUNT(*) as total_productos FROM mis_productos WHERE restaurantes_id = ?";
    $stmtContar = $db->prepare($queryContar);
    $stmtContar->bind_param("i", $restaurante_id);
    $stmtContar->execute();
    $resultadoContar = $stmtContar->get_result();
    $conteo = $resultadoContar->fetch_assoc();
    $stmtContar->close();
    
    $total_productos_actuales = $conteo['total_productos'];
} else {
    // Si aún no relacionas los productos por restaurante, contamos el total general temporalmente
    // para que no se te rompa el dashboard en la presentación
    $queryContarGenerales = "SELECT COUNT(*) as total_productos FROM mis_productos";
    $resultGenerales = $db->query($queryContarGenerales);
    if ($resultGenerales) {
        $conteo = $resultGenerales->fetch_assoc();
        $total_productos_actuales = $conteo['total_productos'];
    }
}
?>