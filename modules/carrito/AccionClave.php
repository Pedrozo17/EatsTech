<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set("America/Bogota");
include '../../config/Configuracion.php'; // Tu conexión oficial $db

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])){
    
    // --- ACCIÓN 1: GENERAR CÓDIGO Y ENVIAR POR WHATSAPP (FLUJO UX AUTOMÁTICO) ---
    if($_POST['action'] == 'solicitar_codigo'){
        $correo = $db->real_escape_string($_POST['correo']);
        
        $checkEmail = $db->query("SELECT id, telefono FROM registro.datos WHERE correo = '$correo'");
        
        if($checkEmail && $checkEmail->num_rows > 0){
            $userRow = $checkEmail->fetch_assoc();
            $telefonoCliente = preg_replace('/[^0-9]/', '', $userRow['telefono']);
            
            if (strlen($telefonoCliente) == 10) {
                $telefonoCliente = "57" . $telefonoCliente;
            }

            $codigo = rand(100000, 999999);
            $expira = date("Y-m-d H:i:s", strtotime('+15 minutes'));
            
            $update = $db->query("UPDATE registro.datos SET codigo_reset = '$codigo', codigo_expira = '$expira' WHERE correo = '$correo'");
            
            if($update){
                $_SESSION['reset_correo'] = $correo; 
                
                $mensajeTxt = "🔐 *Camaron Express — Seguridad*\n\nHola, has solicitado restablecer tu contraseña.\n\nTu código de verificación de 6 dígitos es:\n👉 *".$codigo."*\n\n_(Este código expira en 15 minutos)_";
                $mensajeUrl = urlencode($mensajeTxt);
                $enlaceWhatsapp = "https://wa.me/" . $telefonoCliente . "?text=" . $mensajeUrl;
                
                // 🪄 TRUCO MÁGICO: Inyectamos el enlace real en la pestaña que el navegador ya aprobó,
                // y movemos la ventana principal al formulario de verificación. ¡Cero clics extra!
                echo "<script>
                    window.open('$enlaceWhatsapp', 'enlace_whatsapp');
                    window.location.href = '../../pages/VerificarCodigo.php';
                </script>";
                exit();
            } else {
                echo "<script>window.close('enlace_whatsapp'); window.location.href='../../pages/OlvideClave.php?error=db';</script>";
                exit();
            }
        } else {
            // Si el correo no existe, cerramos la pestaña huérfana que abrimos y devolvemos error
            echo "<script>window.close('enlace_whatsapp'); window.location.href='../../pages/OlvideClave.php?error=no_existe';</script>";
            exit();
        }
    }
    
    // --- ACCIÓN 2: VERIFICAR SI EL CÓDIGO ES CORRECTO ---
    if($_POST['action'] == 'verificar_codigo'){
        $codigoUser = $db->real_escape_string($_POST['codigo']);
        $correo = $_SESSION['reset_correo'];
        $fechaActual = date("Y-m-d H:i:s");
        
        // Consultamos el código guardado y la expiración
        $query = $db->query("SELECT codigo_reset, codigo_expira FROM registro.datos WHERE correo = '$correo'");
        $row = $query->fetch_assoc();
        
        if($row['codigo_reset'] == $codigoUser){
            // Comprobamos si no ha expirado
            if($fechaActual <= $row['codigo_expira']){
                $_SESSION['reset_permitido'] = true; // Habilitamos el paso final
                header("Location: ../../pages/NuevaClave.php");
                exit();
            } else {
                header("Location: ../../pages/VerificarCodigo.php?error=expirado");
                exit();
            }
        } else {
            header("Location: ../../pages/VerificarCodigo.php?error=incorrecto");
            exit();
        }
    }
    
// --- ACCIÓN 3: CAMBIAR LA CONTRASEÑA EN LA BD (CON ENCRIPCION SEGURA) ---
    if($_POST['action'] == 'actualizar_clave'){
        if(!empty($_SESSION['reset_permitido']) && !empty($_SESSION['reset_correo'])){
            $nuevaClave = trim($_POST['nueva_clave']);
            $correo = $_SESSION['reset_correo'];
            
            // 🟢 APLICAMOS LA ENCRIPCION CORRECTA QUE USA TU PROYECTO
            $claveEncriptada = password_hash($nuevaClave, PASSWORD_DEFAULT);
            
            // Guardamos la contraseña ya encriptada en la base de datos
            $updateClave = $db->query("UPDATE registro.datos SET contraseña = '$claveEncriptada', codigo_reset = NULL, codigo_expira = NULL WHERE correo = '$correo'");
            
            if($updateClave){
                // Limpiamos y destruimos la sesión de recuperación para no dejar rastros
                session_unset();
                session_destroy();
                
                // Iniciamos una limpia para que el redireccionamiento no tenga problemas
                session_start();
                
                // Redirigimos al Login principal con éxito
                header("Location: \Eatstech\modules\usuarios\iniciodesesion.php?reset=exito"); 
                exit();
            }
        }
    }
} else {
    header("Location: ../../pages/OlvideClave.php");
    exit();
}
?>