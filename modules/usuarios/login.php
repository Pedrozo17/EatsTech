<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
    $correo = mysqli_real_escape_string($conex, trim($_POST['correo']));
    $contraseña = trim($_POST['contraseña']);
    
    // 1. Buscamos al usuario ÚNICAMENTE por su correo
    $consulta = "SELECT * FROM datos WHERE correo='$correo'";
    $resultado = mysqli_query($conex, $consulta);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        // 2. VERIFICACIÓN DE CONTRASEÑA ENCRIPTADA
        if (password_verify($contraseña, $usuario['contraseña'])) {
            
            // ¡Login exitoso! Guardamos todo en la sesión
            $_SESSION['logueado'] = true;
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['telefono'] = $usuario['telefono'];   // Enviado al carrito
            $_SESSION['direccion'] = $usuario['direccion']; // Enviado al carrito
            
            header("Location: " . $redirect);
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: /Eatstech/modules/usuarios/iniciodesesion.php?error=1&redirect=" . (isset($_GET['redirect']) ? $_GET['redirect'] : ''));
            exit();
        }
    } else {
        // Correo no encontrado
        header("Location: /Eatstech/modules/usuarios/iniciodesesion.php?error=1&redirect=" . (isset($_GET['redirect']) ? $_GET['redirect'] : ''));
        exit();
    }
}
?>