<?php
// Parámetros de conexión para producción en Alwaysdata
$db_host = "mysql-eatstech.alwaysdata.net"; // Revisa el host exacto en tu panel
$db_user = "eatstech";               // El usuario de BD que creaste
$db_pass = "S17";        // La contraseña que le asignaste
$db_name = "eatstech_db";         // El nombre completo de tu BD

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($db->connect_error) {
    die("Fallo en la conexión de producción: " . $db->connect_error);
}
$db->set_charset("utf8");
?>