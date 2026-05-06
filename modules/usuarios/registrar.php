<?php
session_start();
include("../../config/con_db.php");

if (isset($_POST['register'])) {
    if (strlen($_POST['nombre']) >= 1 && 
        strlen($_POST['correo']) >= 1 && 
        strlen($_POST['cedula']) >= 1 && 
        strlen($_POST['contraseña']) >= 1 && 
        strlen($_POST['confirmar_contraseña']) >= 1) {

        $nombre = trim($_POST['nombre']);
        $correo = trim($_POST['correo']);
        $cedula = trim($_POST['cedula']);
        $contraseña = trim($_POST['contraseña']);
        $confirmar_contraseña = trim($_POST['confirmar_contraseña']);

        $consulta = "INSERT INTO datos(nombre, correo, cedula, contraseña, confirmar_contraseña) 
                     VALUES ('$nombre','$correo','$cedula','$contraseña','$confirmar_contraseña')";
        $resultado = mysqli_query($conex, $consulta);

        if ($resultado) {
            // Registro exitoso - guardamos sesión automáticamente
            $_SESSION['logueado'] = true;
            $_SESSION['correo'] = $correo;
            $_SESSION['nombre'] = $nombre;

            header("Location: /Eatstech/pages/index.php");
            exit();
        } else {
            echo '<h3 class="bad">¡Ups ha ocurrido un error!</h3>';
        }
    } else {
        echo '<h3 class="bad">¡Por favor complete los campos!</h3>';
    }
}
?>
