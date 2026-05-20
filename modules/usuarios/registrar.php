<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../../config/con_db.php");

if (isset($_POST['register'])) {
    if (strlen($_POST['nombre']) >= 1 && 
        strlen($_POST['correo']) >= 1 && 
        strlen($_POST['cedula']) >= 1 && 
        strlen($_POST['telefono']) >= 1 && // Nuevo campo requerido
        strlen($_POST['direccion']) >= 1 && // Nuevo campo requerido
        strlen($_POST['contraseña']) >= 1 && 
        strlen($_POST['confirmar_contraseña']) >= 1) {

        $nombre = mysqli_real_escape_string($conex, trim($_POST['nombre']));
        $correo = mysqli_real_escape_string($conex, trim($_POST['correo']));
        $cedula = mysqli_real_escape_string($conex, trim($_POST['cedula']));
        $telefono = mysqli_real_escape_string($conex, trim($_POST['telefono'])); // Nuevo
        $direccion = mysqli_real_escape_string($conex, trim($_POST['direccion'])); // Nuevo
        $contraseña = trim($_POST['contraseña']);
        $confirmar_contraseña = trim($_POST['confirmar_contraseña']);

        // 1. Validamos que ambas contraseñas coincidan antes de proceder
        if ($contraseña !== $confirmar_contraseña) {
            echo '<h3 class="bad">¡Las contraseñas no coinciden!</h3>';
        } else {
            // 2. ENCRIPTACIÓN SEGURA DE LA CONTRASEÑA
            $contraseña_encriptada = password_hash($contraseña, PASSWORD_DEFAULT);

            // 3. Consulta SQL con los nuevos campos (No guardamos la confirmación en la BD)
            $consulta = "INSERT INTO datos(nombre, correo, cedula, telefono, direccion, contraseña) 
                         VALUES ('$nombre','$correo','$cedula','$telefono','$direccion','$contraseña_encriptada')";
            
            $resultado = mysqli_query($conex, $consulta);

            if ($resultado) {
                // Registro exitoso - guardamos la sesión con todos los datos que requiere tu carrito
                $_SESSION['logueado'] = true;
                $_SESSION['correo'] = $correo;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['telefono'] = $telefono;     // Guardado en sesión
                $_SESSION['direccion'] = $direccion;   // Guardado en sesión

                header("Location: /Eatstech/pages/index.php");
                exit();
            } else {
                echo '<h3 class="bad">¡Ups ha ocurrido un error al registrar!</h3>';
            }
        }
    } else {
        echo '<h3 class="bad">¡Por favor complete todos los campos!</h3>';
    }
}
?>