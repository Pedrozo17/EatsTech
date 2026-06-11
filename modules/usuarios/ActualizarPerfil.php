<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../config/Configuracion.php';

// 1. Validar que el usuario tenga sesión activa
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../../pages/index");
    exit;
}

$user_id = $_SESSION['id_usuario'] ?? 0;

// 2. Verificar que la petición venga por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
    // Limpiar y capturar las variables del formulario
    $nombre = mysqli_real_escape_string($db, trim($_POST['nombre'])); // Mantenemos 'name' porque así está en tu <input name="name">
    $direccion = mysqli_real_escape_string($db, trim($_POST['direccion']));
    $contrasena_nueva = trim($_POST['contrasena']);

    // 3. Determinar si se actualiza la contraseña o no
    if (!empty($contrasena_nueva)) {
        // Si el usuario escribió una nueva contraseña, la encriptamos de forma segura
        $contrasena_encriptada = password_hash($contrasena_nueva, PASSWORD_DEFAULT);
        
        // Consulta incluyendo la contraseña
        $sql = "UPDATE datos SET nombre = ?, direccion = ?, contraseña = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $direccion, $contrasena_encriptada, $user_id);
    } else {
        // Si la dejó en blanco, solo actualizamos nombre y dirección sin tocar la contraseña actual
        $sql = "UPDATE datos SET nombre = ?, direccion = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $direccion, $user_id);
    }

    // 4. Ejecutar la actualización y responder con SweetAlert2
    if ($stmt->execute()) {
        // Actualizamos las variables de sesión para que los cambios se vean reflejados de inmediato en el Header
        $_SESSION['nombre'] = $nombre;
        $_SESSION['direccion'] = $direccion;

        // Mandamos una alerta de éxito y redirigimos de vuelta a Perfil.php
        echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body style='background-color: #141414;'>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Perfil Actualizado!',
                    text: 'Tus datos se guardaron correctamente.',
                    confirmButtonColor: '#FFB900',
                    background: '#242424',
                    color: '#FFF'
                }).then(() => {
                    window.location.href = 'Perfil.php';
                });
            </script>
        </body>
        </html>";
        exit();
    } else {
        // En caso de algún error inesperado en la base de datos
        echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body style='background-color: #141414;'>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error al actualizar',
                    text: 'No se pudieron guardar los cambios, intenta de nuevo.',
                    confirmButtonColor: '#FFB900',
                    background: '#242424',
                    color: '#FFF'
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
        </html>";
        exit();
    }
} else {
    // Si intentan entrar al archivo escribiendo la URL directamente, los devolvecos
    header("Location: Perfil.php");
    exit;
}
?>