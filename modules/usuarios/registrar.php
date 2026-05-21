<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../../config/con_db.php");

if (isset($_POST['register'])) {
    if (strlen($_POST['nombre']) >= 1 && 
        strlen($_POST['correo']) >= 1 && 
        strlen($_POST['cedula']) >= 1 && 
        strlen($_POST['telefono']) >= 1 && 
        strlen($_POST['direccion']) >= 1 && 
        strlen($_POST['contraseña']) >= 1 && 
        strlen($_POST['confirmar_contraseña']) >= 1) {

        $nombre = mysqli_real_escape_string($conex, trim($_POST['nombre']));
        $correo = mysqli_real_escape_string($conex, trim($_POST['correo']));
        $cedula = mysqli_real_escape_string($conex, trim($_POST['cedula']));
        $telefono = mysqli_real_escape_string($conex, trim($_POST['telefono'])); 
        $direccion = mysqli_real_escape_string($conex, trim($_POST['direccion'])); 
        $contraseña = trim($_POST['contraseña']);
        $confirmar_contraseña = trim($_POST['confirmar_contraseña']);

        // 1. Validamos que ambas contraseñas coincidan
        if ($contraseña !== $confirmar_contraseña) {
            header("Location: \Eatstech\modules\usuarios\iniciodesesion.php?error=password");
            exit();
        } else {
            
            // 2. VALIDACIÓN ANTIDUPLICADOS: ¿El correo ya existe?
            $buscarCorreo = "SELECT id FROM datos WHERE correo = '$correo'";
            $resultadoCorreo = mysqli_query($conex, $buscarCorreo);

            if (mysqli_num_rows($resultadoCorreo) > 0) {
                header("Location: \Eatstech\modules\usuarios\iniciodesesion.php?error=duplicado");
                exit();
            } else {
                // Si todo está bien, registramos...
                $contraseña_encriptada = password_hash($contraseña, PASSWORD_DEFAULT);

                $consulta = "INSERT INTO datos(nombre, correo, cedula, telefono, direccion, contraseña) 
                             VALUES ('$nombre','$correo','$cedula','$telefono','$direccion','$contraseña_encriptada')";
                
                $resultado = mysqli_query($conex, $consulta);

                if ($resultado) {
                    $_SESSION['logueado'] = true;
                    $_SESSION['correo'] = $correo;
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['telefono'] = $telefono;     
                    $_SESSION['direccion'] = $direccion;   

                    header("Location: /Eatstech/pages/index.php");
                    exit();
                } else {
                    header("Location: \Eatstech\modules\usuarios\iniciodesesion.php?error=db");
                    exit();
                }
            }
        }
    } else {
        echo '<h3 class="bad">¡Por favor complete todos los campos!</h3>';
    }
}
?>