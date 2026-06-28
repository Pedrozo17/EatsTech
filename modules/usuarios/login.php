<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
include("../../config/con_db.php");

$paginas = [
    'camaron' => '../../pages/camaron',
    'index'   => '../../pages/index'
];

$redirect = isset($_GET['redirect']) && isset($paginas[$_GET['redirect']]) 
    ? $paginas[$_GET['redirect']] 
    : '../../pages/index';

$redirect_param = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if (isset($_POST['login'])) {
    $correo = mysqli_real_escape_string($db, trim($_POST['correo']));
    $contraseña = trim($_POST['contraseña']);
    $tipo_usuario = $_POST['tipo_usuario']; // Captura 'persona' o 'empresa'
    
    // Hacemos una consulta con INNER JOIN si es empresa para traer los datos de su restaurante asignado de golpe
    $consulta = "
        SELECT u.*, r.slug_carpeta, r.id AS r_id 
        FROM datos u
        LEFT JOIN restaurantes r ON u.restaurante_id = r.id 
        WHERE u.correo='$correo'
    ";
    
    // Nota: He usado LEFT JOIN para que si es un cliente ('persona'), la consulta no falle aunque r.id sea NULL.
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
            // REDIRECCIÓN INTELIGENTE MULTI-INQUILINO SAAS
            // =========================================================
            if ($usuario['tipo'] === 'empresa') {
                
                // Si por alguna razón el usuario no tiene restaurante asignado en la BD
                if (empty($usuario['r_id'])) {
                    header("Location: ../usuarios/iniciodesesion?error=sin_restaurante");
                    exit();
                }

                // GUARDAMOS LOS CANDADOS SAAS EN LA SESIÓN DE FORMA TOTALMENTE SEGURA
                $_SESSION['restaurante_id']   = intval($usuario['r_id']);
                $_SESSION['slug_restaurante'] = $usuario['slug_carpeta']; 
        
                // Redirección al Dashboard usando el slug oficial registrado en su Base de Datos
                header("Location: ../../" . $usuario['slug_carpeta'] . "/admin_dashboard");
                exit();

            } else {
                // El cliente final no posee un ID de restaurante corporativo corporativo
                $_SESSION['restaurante_id'] = null;

                // 🔥 SI ES PERSONA (CLIENTE): Verificamos primero si tiene un pago pendiente por procesar
                if (isset($_SESSION['redireccion_post_login'])) {
                    $destino_pago = $_SESSION['redireccion_post_login'];
                    unset($_SESSION['redireccion_post_login']);
                    
                    header("Location: " . $destino_pago);
                    exit();
                } else {
                    header("Location: " . $redirect);
                    exit();
                }
            }
            // =========================================================

        } else {
            header("Location: ../usuarios/iniciodesesion?error=1&redirect=" . $redirect_param);
            exit();
        }
    } else {
        header("Location: ../usuarios/iniciodesesion?error=no_existe&redirect=" . $redirect_param);
        exit();
    }
}
?>