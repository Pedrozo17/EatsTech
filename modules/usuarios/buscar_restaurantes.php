<?php
// 1. Cambiamos al archivo correcto que maneja tus usuarios y restaurantes
include("../../config/con_db.php"); 

// 2. Usamos mysqli_real_escape_string con la variable $conex (tu conexión de con_db)
$correo = isset($_GET['correo']) ? mysqli_real_escape_string($db, trim($_GET['correo'])) : '';
$restaurantes = [];

if (!empty($correo)) {
    // Consulta relacional: Busca los restaurantes del usuario dueño de ese correo
    $query = "SELECT r.nombre_restaurante, r.slug_carpeta 
              FROM restaurantes r 
              JOIN datos d ON r.usuario_id = d.id 
              WHERE d.correo = '$correo'";
              
    // 3. Cambiamos la ejecución al estilo por procedimientos que usa tu con_db 
    $res = mysqli_query($db, $query);
    
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $restaurantes[] = $row;
        }
    }
}

// Devolvemos la respuesta en formato JSON para el JavaScript del Login
header('Content-Type: application/json');
echo json_encode($restaurantes);
exit();