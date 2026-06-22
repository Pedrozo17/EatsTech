<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - RedThings</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #1a1a1a; /* Negro mate / gris oscuro del fondo general */
            color: #ffffff; /* Texto principal en blanco */
            line-height: 1.6;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            box-sizing: border-box;
        }

        .terms-box {
            /* Tarjeta contenedora gris carbón basada en image_9d9fd0.png */
            background-color: #222222; 
            border: 1px solid #2d2d2d;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            
            max-width: 850px;
            width: 100%;
            padding: 50px;
            border-radius: 16px; /* Bordes suavizados de la tarjeta */
            box-sizing: border-box;
        }

        h1 {
            font-family: 'Forum', cursive;
            color: #ffffff;
            font-size: 2.2rem;
            letter-spacing: 1px;
            border-bottom: 1px solid #333333;
            padding-bottom: 15px;
            margin-top: 0;
            text-transform: uppercase;
        }

        /* El color amarillo vibrante extraído de la imagen para los títulos intermedios */
        h1 span, h3 {
            color: #ffbc00; 
        }

        h3 {
            font-family: 'Forum', cursive;
            font-size: 1.4rem;
            letter-spacing: 2px;
            margin-top: 35px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        p, li {
            font-size: 15px;
            color: #aaaaaa; /* Gris claro para los textos largos, igual que en los inputs */
            text-align: justify;
            margin-bottom: 15px;
        }

        ul {
            padding-left: 20px;
            margin-bottom: 25px;
        }

        li {
            margin-bottom: 8px;
        }

        .highlight {
            color: #ffbc00; /* Resaltados en el texto con el amarillo de la marca */
            font-weight: 700;
        }

        .footer-terms {
            margin-top: 50px;
            font-size: 13px;
            color: #555555;
            text-align: center;
            border-top: 1px solid #333333;
            padding-top: 25px;
        }

        /* Botón principal adaptado al estilo de "Enviar Mensaje" de tu imagen */
        .btn-volver {
            display: inline-block;
            background-color: #ffbc00; /* Fondo amarillo sólido */
            color: #1a1a1a; /* Texto oscuro para contraste */
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 30px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-volver:hover {
            background-color: #e0a500; /* Un tono un poco más oscuro al pasar el mouse */
        }

        .btn-volver:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

    <div class="terms-box">
        <button onclick="window.close();" class="btn-volver">Cerrar Ventana</button>
        
        <h1>Términos y Condiciones de Uso y <span>Política de Datos</span></h1>
        <p><strong>Última actualización:</strong> Junio de 2026<br><strong>Ubicación del Servicio:</strong> Mosquera, Cundinamarca, Colombia</p>
        
        <p>El presente documento establece los Términos y Condiciones bajo los cuales se rige el uso del aplicativo web y plataforma SaaS <span class="highlight">RedThings</span>. Al registrarse, acceder o utilizar nuestros servicios, usted (en adelante, "el Usuario", ya sea en calidad de "Persona/Cliente" o "Empresa/Restaurante") acepta de manera expresa, voluntaria e irrevocable el contenido de las presentes cláusulas.</p>

        <h3>1. OBJETO DE LA PLATAFORMA</h3>
        <p>RedThings es un software modular basado en la nube que opera bajo el modelo SaaS (Software as a Service). Su propósito es ofrecer soluciones digitales a establecimientos gastronómicos en Mosquera, Funza y sus alrededores. La plataforma cuenta con dos flujos operativos principales:</p>
        <ul>
            <li><strong>Perfil Empresa/Restaurante:</strong> Acceso al panel de administración (<em>admin_dashboard</em>) para la gestión de catálogos de menús, control de inventario base, monitor de compras en línea y activación de menús interactivos por códigos QR para mesas físicas.</li>
            <li><strong>Perfil Persona/Cliente:</strong> Acceso al catálogo digital para la visualización de productos, realización de pedidos o compras en línea, y autogestión de comandas en mesas físicas especificando la mesa al ordenar.</li>
        </ul>

        <h3>2. REGISTRO, CUENTA Y SEGURIDAD</h3>
        <p>Para hacer uso de las herramientas avanzadas, el Usuario debe registrarse profesionalmente proporcionando datos verídicos y actualizados. El uso de la cuenta es personal e intransferible. El Usuario es el único responsable de mantener la confidencialidad de su credencial de acceso. RedThings se reserva el derecho de suspender cuentas que utilicen datos falsos o alteren el ecosistema informático.</p>

        <h3>3. EXCLUSIÓN DE RESPONSABILIDAD COMERCIAL</h3>
        <p>RedThings actúa exclusivamente como un proveedor de servicios tecnológicos e intermediario digital. Por lo tanto, la preparación, porciones, calidad, higiene, precios y entrega de los alimentos son responsabilidad única y exclusiva de la <span class="highlight">Empresa/Restaurante</span> que ofrece el producto. RedThings no se hace responsable por disputas de pago o inconformidades en el servicio ocurridas entre el Cliente y el Restaurante.</p>

        <h3>4. POLÍTICA DE SUSCRIPCIÓN (Para Empresas)</h3>
        <p>El acceso a los módulos de administración para restaurantes se rige bajo un modelo de suscripción periódica según el plan seleccionado (Virtual o Híbrido con QR). El impago de la suscripción mensual otorgará a RedThings la facultad de suspender de forma temporal o definitiva el acceso al <em>admin_dashboard</em> y a las cartas QR vinculadas.</p>

        <h3>5. PROPIEDAD INTELECTUAL</h3>
        <p>Todo el código fuente, la arquitectura de tres capas, el diseño de la interfaz de usuario (UX/UI), logotipos, bases de datos y algoritmos de la plataforma RedThings son propiedad exclusiva de sus desarrolladores (<span class="highlight">Juan Fernández, Cristopher Romero y Samuel Pedrozo</span>). Queda estrictamente prohibida su reproducción total o parcial, ingeniería inversa o alteración sin autorización expresa.</p>

        <h3>6. POLÍTICA DE TRATAMIENTO DE DATOS PERSONALES (LEY 1581 DE 2012)</h3>
        <p>En cumplimiento de la legislación colombiana (Habeas Data), RedThings informa que los datos recolectados en el formulario de registro se usarán con el fin de operar la plataforma, gestionar el envío de pedidos a domicilio, permitir la identificación en las mesas por QR y procesar las credenciales de administración. Cualquier Usuario tiene derecho a conocer, actualizar, rectificar o solicitar la eliminación de sus datos personales en cualquier momento.</p>

        <div class="footer-terms">
            © 2026 RedThings - ADSO - SENA CBA Mosquera. Todos los derechos reservados.
        </div>
    </div>

</body>
</html>