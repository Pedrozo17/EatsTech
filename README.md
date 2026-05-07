# EatsTech

EatsTech es una plataforma web de restaurantes que permite a los usuarios explorar, navegar por el menu, seleccionar productos y realizar pedidos en linea. Desarrollado con PHP, MySQL y JavaScript para restaurantes en Mosquera, Cundinamarca.

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

---

## Instalacion y configuracion

### Requisitos previos

- XAMPP instalado (Apache + MySQL + PHP)
- Navegador web moderno

### Pasos

1. Clona o descarga el proyecto en la carpeta htdocs de XAMPP:
   ```
   C:/xampp/htdocs/Eatstech/
   ```

2. Inicia XAMPP y activa los modulos Apache y MySQL.

3. Importa las bases de datos en phpMyAdmin (localhost/phpmyadmin). Importa cada archivo en orden desde la carpeta database/:
   - registro.sql
   - restaurante.sql
   - comida.sql
   - carrito.sql
   - carrito2.sql
   - carrito3.sql
   - pagos.sql

4. Abre el proyecto en el navegador:
   ```
   http://localhost/Eatstech/pages/index.php
   ```

---

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

## Autor

Desarrollado como proyecto final de curso.
EatsTech - Plataforma de restaurantes, Mosquera, Cundinamarca.

---

## Licencia

Este proyecto es de uso educativo y no tiene fines comerciales.