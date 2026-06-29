<?php
session_start();
// Asegúrate de incluir tu archivo de conexión a la base de datos aquí
include_once '../config/Configuracion.php'; 

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: ../modules/usuarios/iniciodesesion");
    exit();
}

$correoEmpresa = $_SESSION['correo'] ?? 'correo_desconocido@empresa.com';
$planRaw = $_GET['plan'] ?? 'start';

// 1. Determinar el plan, el periodo y el precio (Simulación)
$periodo = 'mensual';
$planLimpio = $planRaw;

if (strpos($planRaw, '_anual') !== false) { 
    $periodo = 'anual';
    $planLimpio = str_replace('_anual', '', $planRaw);
}

// Asignación de precios según tu vista para el registro
$monto = 0;
if ($planLimpio === 'basic') {
    $monto = ($periodo === 'anual') ? 390000 : 39000;
} elseif ($planLimpio === 'pro') {
    $monto = ($periodo === 'anual') ? 690000 : 69000;
} elseif ($planLimpio === 'enterprise') {
    $monto = ($periodo === 'anual') ? 1290000 : 129000;
}

// 2. Guardar en el Historial de Pagos

global $db; // o tu variable de conexión
$stmt = $db->prepare("INSERT INTO historial_pagos_planes (correo_empresa, plan_adquirido, periodo, monto_pagado) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $correoEmpresa, $planLimpio, $periodo, $monto);
$stmt->execute();
$stmt->close();

// OPCIONAL: Actualizar el plan directamente en la tabla 'restaurantes'
// 1. Traducimos el nombre del plan al ID correspondiente en tu base de datos
$plan_id_db = 1; // Por defecto Start (ID 1)
if ($planLimpio === 'basic') {
    $plan_id_db = 2;
} elseif ($planLimpio === 'pro') {
    $plan_id_db = 3;
} elseif ($planLimpio === 'enterprise') {
    $plan_id_db = 4;
}

// 2. Ejecutamos la actualización
// OJO: En el WHERE asumo que tienes el ID del restaurante en la sesión. 
// Si en tu tabla 'restaurantes' no hay columna 'correo', debes actualizar por 'id'.
$idRestaurante = $_SESSION['restaurante_id']; // Verifica que esta sea tu variable de sesión real

$stmt_upd = $db->prepare("UPDATE restaurantes SET plan_id = ? WHERE id = ?");
$stmt_upd->bind_param("ii", $plan_id_db, $idRestaurante);
$stmt_upd->execute();
$stmt_upd->close();


// 3. ACTUALIZAR LA SESIÓN (Esto es clave para que al volver atrás se vea el plan activo)
$_SESSION['plan'] = $planLimpio;

// 4. Preparar URL de WhatsApp (Simulación de envío de comprobante)
$numeroWhatsApp = "573142756300";
$mensaje = "¡Hola! 🚀 He realizado el pago de mi suscripción en *EatsTech*.\n\n";
$mensaje .= "*Empresa:* " . $correoEmpresa . "\n";
$mensaje .= "*Plan Adquirido:* " . strtoupper($planLimpio) . "\n";
$mensaje .= "*Modalidad:* " . ucfirst($periodo) . "\n";
$mensaje .= "*Total Pagado:* $" . number_format($monto, 0, ',', '.') . " COP\n\n";
$mensaje .= "Adjunto mi comprobante de pago para la activación.";

$urlWhatsApp = "https://api.whatsapp.com/send?phone=" . $numeroWhatsApp . "&text=" . urlencode($mensaje);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando Pago - EatsTech</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/logo.png">

    <!-- SweetAlert2 para la alerta bonita -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #242424; color: #fff; font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
    </style>
</head>
<body>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: '¡Simulación Exitosa!',
                html: 'Tu plan <b><?php echo strtoupper($planLimpio); ?></b> se ha registrado.<br><br>Serás redirigido a WhatsApp para adjuntar tu comprobante de pago.',
                icon: 'success',
                confirmButtonText: 'Ir a WhatsApp',
                confirmButtonColor: '#FFB900',
                background: '#323232',
                color: '#FFFFFF',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Abre WhatsApp en una pestaña nueva
                    window.open("<?php echo $urlWhatsApp; ?>", "_blank");
                    // Redirige la pestaña actual de vuelta a los planes (ya actualizados)
                    window.location.href = "cambiar_plan.php";
                }
            });
        });
    </script>
</body>
</html>