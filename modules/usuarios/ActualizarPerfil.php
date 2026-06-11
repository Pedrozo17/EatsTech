<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Incluir la configuración de la base de datos
include __DIR__ . '/../../config/Configuracion.php';

// 2. Validar que el usuario esté realmente logueado
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header("Location: ../../pages/index");
    exit;
}

// 3. Validar que la petición venga por método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id_usuario'] ?? 0;
    $nombre = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validación básica de campos obligatorios
    if (empty($nombre) || empty($direccion)) {
        header("Location: Perfil.php?error=campos_vacios");
        exit;
    }

    // 4. LÓGICA DE ACTUALIZACIÓN
    if (!empty($password)) {
        // SI EL USUARIO ESCRIBIÓ UNA CONTRASEÑA NUEVA:
        // (Usa password_hash si manejas contraseñas encriptadas, o déjala directa si es texto plano en tu prototipo)
        // Ejemplo encriptada (Recomendado): $password_segura = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE datos SET nombre = ?, direccion = ?, contrasena = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $direccion, $password, $user_id);
    } else {
        // SI EL USUARIO DEJÓ LA CONTRASEÑA EN BLANCO: Solo actualiza Nombre y Dirección
        $sql = "UPDATE datos SET nombre = ?, direccion = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $direccion, $user_id);
    }

    // 5. EJECUTAR Y ACTUALIZAR VARIABLES DE SESIÓN
    if ($stmt->execute()) {
        // Actualizamos el nombre en la sesión activa para que el sistema y el LIKE no se rompan
        $_SESSION['nombre'] = $nombre;
        
        // Redirecciona con éxito
        header("Location: Perfil.php?status=success");
        exit;
    } else {
        // Redirecciona con error de base de datos
        header("Location: Perfil.php?error=db_fail");
        exit;
    }
} else {
    header("Location: Perfil.php");
    exit;
}
?>