<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../../config/con_db.php");

if (isset($_POST['register'])) {
    
    // ==========================================================================
    // VALIDACIÓN DE TÉRMINOS Y CONDICIONES (Seguridad en el Servidor)
    // ==========================================================================
    if (!isset($_POST['terminos'])) {
        header("Location: ../usuarios/iniciodesesion?error=terminos");
        exit();
    }

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
            header("Location: ../usuarios/iniciodesesion?error=password");
            exit();
        } else {
            $buscarCorreo = "SELECT id FROM datos WHERE correo = '$correo'";
            $resultadoCorreo = mysqli_query($db, $buscarCorreo);

            if (mysqli_num_rows($resultadoCorreo) > 0) {
                header("Location: ../usuarios/iniciodesesion?error=duplicado");
                exit();
            } else {
                $contraseña_encrypted = password_hash($contraseña, PASSWORD_DEFAULT);

                // 1. Guardamos el usuario en la tabla 'datos' (Inicialmente sin restaurante_id)
                $consulta = "INSERT INTO datos(nombre, correo, contraseña, cedula, telefono, direccion, tipo) 
                             VALUES ('$nombre','$correo','$contraseña_encrypted','$cedula','$telefono','$direccion', '$tipo_usuario')";
                
                $resultado = mysqli_query($db, $consulta);

                if ($resultado) {
                    $nuevo_usuario_id = mysqli_insert_id($db);

                    // ==========================================================================
                    // EVALUACIÓN DE FLUJO SEGÚN EL ROL
                    // ==========================================================================
                    if ($tipo_usuario === 'empresa') {
                        $nombre_restaurante = mysqli_real_escape_string($db, trim($_POST['nombre_restaurante']));
                        
                        // 1. Generamos el slug base limpio automáticamente
                        $slug_base = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombre_restaurante)));
                        $color_principal = '#cf9465'; 

                        // 🔍 COMPROBACIÓN INTELIGENTE ANTES DEL INSERT:
                        // Verificamos si ese slug ya existe en la tabla de restaurantes
                        $consulta_verificar = "SELECT COUNT(*) as total FROM restaurantes WHERE slug_carpeta = '$slug_base'";
                        $resultado_verificar = mysqli_query($db, $consulta_verificar);
                        $fila_verificar = mysqli_fetch_assoc($resultado_verificar);

                        if ($fila_verificar['total'] > 0) {
                            // 🛠️ Si ya existe, le concatenamos un número aleatorio único para evitar el choque en la base de datos
                            $slug_carpeta = $slug_base . '-' . rand(100, 999);
                        } else {
                            // Si está libre, se queda con el slug limpio original
                            $slug_carpeta = $slug_base;
                        }

                        // 2. Insertamos la nueva empresa con el $slug_carpeta garantizado como único
                        $consulta_restaurante = "INSERT INTO restaurantes (nombre_restaurante, slug_carpeta, color_principal, plan_id) 
                                                VALUES ('$nombre_restaurante', '$slug_carpeta', '$color_principal', 1)";
                        mysqli_query($db, $consulta_restaurante);
                        $nuevo_restaurante_id = mysqli_insert_id($db);

                        // 3. Enlazamos al usuario con su nuevo restaurante asignado (El verdadero puente)
                        $actualizar_usuario = "UPDATE datos SET restaurante_id = '$nuevo_restaurante_id' WHERE id = '$nuevo_usuario_id'";
                        mysqli_query($db, $actualizar_usuario);

                        // Redirección al login con aviso para seleccionar empresa
                        header("Location: ../usuarios/iniciodesesion?registro=exito");
                        exit();
                    }
                    } else {
                        // Flujo Persona: Iniciamos sesión automáticamente para romper la fricción
                        $_SESSION['logueado'] = true;
                        $_SESSION['id_usuario'] = $nuevo_usuario_id; 
                        $_SESSION['correo'] = $correo;
                        $_SESSION['nombre'] = $nombre;
                        $_SESSION['telefono'] = $telefono;     
                        $_SESSION['direccion'] = $direccion;   
                        $_SESSION['tipo'] = $tipo_usuario; 
                        $_SESSION['restaurante_id'] = null; // Un comensal no pertenece a ninguna empresa

                        // Mandamos directo al index público a comprar
                        header("Location: ../../pages/index");
                        exit();
                    }
                    
                } else {
                    header("Location: ../usuarios/iniciodesesion?error=db");
                    exit();
                }
            }
        }
    } else {
        header("Location: ../usuarios/iniciodesesion?error=vacio");
        exit();
    }
}
?>