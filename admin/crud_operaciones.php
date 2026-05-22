<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'empresa') { header("Location: ../modules/usuarios/iniciodesesion.php"); exit(); }

include("../config/configuracion.php");

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// CREAR NUEVO PLATO
if ($action === 'create_prod') {
    $nombre = $db->real_escape_string(trim($_POST['nombre']));
    $desc   = $db->real_escape_string(trim($_POST['descripcion']));
    $precio = floatval($_POST['precio']);

    // Grabamos sobre name, description, price y status = 1
    $query = "INSERT INTO mis_productos (name, description, price, status) VALUES ('$nombre', '$desc', $precio, 1)";
    $db->query($query);
    header("Location: admin_dashboard.php?seccion=productos");
    exit();
}

// ACTUALIZAR PLATO
if ($action === 'update_prod') {
    $id     = intval($_POST['id']);
    $nombre = $db->real_escape_string(trim($_POST['nombre']));
    $desc   = $db->real_escape_string(trim($_POST['descripcion']));
    $precio = floatval($_POST['precio']);

    // Modificamos usando los nombres en inglés
    $query = "UPDATE mis_productos SET name='$nombre', description='$desc', price=$precio WHERE id=$id";
    $db->query($query);
    header("Location: admin_dashboard.php?seccion=productos");
    exit();
}

// ELIMINAR PLATO
if ($action === 'delete_prod') {
    $id = intval($_GET['id']);
    $db->query("DELETE FROM mis_productos WHERE id = $id");
    header("Location: admin_dashboard.php?seccion=productos");
    exit();
}
?>