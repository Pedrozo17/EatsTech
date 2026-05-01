<?php
// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';
$clave = '';
$bd = 'restaurante';

$conn = new mysqli($host, $usuario, $clave, $bd);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres
$conn->set_charset("utf8");

// Recibir los datos del formulario
$nombre = $_POST['nombre'];
$celular = $_POST['celular'];
$personas = $_POST['personas'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$mensaje = $_POST['mensaje'];

// Preparar la consulta
$stmt = $conn->prepare("INSERT INTO comiditadeli (nombre, celular, personas, fecha, hora, mensaje) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisss", $nombre, $celular, $personas, $fecha, $hora, $mensaje);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "Reservación realizada con éxito";
} else {
    echo "Error al realizar la reservación: " . $stmt->error;
}

// Cerrar la conexión
$conn->close();
?>