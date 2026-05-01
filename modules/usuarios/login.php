<?php
include("../../config/con_db.php");

if (isset($_POST['login'])) {
    if (strlen($_POST['correo']) >= 1 && strlen($_POST['contraseña']) >= 1) {
        $correo = trim($_POST['correo']);
        $contraseña = trim($_POST['contraseña']);
        
        // Consulta para verificar el usuario
        $consulta = "SELECT * FROM datos WHERE correo='$correo' AND contraseña='$contraseña'";
        $resultado = mysqli_query($conex, $consulta);
        
        if (mysqli_num_rows($resultado) > 0) {
            header("Location: /Eatstech\pages\index.html");
            exit();
        } else {
            ?> 
            <h3 class="bad">¡Correo o contraseña incorrectos!</h3>
            <?php
        }
    } else {
        ?> 
        <h3 class="bad">¡Por favor complete los campos!</h3>
        <?php
    }
}
?>
