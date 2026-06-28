<?php
// buscar_restaurantes.php
ob_start(); 
header('Content-Type: application/json; charset=utf-8');

include("../../config/con_db.php"); 

if (isset($conex) && !isset($db)) {
    $db = $conex;
}

if (!$db) {
    echo json_encode(["error" => "No se detectó la variable de conexión de la BD"]);
    exit();
}

$correo = isset($_GET['correo']) ? mysqli_real_escape_string($db, trim($_GET['correo'])) : '';
$restaurantes = [];

if (!empty($correo)) {
    // 🟢 CORRECCIÓN CRÍTICA: Cambiamos 'r.usuario_id = d.id' por 'd.restaurante_id = r.id'
    // También nos aseguramos de que solo busque usuarios de tipo 'empresa'
    $query = "SELECT r.id, r.nombre_restaurante, r.slug_carpeta 
              FROM restaurantes r 
              JOIN datos d ON d.restaurante_id = r.id 
              WHERE d.correo = '$correo' AND d.tipo = 'empresa'";
              
    $res = mysqli_query($db, $query);
    
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $restaurantes[] = $row;
        }
    }
}

ob_end_clean(); 
echo json_encode($restaurantes);
exit();
?>