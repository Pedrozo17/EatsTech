<?php
// Detalles de la conexión de producción en Alwaysdata
$db_host = "mysql-eatstech.alwaysdata.net";
$db_user = "eatstech";               // El usuario de BD que creaste
$db_pass = "Samuel0917";             // La contraseña que le asignaste
$db_name = "eatstech_db";

// Creación de la conexión con las variables correctas
$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Validación de errores
if ($db->connect_error) {
    die("Fallo en la conexión de producción: " . $db->connect_error);
}

// Configuración del juego de caracteres
$db->set_charset("utf8");
?>