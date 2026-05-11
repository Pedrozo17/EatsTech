# EatsTech

EatsTech es una plataforma web de restaurantes que resuelve la falta de visibilidad digital de los negocios gastronómicos en Mosquera, Cundinamarca. Permite a los restaurantes mostrar sus productos, gestionar pedidos y conectar con sus clientes a través de un aplicativo web. Los usuarios pueden explorar restaurantes, navegar por el menu, agregar productos al carrito y realizar pedidos en linea.
---

## Funcionalidades

- Pantalla de carga animada con logo usando GSAP
- Navbar flotante con sesion de usuario activa
- Sistema de autenticacion completo (registro e inicio de sesion)
- Sesiones PHP que persisten el usuario en todas las paginas
- Carrusel de restaurantes con efecto coverflow usando Swiper.js
- Pagina individual por restaurante con menu navegable
- Carrito de compras funcional
- Sistema de pagos y seguimiento de pedidos
- Cierre de sesion con redireccion automatica

---

## Tecnologias utilizadas

| Tecnologia | Uso |
|---|---|
| HTML5 | Estructura de las paginas |
| CSS3 | Estilos y diseno visual |
| PHP | Backend, sesiones y logica de negocio |
| MySQL | Base de datos |
| JavaScript | Interactividad |
| GSAP 3 | Animaciones |
| Swiper.js 9 | Carrusel de restaurantes |
| Font Awesome 6 | Iconos |
| XAMPP | Servidor local (Apache + MySQL) |

---

## Estructura del proyecto

```
Eatstech/
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── style1.css
│   │   ├── estilo2.css
│   │   └── estilo3.css
│   ├── images/
│   └── js/
│       ├── main.js
│       ├── script.js
│       └── script2.js
├── config/
│   ├── con_db.php
│   ├── Configuracion.php
│   └── conx.php
├── database/
│   ├── carrito.sql
│   ├── carrito2.sql
│   ├── carrito3.sql
│   ├── comida.sql
│   ├── pagos.sql
│   ├── registro.sql
│   └── restaurante.sql
├── modules/
│   ├── carrito/
│   │   ├── AccionCarta.php
│   │   └── carritodecompras.php
│   ├── menu/
│   │   ├── La-carta.php
│   │   └── VerCarta.php
│   ├── pagos/
│   │   ├── OrdenExito.php
│   │   ├── Pagos.php
│   │   └── procesar_pago.php
│   └── usuarios/
│       ├── iniciodesesion.php
│       ├── login.php
│       ├── logout.php
│       ├── registrar.php
│       └── reset_password.html
├── pages/
│   ├── index.php
│   ├── casarolla.php
│   ├── formulariodepago.html
│   ├── RealizarPago.html
│   └── tracking.html
├── favicon.svg
├── style-guide.md
└── README.md
```

## Requisitos previos

### Hardware minimo

| Componente | Minimo |
|---|---|
| Procesador | Intel Core i3 o AMD equivalente |
| Memoria RAM | 4 GB |
| Almacenamiento | 500 MB libres |
| Sistema operativo | Windows 10, macOS 10.14, o Linux Ubuntu 20.04 |
| Conexion a internet | Requerida para cargar fuentes y librerias externas |

### Hardware recomendado

| Componente | Recomendado |
|---|---|
| Procesador | Intel Core i5 o superior |
| Memoria RAM | 8 GB o mas |
| Almacenamiento | 1 GB libres |
| Sistema operativo | Windows 11, macOS 12 o Linux Ubuntu 22.04 |

## Instalacion

1. Descarga o clona el repositorio:

   git clone https://github.com/usuario/Eatstech.git

O descarga el ZIP y extrae la carpeta.

2. Copia la carpeta del proyecto dentro de htdocs de XAMPP:

   C:/xampp/htdocs/Eatstech/

## Ejecucion local

1. Abre XAMPP y activa los modulos Apache y MySQL.
2. Importa la base de datos (ver seccion Base de datos).
3. Abre el navegador y entra a:

   http://localhost/Eatstech/pages/index.php

## Base de datos

1. Abre phpMyAdmin en:

   http://localhost/phpmyadmin

2. Importa los archivos SQL en este orden desde la carpeta database/:

registro.sql
restaurante.sql
comida.sql
carrito.sql
carrito2.sql
carrito3.sql
pagos.sql


3. Verifica que la conexion en config/con_db.php coincida con tu configuracion local:

- php   $conex = mysqli_connect("localhost", "root", "", "registro");

## Variables de entorno
- Este proyecto no requiere variables de entorno. La configuracion de la base de datos se maneja directamente en config/con_db.php.

## Usuario de prueba
- El sistema no tiene un usuario de prueba predefinido. Para probar el sistema:

1. Ve a: http://localhost/Eatstech/modules/usuarios/iniciodesesion.php
2. Registrate con cualquier correo y contraseña.
3. sesion con las mismas credenciales.


## Despliegue
- El proyecto esta disenado para correr en un servidor local con XAMPP. Para desplegarlo en produccion se recomienda:

- Hosting con soporte PHP 8 y MySQL (por ejemplo: Hostinger, InfinityFree, o 000webhost)
- Subir los archivos via FTP o panel de control del hosting
- Crear la base de datos en el panel del hosting e importar los archivos SQL
- Actualizar las rutas en config/con_db.php con los datos del servidor de produccion
- Implementar HTTPS para proteger los datos de los usuarios

## Flujo de usuario

1. El usuario entra a index.php y ve la pantalla de carga animada.
2. El navbar muestra los links de navegacion y el boton de login.
3. El usuario puede registrarse o iniciar sesion desde iniciodesesion.php.
4. Tras autenticarse, el navbar muestra su nombre y la opcion de cerrar sesion.
5. Desde el carrusel selecciona un restaurante (por ejemplo Cassarola).
6. Dentro del restaurante puede navegar por el menu en La-carta.php.
7. Agrega productos al carrito desde AccionCarta.php.
8. Revisa su carrito en carritodecompras.php.
9. Procede al pago en Pagos.php y confirma en procesar_pago.php.
10. Recibe confirmacion del pedido en OrdenExito.php.
11. Puede hacer seguimiento del pedido en tracking.html.

---

## Restaurantes disponibles

| Restaurante | Estado |
|---|---|
| Cassarola | Disponible |
| Fogon Antioqueno | Disponible |
| Toskana | Proximamente |

---

## Notas de seguridad

- El proyecto usa consultas SQL directas. Se recomienda implementar mysqli_prepare() antes de subir a produccion para evitar inyeccion SQL.
- Las contrasenas se guardan en texto plano. Se recomienda usar password_hash() y password_verify() en produccion.
- El proyecto esta disenado para correr en localhost con XAMPP.

---

## Autores

Samuel Pedrozo Baena 
Juan David Fernandez Peréz
Cristopher Stuard Romero Monsalve 
Desarrollado como proyecto final de curso.
EatsTech - Plataforma de restaurantes, Mosquera, Cundinamarca.

---

## Licencia

Este proyecto es de uso educativo y no tiene fines comerciales.