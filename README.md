
# EatsTech

EatsTech es una plataforma web de restaurantes que resuelve la falta de visibilidad digital de los negocios gastronómicos en Mosquera, Cundinamarca. Permite a los restaurantes mostrar sus productos, gestionar pedidos y conectar con sus clientes a través de un aplicativo web. Los usuarios pueden explorar restaurantes, navegar por el menú, agregar productos al carrito y realizar pedidos en línea; mientras que los administradores de los negocios cuentan con un panel de control avanzado para la gestión de su inventario y el monitoreo de órdenes en tiempo real.

---

## Integrantes / Autores
- **Samuel Pedrozo Baena**
- **Juan David Fernández Pérez**
- **Cristopher Stuard Romero Monsalve**

*Proyecto de desarrollo de software - Orientado a la gestión e integración de servicios gastronómicos locales.*

---

## Funcionalidades

### 👤 Área del Cliente
- **Experiencia Fluida:** Pantalla de carga animada con logo usando GSAP 3.
- **Navegación Dinámica:** Navbar flotante con sesión de usuario activa y carrusel de restaurantes con efecto coverflow usando Swiper.js.
- **Autenticación Completa:** Sistema de registro e inicio de sesión con persistencia mediante sesiones PHP nativas.
- **Módulo de Compras:** Carrito de compras funcional, sistema de pagos integrado y confirmación automatizada de la orden.
- **Seguimiento:** Módulo de tracking para verificar el progreso del pedido.

### 💼 Panel Administrativo (`admin_dashboard.php`)
- **Control de Acceso:** Restricción estricta por roles de usuario corporativos (`$_SESSION['tipo'] === 'empresa'`).
- **CRUD de Productos:** Interfaz para crear, actualizar y eliminar platos del menú de manera dinámica sobre la tabla `mis_productos`.
- **Monitoreo de Órdenes en Tiempo Real:** Panel de control asíncrono que procesa las tablas `orden` y `pedidos_registrados`.
- **Actualización en Caliente (AJAX Fetch):** Selectores visuales interactivos que modifican los estados de preparación y pago de los pedidos instantáneamente sin necesidad de recargar la página.

---

## Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| **HTML5 & CSS3** | Estructura semántica, diseño responsive y estilos visuales personalizados. |
| **PHP 8** | Backend modular, gestión de sesiones, validaciones de seguridad y lógica del CRUD. |
| **MySQL** | Base de datos relacional para persistencia de usuarios, platos y órdenes. |
| **JavaScript (ES6+)** | Consumo de endpoints asíncronos mediante `Fetch API` y manipulación del DOM en tiempo real. |
| **GSAP 3** | Animaciones de carga y transiciones de interfaz. |
| **Swiper.js 9** | Componente del carrusel interactivo de restaurantes. |
| **Font Awesome 6** | Paquete de iconografía del sitio. |
| **XAMPP** | Entorno de desarrollo local (Servidor Apache y servidor de Base de Datos MySQL). |

---

## Requisitos previos

### Hardware mínimo
- **Procesador:** Intel Core i3 o AMD equivalente
- **Memoria RAM:** 4 GB
- **Almacenamiento:** 500 MB libres
- **Sistema Operativo:** Windows 10, macOS 10.14, o Linux Ubuntu 20.04
- **Conexión a internet:** Requerida para la carga de CDNs externos (Fuentes y librerías)

### Hardware recomendado
- **Procesador:** Intel Core i5 o superior
- **Memoria RAM:** 8 GB o más
- **Almacenamiento:** 1 GB libre
- **Sistema Operativo:** Windows 11, macOS 12 o Linux Ubuntu 22.04

---

## Instalación

1. **Descargar o clona el repositorio:**
   ```bash
   git clone [https://github.com/usuario/Eatstech.git](https://github.com/usuario/Eatstech.git)

```

*O extrae el archivo ZIP directamente en tu ordenador.*

2. **Montar en el servidor local:**
Mueve o copia la carpeta completa del proyecto dentro del directorio de despliegue local de XAMPP:
```html
C:/xampp/htdocs/Eatstech/

```



---

## Ejecución local

