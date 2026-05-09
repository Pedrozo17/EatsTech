<?php
session_start();
include("../../config/con_db.php");

// Páginas permitidas para redirigir
$paginas = [
    'casarolla' => '/Eatstech/pages/casarolla.php',
    'index'     => '/Eatstech/pages/index.php'
];

$redirect = isset($_GET['redirect']) && isset($paginas[$_GET['redirect']]) 
    ? $paginas[$_GET['redirect']] 
    : '/Eatstech/pages/index.php';

if (isset($_POST['login'])) {
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);
    
    $consulta = "SELECT * FROM datos WHERE correo='$correo' AND contraseña='$contraseña'";
    $resultado = mysqli_query($conex, $consulta);
    
    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        $_SESSION['logueado'] = true;
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['nombre'] = $usuario['nombre'];
        
        header("Location: " . $redirect);
        exit();
    } else {
        header("Location: /Eatstech/modules/usuarios/iniciodesesion.php?error=1&redirect=" . $_GET['redirect']);
        exit();
    }
}
?>