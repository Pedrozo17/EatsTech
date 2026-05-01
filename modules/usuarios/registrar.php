<?php 

include("/config/con_db.php");

if (isset($_POST['register'])) {
    if (strlen($_POST['nombre']) >= 1 && 
    strlen($_POST['correo']) >= 1 && 
    strlen($_POST['cedula']) >= 1 && 
    strlen($_POST['contraseña']) >= 1 && 
    strlen($_POST['confirmar_contraseña']) >= 1) {
        $name = trim($_POST['nombre']);
        $correo = trim($_POST['correo']);
        $cedula = trim($_POST['cedula']);
        $contraseña = trim($_POST['contraseña']);
        $confirmar_contraseña = trim($_POST['confirmar_contraseña']);
        $consulta = "INSERT INTO datos(nombre, correo, cedula, contraseña, confirmar_contraseña) VALUES ('$name','$correo','$cedula','$contraseña','$confirmar_contraseña')";
        $resultado = mysqli_query($conex, $consulta);
        
        if ($resultado) {
            ?> 
            <h3 class="ok"></h3>
            <?php
        } else {
            ?> 
            <h3 class="bad"></h3>
            <?php
        }
    } else {
        ?> 
        <h3 class="bad">¡Por favor complete los campos!</h3>
        <?php
    }
}

?>
<?php
if (isset($_POST['login'])) {
    if (strlen($_POST['correo']) >= 1 && strlen($_POST['contraseña']) >= 1) {
        $correo = trim($_POST['correo']);
        $contraseña = trim($_POST['contraseña']);
        
        // Consulta para verificar el usuario
        $consulta = "SELECT * FROM datos WHERE correo='$correo' AND contraseña='$contraseña'";
        $resultado = mysqli_query($conex, $consulta);
        
        if (mysqli_num_rows($resultado) > 0) {
            header("Location: /pages/index.html");
            exit();
        } else {
            ?> 
            <h3 class="bad"></h3>
            <?php
        }
    } else {
        ?> 
        <h3 class="bad"></h3>
        <?php
    }
}
?>

