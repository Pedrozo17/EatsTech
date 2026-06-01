<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../../config/con_db.php");

if (isset($_POST['register'])) {
    if (strlen($_POST['nombre']) >= 1 && 
        strlen($_POST['correo']) >= 1 && 
        strlen($_POST['cedula']) >= 1 && 
        strlen($_POST['telefono']) >= 1 && 
        strlen($_POST['direccion']) >= 1 && 
        strlen($_POST['contraseña']) >= 1 && 
        strlen($_POST['confirmar_contraseña']) >= 1) {

        $nombre = mysqli_real_escape_string($db, trim($_POST['nombre']));
        $correo = mysqli_real_escape_string($db, trim($_POST['correo']));
        $cedula = mysqli_real_escape_string($db, trim($_POST['cedula']));
        $telefono = mysqli_real_escape_string($db, trim($_POST['telefono'])); 
        $direccion = mysqli_real_escape_string($db, trim($_POST['direccion'])); 
        $contraseña = trim($_POST['contraseña']);
        $confirmar_contraseña = trim($_POST['confirmar_contraseña']);
        $tipo_usuario = mysqli_real_escape_string($db, $_POST['tipo_usuario']); 

        if ($contraseña !== $confirmar_contraseña) {
            header("Location: ../usuarios/iniciodesesion ?error=password");
            exit();
        } else {
            $buscarCorreo = "SELECT id FROM datos WHERE correo = '$correo'";
            $resultadoCorreo = mysqli_query($db, $buscarCorreo);

            if (mysqli_num_rows($resultadoCorreo) > 0) {
                header("Location: ../usuarios/iniciodesesion ?error=duplicado");
                exit();
            } else {
                $contraseña_encrypted = password_hash($contraseña, PASSWORD_DEFAULT);

                // 1. Guardamos el usuario en la tabla 'datos'
                $consulta = "INSERT INTO datos(nombre, correo, contraseña, cedula, telefono, direccion, tipo) 
                             VALUES ('$nombre','$correo','$contraseña_encrypted','$cedula','$telefono','$direccion', '$tipo_usuario')";
                
                $resultado = mysqli_query($db, $consulta);

                if ($resultado) {
                    $nuevo_usuario_id = mysqli_insert_id($db);

                    // ==========================================================================
                    // EVALUACIÓN DE FLUJO SEGÚN EL ROL
                    // ==========================================================================
                    if ($tipo_usuario === 'empresa') {
                        // Flujo Empresa: Registramos restaurante base y mandamos al login para que lo seleccione
                        $nombre_restaurante = mysqli_real_escape_string($db, trim($_POST['nombre_restaurante']));
                        
                        $slug_carpeta = 'admin';      
                        $color_principal = '#cf9465'; 

                        $consulta_restaurante = "INSERT INTO restaurantes (usuario_id, nombre_restaurante, slug_carpeta, color_principal) 
                                                 VALUES ('$nuevo_usuario_id', '$nombre_restaurante', '$slug_carpeta', '$color_principal')";
                        
                        mysqli_query($db, $consulta_restaurante);

                        // Redirección al login con aviso para seleccionar empresa
                        header("Location: ../usuarios/iniciodesesion ?registro=exito");
                        exit();

                    } else {
                        // Flujo Persona: Iniciamos sesión automáticamente para romper la fricción
                        $_SESSION['logueado'] = true;
                        $_SESSION['id_usuario'] = $nuevo_usuario_id; 
                        $_SESSION['correo'] = $correo;
                        $_SESSION['nombre'] = $nombre;
                        $_SESSION['telefono'] = $telefono;     
                        $_SESSION['direccion'] = $direccion;   
                        $_SESSION['tipo'] = $tipo_usuario; 

                        // Mandamos directo al index público a comprar
                        header("Location: ../../pages/index ");
                        exit();
                    }
                    
                } else {
                    header("Location: ../usuarios/iniciodesesion ?error=db");
                    exit();
                }
            }
        }
    } else {
        echo '<h3 class="bad">¡Por favor complete todos los campos!</h3>';
    }
}
?>