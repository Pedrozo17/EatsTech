<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/estilo2.css">
    <script defer src="/assets/js/main.js"></script>
    <title>Document</title>
</head>
<body>
    <div class="container right-panel-active">
        <!-- Sign Up -->
        <div class="container__form container--signup">
        <h2 class="form__title">Sign up</h2>
        <form method="post">
            <input type="text" name="nombre" placeholder="Nombre completo">
            <input type="correo" name="correo" placeholder="correo">
            <input type="text" name="cedula" placeholder="Numero de cedula">
            <input type="password" name="contraseña" placeholder="contraseña">
            <input type="password" name="confirmar_contraseña" placeholder="confirmar_contraseña">
            <input type="submit" name="register">
        </form>
        </div>
    
        <!-- Sign In -->
        <div class=" container__form container--signin">
        <h2 class="form__title">Sign In</h2>
        <form method="post">
                <input type="correo" name="correo" placeholder="correo">
                <input type="password" name="contraseña" placeholder="contraseña">
                <input type="submit" name="login">
            </form>
        </div>
    
        <!-- Overlay -->
        <div class="container__overlay">
            <div class="overlay">
                <div class="overlay__panel overlay--left">
                    <button class="btn" id="signIn">Sign In</button>
                </div>
                <div class="overlay__panel overlay--right">
                    <button class="btn" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <?php 
                include("/modules/usuarios/registrar.php");
            ?>
</body>
</html>