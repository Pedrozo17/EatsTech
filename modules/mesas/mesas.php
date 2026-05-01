<?php
// Parámetros de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurante";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL
$sql = "SELECT nombre, celular, personas, fecha, hora, mensaje FROM comiditadeli";  
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de Reservas</title>
    <!-- Vincular el CSS externo -->
    <link rel="stylesheet" href="pintalamesa.css">
</head>
<body>

<h2>Reservas</h2>

<table>
    <tr>
        
        <th>Nombre</th>
        <th>Celular</th>
        <th>Personas</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Mensaje</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        // Salida de datos en cada fila
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['celular'] . "</td>";
            echo "<td>" . $row['personas'] . "</td>";
            echo "<td>" . $row['fecha'] . "</td>";
            echo "<td>" . $row['hora'] . "</td>";
            echo "<td>" . $row['mensaje'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No hay resultados</td></tr>";
    }

    // Cerrar la conexión
    $conn->close();
    ?>
</table>

</body>
</html>
