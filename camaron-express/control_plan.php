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

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['restaurante_id'])) {
    echo "<script>window.location.href = '../modules/usuarios/iniciodesesion';</script>";
    exit();
}

$restaurante_sesion_id = (int)$_SESSION['restaurante_id'];

// =================================================================
// 🛠️ CONSULTAR DATOS DEL RESTAURANTE Y SU PLAN (Multi-Inquilino Real)
// =================================================================
$query = "SELECT r.id AS restaurante_id, r.nombre_restaurante, r.color_principal, r.slug_carpeta,
                 p.id AS plan_id, p.nombre AS nombre_plan, p.limite_productos, p.tiene_estadisticas
          FROM restaurantes r
          LEFT JOIN planes p ON r.plan_id = p.id
          WHERE r.id = ?";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $restaurante_sesion_id);
$stmt->execute();
$resultado = $stmt->get_result();
$dataRestaurante = $resultado->fetch_assoc();
$stmt->close();

if (!$dataRestaurante) {
    die("Error de configuración: Tu cuenta de usuario no tiene un restaurante válido asignado.");
}

// =================================================================
// 📊 CONTEO SEGURO EN LA TABLA REAL 'mis_productos'
// =================================================================
$total_productos_actuales = 0;
$restaurante_id = (int)$dataRestaurante['restaurante_id'];

// Consultamos contando únicamente los platos que pertenecen a este restaurante_id
$queryContar = "SELECT COUNT(*) as total_productos FROM mis_productos WHERE restaurante_id = ?";
$stmtContar = $db->prepare($queryContar);
$stmtContar->bind_param("i", $restaurante_id);
$stmtContar->execute();
$resultadoContar = $stmtContar->get_result();
$conteo = $resultadoContar->fetch_assoc();
$stmtContar->close();

$total_productos_actuales = (int)$conteo['total_productos'];
?>