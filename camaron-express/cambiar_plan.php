<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// SEGURIDAD: Solo empresas logueadas pueden cambiar plan
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: ../modules/usuarios/iniciodesesion");
    exit();
}

// Incluimos tu controlador
include_once 'control_plan.php';

// Dejamos por defecto 'start'
$plan_actual = 'start'; 

/**
 * CAPTURA REAL BASADA EN TU ARREGLO:
 * Leemos 'nombre_plan' desde $dataRestaurante, lo pasamos a minúsculas
 * y limpiamos espacios para que la comparación sea exacta.
 */
if (isset($dataRestaurante['nombre_plan']) && !empty($dataRestaurante['nombre_plan'])) {
    $plan_actual = trim(strtolower($dataRestaurante['nombre_plan']));
} elseif (isset($_SESSION['plan']) && !empty($_SESSION['plan'])) {
    $plan_actual = trim(strtolower($_SESSION['plan']));
}

/**
 * MAPEO TRADUCIDO A LA INTERFAZ:
 * Como tu base de datos devuelve "Enterprise (Developer Mode)", buscamos si el texto
 * contiene la palabra clave para marcar la tarjeta correcta en la vista.
 */
if (strpos($plan_actual, 'start') !== false || strpos($plan_actual, 'gratis') !== false) {
    $plan_actual = 'start';
} elseif (strpos($plan_actual, 'basic') !== false || strpos($plan_actual, 'basico') !== false) {
    $plan_actual = 'basic';
} elseif (strpos($plan_actual, 'pro') !== false) {
    // Evitamos que confunda 'enterprise pro' con el plan Pro normal
    if (strpos($plan_actual, 'enterprise') !== false) {
        $plan_actual = 'enterprise';
    } else {
        $plan_actual = 'pro';
    }
} elseif (strpos($plan_actual, 'enterprise') !== false || strpos($plan_actual, 'premium') !== false) {
    $plan_actual = 'enterprise';
} else {
    // Por seguridad, si no coincide con ninguna palabra, regresa a start
    $plan_actual = 'start'; 
}

// Ya puedes volver a comentar la línea del echo poniéndole las dos barras (//) si deseas.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes de Suscripción - EatsTech</title>
    <link rel="stylesheet" href="../assets/css/estiloADM.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/logo.png">
    <style>
:root {
    --bg-eatstech: #323232;
    --oro-eatstech: #FFB900;
    --blanco-texto: #FFFFFF;
    --tarjeta-fondo: #242424;
    --gris-detalle: #8a8a8a;
}

body {
    background-color: var(--bg-eatstech) !important;
    color: var(--blanco-texto);
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
}

.planes-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
    text-align: center;
}

.btn-back {
    display: inline-block;
    margin-bottom: 25px;
    color: var(--oro-eatstech);
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    transition: opacity 0.2s;
}
.btn-back:hover { opacity: 0.8; }

.planes-header h1 {
    color: var(--oro-eatstech);
    font-size: 36px;
    margin-bottom: 10px;
    font-weight: 600;
}

.planes-header p {
    color: var(--gris-detalle);
    margin-bottom: 30px;
    font-size: 16px;
}

.toggle-container {
    display: inline-flex;
    background: var(--tarjeta-fondo);
    padding: 5px;
    border-radius: 30px;
    margin-bottom: 40px;
    border: 1px solid #444;
}

.toggle-btn {
    background: transparent;
    border: none;
    color: var(--blanco-texto);
    padding: 10px 20px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    transition: all 0.3s;
}

.toggle-btn.active {
    background: var(--oro-eatstech);
    color: #141414;
}

.ahorro-badge {
    background: #28a745;
    color: white;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: 5px;
}

.grid-planes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    align-items: stretch;
}

.card-plan {
    background: var(--tarjeta-fondo);
    border-radius: 14px;
    padding: 35px 20px;
    border: 1px solid #444;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    transition: transform 0.3s ease, border-color 0.3s ease;
}

.card-plan:hover {
    transform: translateY(-8px);
}

.card-plan.destacado {
    border: 2px solid var(--oro-eatstech);
}

