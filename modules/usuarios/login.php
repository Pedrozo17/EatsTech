<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
include("../../config/con_db.php");

$paginas = [
    'camaron' => '../../pages/camaron ',
    'index'     => '../../pages/index '
];

$redirect = isset($_GET['redirect']) && isset($paginas[$_GET['redirect']]) 
    ? $paginas[$_GET['redirect']] 
    : '../../pages/index ';

$redirect_param = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if (isset($_POST['login'])) {
    $correo = mysqli_real_escape_string($db, trim($_POST['correo']));
    $contraseña = trim($_POST['contraseña']);
    $tipo_usuario = $_POST['tipo_usuario']; // Captura 'persona' o 'empresa'
    
    $consulta = "SELECT * FROM datos WHERE correo='$correo'";
    $resultado = mysqli_query($db, $consulta);
    
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        
        if (password_verify($contraseña, $usuario['contraseña'])) {
            
            // SEGURIDAD CRÍTICA: Validar si el rol de la BD coincide con el selector del formulario
            if ($usuario['tipo'] !== $tipo_usuario) {
                header("Location: ../usuarios/iniciodesesion?error=rol_incorrecto&redirect=" . $redirect_param);
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
            // REDIRECCIÓN INTELIGENTE SEGÚN EL ROL Y EL ESTADO DE COMPRA
            // =========================================================
            if ($usuario['tipo'] === 'empresa') {
                // Capturamos el slug de la carpeta enviado desde el selector dinámico
                $carpeta_destino = isset($_POST['restaurante_slug']) && !empty($_POST['restaurante_slug']) 
                                   ? trim($_POST['restaurante_slug']) 
                                   : 'admin';
        
                // Redirección al Dashboard del restaurante seleccionado
                header("Location: ../../" . $carpeta_destino . "/admin_dashboard ");
                exit();

            } else {
                // 🔥 SI ES PERSONA (CLIENTE): Verificamos primero si tiene un pago pendiente por procesar
                if (isset($_SESSION['redireccion_post_login'])) {
                    $destino_pago = $_SESSION['redireccion_post_login'];
                    
                    // Limpiamos la variable para que no se quede estancada en las siguientes sesiones
                    unset($_SESSION['redireccion_post_login']);
                    
                    // Lo redirigimos de inmediato a la pantalla de pago donde fue interrumpido
                    header("Location: " . $destino_pago);
                    exit();
                } else {
                    // Flujo normal de navegación si no estaba intentando pagar nada
                    header("Location: " . $redirect);
                    exit();
                }
            }
            // =========================================================

        } else {
            // Contraseña incorrecta
            header("Location: ../usuarios/iniciodesesion?error=1&redirect=" . $redirect_param);
            exit();
        }
    } else {
        // Correo no existe
        header("Location: ../usuarios/iniciodesesion?error=no_existe&redirect=" . $redirect_param);
        exit();
    }
}
?>