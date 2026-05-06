<?php
session_start();
include("../../config/con_db.php");

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
        
        header("Location: /Eatstech/pages/index.php");
        exit();
    } else {
        header("Location: /Eatstech/modules/usuarios/iniciodesesion.php?error=1");
        exit();
    }
}
?>