/* Tarjeta visual para el plan actualmente activo en BD */
.card-plan.plan-activo-card {
    border: 2px solid #28a745;
}

.badge-popular {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--oro-eatstech);
    color: #141414;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
}

.badge-activo {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: #28a745;
    color: white;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
}

.plan-name {
    font-size: 24px;
    color: var(--blanco-texto);
    margin: 0 0 5px 0;
    font-weight: 600;
}

.plan-ideal {
    font-size: 12px;
    color: var(--gris-detalle);
    margin-bottom: 20px;
    height: 35px;
}

.plan-price {
    font-size: 34px;
    font-weight: bold;
    color: var(--oro-eatstech);
    margin-bottom: 5px;
}

.plan-price span {
    font-size: 14px;
    color: var(--blanco-texto);
    font-weight: normal;
}

.plan-comision {
    font-size: 13px;
    color: #ff4a4a;
    margin-bottom: 25px;
    font-weight: 500;
}
.plan-comision.free-trans {
    color: #28a745;
}

.plan-features {
    list-style: none;
    padding: 0;
    margin: 0 0 35px 0;
    text-align: left;
}

.plan-features li {
    padding: 10px 0;
    color: #e0e0e0;
    font-size: 13px;
    border-bottom: 1px solid #333;
}

.plan-features li::before {
    content: "✓  ";
    color: var(--oro-eatstech);
    font-weight: bold;
}

.btn-elegir {
    display: block;
    background: transparent;
    color: var(--oro-eatstech);
    border: 1px solid var(--oro-eatstech);
    padding: 12px;
    text-align: center;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
    font-size: 13px;
    text-transform: uppercase;
    transition: all 0.2s ease;
}

.btn-elegir:hover {
    background: var(--oro-eatstech);
    color: #141414;
}

.btn-elegir.active-plan {
    background: #28a745;
    color: white;
    border-color: #28a745;
    cursor: not-allowed;
    pointer-events: none;
}

.btn-elegir.btn-gold {
    background: var(--oro-eatstech);
    color: #141414;
}
.btn-elegir.btn-gold:hover {
    background: transparent;
    color: var(--oro-eatstech);
}

/* ==========================================
   MEDIA QUERIES (RESPONSIVE DESIGN)
   ========================================== */

/* Tabletas y pantallas medianas (Dispositivos menores a 992px) */
@media (max-width: 992px) {
    .planes-container {
        margin: 30px auto;
    }
    .planes-header h1 {
        font-size: 32px;
    }
    .grid-planes {
        grid-template-columns: repeat(2, 1fr); /* 2 columnas en tablets */
        gap: 15px;
    }
}

/* Teléfonos móviles grandes (Dispositivos menores a 768px) */
@media (max-width: 768px) {
    .planes-container {
        margin: 20px auto;
        padding: 0 15px;
    }
    .planes-header h1 {
        font-size: 28px;
    }
    .planes-header p {
        font-size: 14px;
        margin-bottom: 20px;
    }
    .toggle-container {
        margin-bottom: 30px;
    }
    .toggle-btn {
        padding: 8px 15px;
        font-size: 13px;
    }
    .grid-planes {
        grid-template-columns: 1fr; /* 1 sola columna, tarjetas apiladas */
        gap: 25px; /* Más espacio vertical entre tarjetas */
    }
    .card-plan {
        padding: 30px 15px;
    }
    .card-plan:hover {
        transform: none; /* Desactivamos el efecto hover en móvil para evitar saltos táctiles */
    }
    .plan-ideal {
        height: auto; /* Permite que el texto fluya libremente en pantallas pequeñas */
        margin-bottom: 15px;
    }
}

/* Teléfonos móviles pequeños (Dispositivos menores a 480px) */
@media (max-width: 480px) {
    .planes-header h1 {
        font-size: 24px;
    }
    .plan-name {
        font-size: 20px;
    }
    .plan-price {
        font-size: 30px;
    }
    .toggle-container {
        display: flex;
        flex-direction: column; /* Apila los botones de Mes/Año si el espacio es muy reducido */
        border-radius: 15px;
        width: 100%;
        max-width: 250px;
        margin-left: auto;
        margin-right: auto;
    }
    .toggle-btn {
        border-radius: 10px;
    }
    .ahorro-badge {
        display: inline-block;
        margin-top: 2px;
        margin-left: 0;
    }
}
    </style>
