<?php
// Datos de conexión a la base de datos
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'carrito';

// Crear conexión
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);     
// Verificar la conexión
if (!$db) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener datos del formulario
$id_pedido = $_POST['id_pedido'];
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$cantidad = $_POST['cantidad'];
$numero_tarjeta = $_POST['numero_tarjeta'];
$fecha_expiracion = $_POST['fecha_expiracion'];
$codigo_seguridad = $_POST['codigo_seguridad'];
$metodo_pago = $_POST['metodo_pago'];

// Insertar datos en la base de datos
$sql = "INSERT INTO pagos ( id_pedido, nombre, email, cantidad, numero_tarjeta, fecha_expiracion, codigo_seguridad, metodo_pago) 
        VALUES ( '$id_pedido', '$nombre', '$email', '$cantidad', '$numero_tarjeta', '$fecha_expiracion', '$codigo_seguridad', '$metodo_pago')";

if (mysqli_query($db, $sql)) {
    // Pago exitoso, mostrar mensaje emergente y redirigir
    echo "<script type='text/javascript'>
            alert('Pago exitoso');
            window.location.href = '../carrito/carritodecompras.php';
          </script>";
} else {
    // Pago fallido, mostrar mensaje emergente y redirigir
    echo "<script type='text/javascript'>
            alert('El pago no fue exitoso, por favor intenta de nuevo.');
            window.location.href = '../pages/RealizarPago.html';
          </script>";
}

// Cerrar la conexión
mysqli_close($db);
?>