1. Abre el panel de control de XAMPP y activa los módulos de **Apache** y **MySQL**.
2. Importa la base de datos (ver sección correspondiente abajo).
3. Abre tu navegador web de preferencia e ingresa a la siguiente URL:
```html
http://localhost/Eatstech/pages/index.php

```



---

## Base de datos

1. Dirígete al gestor de bases de datos web en `http://localhost/phpmyadmin`.
2. Crea una base de datos llamada `registro` (o el nombre que prefieras para tu entorno).
3. Selecciona la base de datos e importa los archivos estructurados que se encuentran dentro de la carpeta `database/` en el siguiente **orden estricto**:
1. `registro.sql`
2. `restaurante.sql`
3. `comida.sql`
4. `carrito.sql`
5. `carrito2.sql`
6. `carrito3.sql`
7. `pagos.sql`



### Estructura de Control de Estados actualizados:

Ambas tablas críticas de control operativo manejan un formato de texto unificado para evitar colapsos en las peticiones AJAX:

* **`pedidos_registrados`**: Cuenta con la columna `estado` (`VARCHAR(50)`) para registrar de forma legible el progreso de entrega.
* **`orden`**: Cuenta con la columna `status` (`VARCHAR(50)`) configurada con el valor por defecto `'En Espera'`.

---

## Variables de entorno y Configuración

El proyecto no requiere archivos `.env` externos. La configuración de la conexión global a la base de datos se centraliza en el archivo:

* Ruta del archivo: `config/configuracion.php`

### Código base de conexión local:

```php
<?php
// Configuración de conexión orientada a objetos ($db)
$db = new mysqli("localhost", "root", "", "registro");

if ($db->connect_error) {
    die("Error de conexión: " . $db->connect_error);
}
?>

```

---

## Usuario de prueba

El sistema no cuenta con un usuario quemado en código por motivos de seguridad, permitiendo el auto-registro libre. Para realizar pruebas del flujo completo:

1. Dirígete a la pestaña de inicio de sesión: `http://localhost/Eatstech/modules/usuarios/iniciodesesion.php`.
2. Haz clic en "Regístrate", rellena los datos de tu nueva cuenta y haz clic en crear.
3. Inicia sesión con las credenciales que acabas de registrar.
4. **Nota para Rol Administrador:** Si deseas acceder al panel administrativo en `admin/admin_dashboard.php`, ingresa a phpMyAdmin, busca el usuario registrado en la tabla correspondientes y cambia el campo `tipo` de `'cliente'` a `'empresa'`.

---

## Despliegue y Datos a cambiar en Producción

El proyecto está diseñado para trabajar inicialmente en un entorno local con XAMPP. Para migrar el aplicativo a un entorno de producción (Hosting real como Hostinger, InfinityFree, etc.), se deben realizar las siguientes modificaciones:

### 1. Actualización de Credenciales en el Archivo de Conexión:

Se debe editar el archivo `config/configuracion.php` cambiando los parámetros locales por los suministrados por tu proveedor de hosting:

```php
// Cambiar en entorno de producción:
$db = new mysqli("HOST_DEL_HOSTING", "USUARIO_BD_HOSTING", "CONTRASEÑA_BD", "NOMBRE_BD_HOSTING");

```

### 2. Parámetros de Servidor Recomendados:

* Servidor web con soporte para **PHP 8.0** o superior.
* Motor de bases de datos **MySQL 5.7** o **MariaDB 10.4**.
* Configuración de certificado **HTTPS** activo para proteger el envío de datos en formularios.

---

## Evidencias del sistema funcionando

### 📱 Vista del Cliente (Carrito y Menú)

Interfaz optimizada donde los usuarios seleccionan los restaurantes en Mosquera, navegan por la carta y consolidan sus productos directamente en el carrito de compras.

### 💼 Vista del Administrador (Monitoreo de Órdenes)

Panel de control centralizado (`admin_dashboard.php`) con sistema de pestañas funcionales para interactuar dinámicamente con el inventario de platos y modificar los estados de preparación o pago asíncronamente mediante peticiones controladas.

---

## Licencia

Este proyecto es de carácter estrictamente educativo y de código abierto para la comunidad estudiantil. No posee fines comerciales.

```

```