</head>
<body>

    <div class="planes-container">
        <a href="./admin_dashboard" class="btn-back">⬅ Volver al Panel Administrativo</a>
        
        <div class="planes-header">
            <h1>Actualiza para potenciar aún mas tu negocio</h1>
            <p>Selecciona el modelo estratégico ideal para potenciar y automatizar las comandas de tu restaurante.</p>
        </div>

        <div class="toggle-container">
            <button class="toggle-btn active" onclick="cambiarPeriodo('mensual')">Mensual</button>
            <button class="toggle-btn" onclick="cambiarPeriodo('anual')">Anual <span class="ahorro-badge">Ahorra 2 meses</span></button>
        </div>

        <div class="grid-planes">
            
            <!-- PLAN START -->
            <div class="card-plan <?php echo ($plan_actual === 'start') ? 'plan-activo-card' : ''; ?>">
                <?php if ($plan_actual === 'start'): ?>
                    <div class="badge-activo">Tu Plan Activo</div>
                <?php endif; ?>
                <div>
                    <h3 class="plan-name">Plan Start</h3>
                    <div class="plan-ideal">Emprendimientos y cocinas ocultas validando su producto.</div>
                    <div class="plan-price"><span class="price-val" data-mensual="$0" data-anual="$0">$0</span><span> / mes</span></div>
                    <div class="plan-comision">Comisión: 15% x pedido</div>
                    <ul class="plan-features">
                        <li>Catálogo digital básico</li>
                        <li>Hasta 20 productos</li>
                        <li>Enlace para Instagram o WhatsApp</li>
                    </ul>
                </div>
                <a href="#" class="btn-elegir <?php echo ($plan_actual === 'start') ? 'active-plan' : ''; ?>">
                    <?php echo ($plan_actual === 'start') ? 'Plan Actual' : 'Seleccionar'; ?>
                </a>
            </div>

            <!-- PLAN BASIC -->
            <div class="card-plan <?php echo ($plan_actual === 'basic' || $plan_actual === 'basic_anual') ? 'plan-activo-card' : ''; ?>">
                <?php if ($plan_actual === 'basic' || $plan_actual === 'basic_anual'): ?>
                    <div class="badge-activo">Tu Plan Activo</div>
                <?php endif; ?>
                <div>
                    <h3 class="plan-name">Plan Basic</h3>
                    <div class="plan-ideal">Negocios pequeños que buscan ordenar sus flujos operativos.</div>
                    <div class="plan-price"><span class="price-val" data-mensual="$39.000" data-anual="$390.000">$39.000</span><span> / mes</span></div>
                    <div class="plan-comision">Comisión: 5% x pedido</div>
                    <ul class="plan-features">
                        <li>Hasta 50 productos</li>
                        <li>Panel de administración básico</li>
                        <li>Reporte de ventas simple mensual</li>
                    </ul>
                </div>
                <a href="procesar_pago?plan=basic" class="btn-elegir price-link <?php echo ($plan_actual === 'basic' || $plan_actual === 'basic_anual') ? 'active-plan' : ''; ?>" data-m-link="procesar_pago?plan=basic" data-a-link="procesar_pago?plan=basic_anual">
                    <?php echo ($plan_actual === 'basic' || $plan_actual === 'basic_anual') ? 'Plan Actual' : 'Adquirir'; ?>
                </a>
            </div>

            <!-- PLAN PRO -->
            <div class="card-plan <?php echo ($plan_actual === 'pro' || $plan_actual === 'pro_anual') ? 'plan-activo-card' : (!($plan_actual === 'basic' || $plan_actual === 'basic_anual' || $plan_actual === 'enterprise' || $plan_actual === 'enterprise_anual') ? 'destacado' : ''); ?>">
                <?php if ($plan_actual === 'pro' || $plan_actual === 'pro_anual'): ?>
                    <div class="badge-activo">Tu Plan Activo</div>
                <?php elseif (!($plan_actual === 'basic' || $plan_actual === 'basic_anual' || $plan_actual === 'enterprise' || $plan_actual === 'enterprise_anual')): ?>
                    <div class="badge-popular">Más Vendido</div>
                <?php endif; ?>
                <div>
                    <h3 class="plan-name">Plan Pro</h3>
                    <div class="plan-ideal">Locales y restaurantes medianos con flujo constante.</div>
                    <div class="plan-price"><span class="price-val" data-mensual="$69.000" data-anual="$690.000">$69.000</span><span> / mes</span></div>
                    <div class="plan-comision free-trans">Comisión: 0% (100% tuyo)</div>
                    <ul class="plan-features">
                        <li>Productos ilimitados</li>
                        <li>Monitor en tiempo real con sonido</li>
                        <li>Dashboard de estadísticas avanzadas</li>
                        <li>Gestión de domiciliarios propios</li>
                    </ul>
                </div>
                <a href="procesar_pago?plan=pro" class="btn-elegir <?php echo ($plan_actual === 'pro' || $plan_actual === 'pro_anual') ? 'active-plan' : 'btn-gold'; ?> price-link" data-m-link="procesar_pago?plan=pro" data-a-link="procesar_pago?plan=pro_anual">
                    <?php echo ($plan_actual === 'pro' || $plan_actual === 'pro_anual') ? 'Plan Actual' : 'Adquirir Pro'; ?>
                </a>
            </div>

            <!-- PLAN ENTERPRISE -->
            <div class="card-plan <?php echo ($plan_actual === 'enterprise' || $plan_actual === 'enterprise_anual') ? 'plan-activo-card' : ''; ?>">
                <?php if ($plan_actual === 'enterprise' || $plan_actual === 'enterprise_anual'): ?>
                    <div class="badge-activo">Tu Plan Activo</div>
                <?php endif; ?>
                <div>
                    <h3 class="plan-name">Plan Enterprise</h3>
                    <div class="plan-ideal">Cadenas locales consolidadas o marcas multisede.</div>
                    <div class="plan-price"><span class="price-val" data-mensual="$129.000" data-anual="$1.290.000">$129.000</span><span> / mes</span></div>
                    <div class="plan-comision free-trans">Comisión: 0% (100% tuyo)</div>
                    <ul class="plan-features">
                        <li>Todo lo del Plan Pro</li>
                        <li>Multisede (múltiples sucursales)</li>
                        <li>Integración de dominio propio</li>
                        <li>Soporte corporativo WhatsApp 24/7</li>
                    </ul>
                </div>
                <a href="procesar_pago?plan=enterprise" class="btn-elegir price-link <?php echo ($plan_actual === 'enterprise' || $plan_actual === 'enterprise_anual') ? 'active-plan' : ''; ?>" data-m-link="procesar_pago?plan=enterprise" data-a-link="procesar_pago?plan=enterprise_anual">
                    <?php echo ($plan_actual === 'enterprise' || $plan_actual === 'enterprise_anual') ? 'Plan Actual' : 'Adquirir Pro'; ?>
                </a>
            </div>

        </div>
    </div>

    <script>
        function cambiarPeriodo(periodo) {
            const botones = document.querySelectorAll('.toggle-btn');
            botones.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const precios = document.querySelectorAll('.price-val');
            precios.forEach(precio => {
                if(periodo === 'anual') {
                    precio.textContent = precio.getAttribute('data-anual');
                    precio.nextElementSibling.textContent = ' / año';
                } else {
                    precio.textContent = precio.getAttribute('data-mensual');
                    precio.nextElementSibling.textContent = ' / mes';
                }
            });

            const links = document.querySelectorAll('.price-link');
            links.forEach(link => {
                // Solo modificamos el href si no es el plan activo actualmente
                if(!link.classList.contains('active-plan')) {
                    if(periodo === 'anual') {
                        link.setAttribute('href', link.getAttribute('data-a-link'));
                    } else {
                        link.setAttribute('href', link.getAttribute('data-m-link'));
                    }
                }
            });
        }
    </script>
</body>
</html>