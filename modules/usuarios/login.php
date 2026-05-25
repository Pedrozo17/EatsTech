<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../../config/con_db.php");

$paginas = [
    'casarolla' => '../pages/casarolla.php',
    'index'     => '../pages/index.php'
];

$redirect = isset($_GET['redirect']) && isset($paginas[$_GET['redirect']]) 
    ? $paginas[$_GET['redirect']] 
    : '../pages/index.php';

$redirect_param = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if (isset($_POST['login'])) {
    $correo = mysqli_real_escape_string($conex, trim($_POST['correo']));
    $contraseña = trim($_POST['contraseña']);
    $tipo_usuario = $_POST['tipo_usuario']; // Captura 'persona' o 'empresa'
    
    $consulta = "SELECT * FROM datos WHERE correo='$correo'";
    $resultado = mysqli_query($conex, $consulta);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        if (password_verify($contraseña, $usuario['contraseña'])) {
            
            // SEGURIDAD CRÍTICA: Validar si el rol de la BD coincide con el selector del formulario
            if ($usuario['tipo'] !== $tipo_usuario) {
                header("Location: ../usuarios/iniciodesesion.php?error=rol_incorrecto&redirect=" . $redirect_param);
                exit();
            }

            // Guardamos las variables globales comunes en la sesión
            $_SESSION['logueado'] = true;
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['telefono'] = $usuario['telefono'];   
            $_SESSION['direccion'] = $usuario['direccion']; 
            $_SESSION['tipo'] = $usuario['tipo']; 
            
            // =========================================================
            // REDIRECCIÓN SEGÚN EL ROL
            // =========================================================
            if ($usuario['tipo'] === 'empresa') {
                // Capturamos el slug de la carpeta enviado desde el selector dinámico
                $carpeta_destino = isset($_POST['restaurante_slug']) && !empty($_POST['restaurante_slug']) 
                                   ? trim($_POST['restaurante_slug']) 
                                   : 'admin';
        
                // Redirección al Dashboard del restaurante seleccionado
                header("Location: ../" . $carpeta_destino . "/admin_dashboard.php");
                exit();

            } else {
                // SI ES PERSONA (USUARIO): Lo mandamos a la página que quería visitar (ej: casarolla) o al index
                header("Location: " . $redirect);
                exit();
            }
            // =========================================================

        } else {
            // Contraseña incorrecta
            header("Location: ../usuarios/iniciodesesion.php?error=1&redirect=" . $redirect_param);
            exit();
        }
    } else {
        // Correo no existe
        header("Location: ../usuarios/iniciodesesion.php?error=no_existe&redirect=" . $redirect_param);
        exit();
    }
}
?>