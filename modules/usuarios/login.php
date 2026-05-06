<?php
session_start();
include("../../config/con_db.php");

if (isset($_POST['login'])) {
    if (strlen($_POST['correo']) >= 1 && strlen($_POST['contraseña']) >= 1) {
        $correo = trim($_POST['correo']);
        $contraseña = trim($_POST['contraseña']);
        
        $consulta = "SELECT * FROM datos WHERE correo='$correo' AND contraseña='$contraseña'";
        $resultado = mysqli_query($conex, $consulta);
        
        if (mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);
            
            // Guardamos los datos en sesión
            $_SESSION['logueado'] = true;
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['nombre'] = $usuario['nombre']; // ajusta si el campo se llama diferente
            
            header("Location: /Eatstech\pages\index.php");
            exit();
        } else {
            echo '<h3 class="bad">¡Correo o contraseña incorrectos!</h3>';
        }
    } else {
        echo '<h3 class="bad">¡Por favor complete los campos!</h3>';
    }
}
?>