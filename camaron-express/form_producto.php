<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'empresa') { 
    header("Location: ../modules/usuarios/iniciodesesion"); 
    exit(); 
}

include_once 'control_plan.php';
include("../config/Configuracion.php");

$es_edicion = false;
$nombre = $descripcion = $precio = "";
$stock = 0; 
$id = null;

// Capturamos el id del restaurante desde el control del plan
$id_restaurante_actual = (int)$dataRestaurante['restaurante_id'];

if (isset($_GET['id'])) {
    $es_edicion = true;
    $id = intval($_GET['id']);
    
    // Validamos usando sentencia preparada que el producto le pertenezca al restaurante logueado
    $stmt = $db->prepare("SELECT * FROM mis_productos WHERE id = ? AND restaurante_id = ?");
    $stmt->bind_param("ii", $id, $id_restaurante_actual);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($prod = $res->fetch_assoc()) {
        $nombre = $prod['name'];        
        $descripcion = $prod['description'] ?? '';  
        $precio = $prod['price'];       
        $stock = $prod['stock']; 
    } else {
        // Bloqueo total de intrusión si intentan cambiar el ID desde la URL
        die("⚠️ Alerta de Seguridad: No tienes permisos para editar este producto o el registro no existe.");
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-camaron.png">
    
    <!-- 📱 Para Dispositivos Móviles (iOS y Android) -->
    <!-- Apple (Safari y accesos directos en iPhone/iPad) -->
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/apple-touch-icon-camaron.png">
    
    <!-- Android (Chrome Mobile y pantallas de inicio) -->
    <link rel="icon" type="image/png" sizes="192x192" href="../assets/images/web-app-manifest-192x192-camaron.png">
    <link rel="icon" type="image/png" sizes="512x512" href="../assets/images/web-app-manifest-512x512-camaron.png">
    <title>Plato - EatsTech</title>
    <style>
        body { 
            background-color: var(--bg-main, #f7f2e8); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            margin: 0; 
        }

        .form-container { 
            background-color: var(--bg-card, #efe6d3); 
            padding: 40px; 
            border-radius: 20px; 
            width: 100%; 
            max-width: 450px; 
            border: 1px solid var(--bg-input, #e4d7be); 
            color: var(--text-body, #3b3128); 
            box-shadow: 0 10px 25px rgba(42, 36, 29, 0.08); 
        }

        .form-container h2 { 
            color: var(--text-main, #2a241d); 
            text-align: center; 
            margin-top: 0; 
            margin-bottom: 25px; 
            font-weight: 800;
        }

        .form-group { 
            margin-bottom: 20px; 
        }

        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            color: var(--text-muted, #5a4f44); 
            font-size: 13px; 
            font-weight: bold; 
        }

        .form-group input, 
        .form-group textarea,
        .form-group select { 
            width: 100%; 
            padding: 12px; 
            background: var(--bg-main, #f7f2e8); 
            border: 1px solid var(--bg-input, #e4d7be); 
            color: var(--text-main, #2a241d); 
            border-radius: 8px; 
            box-sizing: border-box; 
            font-size: 14px; 
            transition: all 0.2s ease;
        }

        .form-group input:focus, 
        .form-group textarea:focus,
        .form-group select:focus { 
            border-color: var(--amarillo, #cf9465); 
            background: #ffffff; 
            outline: none; 
            box-shadow: 0 0 8px rgba(207, 148, 101, 0.2);
        }

        .btn-save { 
            background: var(--dark-navbar, #2a241d); 
            color: var(--pure-white, #ffffff); 
            padding: 14px; 
            border: none; 
            width: 100%; 
            font-weight: bold; 
            cursor: pointer; 
            border-radius: 20px; 
            text-transform: uppercase; 
            margin-top: 15px; 
            transition: all 0.2s; 
            box-shadow: 0 4px 12px rgba(42, 36, 29, 0.15);
        }

        .btn-save:hover { 
            background: var(--amarillo, #cf9465); 
            color: var(--pure-white, #ffffff);
            box-shadow: 0 4px 12px rgba(207, 148, 101, 0.3);
        }

        .back-link { 
            display: block; 
            text-align: center; 
            margin-top: 20px; 
            color: var(--text-muted, #5a4f44); 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: bold;
        }

        .back-link:hover { 
            color: var(--amarillo, #cf9465); 
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><?php echo $es_edicion ? '✏️ Editar Plato' : '🍔 Nuevo Plato'; ?></h2>
        <form action="crud_operaciones" method="POST" enctype="multipart/form-data">
            <!-- Tokens y campos de control ocultos -->
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="action" value="<?php echo $es_edicion ? 'update_prod' : 'create_prod'; ?>">
            <input type="hidden" name="restaurante_id" value="<?php echo $id_restaurante_actual; ?>">

            <div class="form-group">
                <label>Nombre del Plato:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required placeholder="Ej. Cazuela Camaron Express">
            </div>

            <div class="form-group">
                <label>Descripción / Ingredientes:</label>
                <textarea name="descripcion" rows="3" placeholder="Ej. Acompañado de patacón y arroz..."><?php echo htmlspecialchars($descripcion); ?></textarea>
            </div>

            <div class="form-group">
                <label for="precio_producto">Precio de Venta ($):</label>
                <input 
                    type="number" 
                    id="precio_producto" 
                    name="precio" 
                    value="<?php echo isset($precio) ? intval($precio) : ''; ?>" 
                    class="form-input" 
                    placeholder="Ej: 15000" 
                    min="1" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="stock_producto">Cantidad en Inventario (Stock):</label>
                <input 
                    type="number" 
                    id="stock_producto" 
                    name="stock" 
                    value="<?php echo isset($stock) ? intval($stock) : 0; ?>" 
                    class="form-input" 
                    min="0" 
                    placeholder="Ej: 50" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="imagen">Foto del Producto (Solo formato WEBP):</label>
                <input type="file" name="imagen" id="imagen" accept="image/webp" style="padding: 8px 0; background: none; border: none;">
            </div>

            <button type="submit" class="btn-save"><?php echo $es_edicion ? 'Actualizar Plato' : 'Crear Plato'; ?></button>
            <a href="admin_dashboard?seccion=productos" class="back-link">← Cancelar y volver</a>
        </form>
        <script src="../assets/js/admin.js"></script>
    </div>
</body>
</html>