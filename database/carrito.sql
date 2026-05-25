-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2026 a las 20:53:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `carrito`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mis_productos`
--

CREATE TABLE `mis_productos` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` float(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT 'default.png',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `mis_productos`
--

INSERT INTO `mis_productos` (`id`, `name`, `description`, `price`, `imagen`, `created`, `modified`, `status`) VALUES
(1, 'Cazuela de mariscos', 'cazuela de mariscos con 220gr de mixtura acompañada de tostadas y aguacate', 35000.00, 'cazuela de mariscos.png', '2024-08-02 08:21:25', '2026-05-16 08:21:25', '1'),
(2, 'Camaclitos', 'un bowl con una cama de lechuga, pico de gallo, aguacate y choclitos', 22000.00, 'Camaclitos.png', '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(3, 'Sánguche de camaron', 'Sándwich preparado con camarones frescos, aguacate, queso, tomate y lechuga, acompañado de nuestra deliciosa salsa de la casa.\r\n', 20000.00, 'Sánguche de camaron.png', '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(4, 'Cardumen de Camarón 12oz', 'Vaso de 12 oz con camarones, aguacate y mango fresco, acompañado de nuestra salsa de la casa y galletas Saltín.\r\n', 26000.00, 'Cardumen de Camarón 12oz.png', '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(5, 'Camaritos', 'un bowl con una cama de lechuga, repleto de Doritos, pico de gallo, camarones y aguacate', 24000.00, 'Camarones — Los Camarítos.png', '2024-08-03 00:12:28', '2024-08-03 00:12:28', '1'),
(6, 'Coctel intermedio', 'Vaso de 9 oz con camarones, mango o aguacate a tu elección, acompañado de nuestra salsa de la casa y galletas Saltín.\r\n', 20000.00, 'coctel intermedio.png', '2024-08-03 00:17:31', '2024-08-03 00:17:31', '1'),
(7, 'Coctel tradicional 7oz', 'Vaso de 7 oz con camarones, acompañado de nuestra salsa de la casa y galletas Saltín.\r\n\r\n', 15000.00, 'Coctel tradicional 7oz.png', '2024-09-14 00:45:11', '2024-09-14 00:45:11', '1'),
(8, 'Camarones adicionales', 'Porción adicional de camarones (+50 g) para agregar más sabor a tu pedido.\r\n\r\n', 8000.00, 'camarones adicionales.png', '2024-09-14 00:45:11', '2024-09-14 00:45:11', '1'),
(9, 'Galletas Saltin adicionales', 'Porción adicional de galletas Saltín para acompañar tu pedido.\r\n\r\n', 2500.00, 'galletas adicionales.png', '2024-09-14 00:47:09', '2024-09-14 00:47:09', '1'),
(10, 'Doritos adicionales', 'Porción adicional de Doritos para darle un toque más crujiente a tu pedido.\r\n\r\n', 3000.00, 'doritos adicionales.png', '2024-09-14 00:47:09', '2024-09-14 00:47:09', '1'),
(11, 'Choclitos adicionales', 'Porción adicional de Choclitos para acompañar y darle más sabor a tu pedido.\r\n', 2500.00, 'choclitos adicionales.png', '2026-05-16 21:40:01', '2026-05-16 21:40:01', '1'),
(13, 'Adición de Aguacate', 'Porción adicional de aguacate fresco para complementar tu pedido.\r\n', 3000.00, 'aguacate adicional.png', '2026-05-16 21:40:57', '2026-05-16 21:40:57', '1'),
(14, 'Fuztea de negro durazno', 'Fuze Tea sabor durazno, una bebida refrescante ideal para acompañar tu pedido.\r\n', 4500.00, 'fuz tea durazno.png', '2026-05-16 21:41:17', '2026-05-16 21:41:17', '1'),
(15, 'Fuztea te negro limon', 'Fuze Tea té negro sabor limón, una bebida refrescante perfecta para acompañar tu pedido.\r\n', 4500.00, 'fuz tea limon.png', '2026-05-16 21:42:37', '2026-05-16 21:42:37', '1'),
(16, 'Gaseosa 7up', '7UP, bebida refrescante con sabor cítrico ideal para acompañar tu pedido.\r\n', 4000.00, '7up.png', '2026-05-16 21:43:11', '2026-05-16 21:43:11', '1'),
(17, 'Gaseosa manzana', 'Manzana Postobón, bebida refrescante con sabor a manzana perfecta para acompañar tu pedido.\r\n', 4000.00, 'manzana postobon.png', '2026-05-16 21:44:26', '2026-05-16 21:44:26', '1'),
(18, 'Gaseosa colombiana', 'Colombiana, bebida gaseosa tradicional con sabor único ideal para acompañar tu pedido.\r\n', 4000.00, 'colombiana.png', '2026-05-16 21:44:50', '2026-05-16 21:44:50', '1'),
(19, 'Gaseosa Coca-Cola', 'Coca-Cola, bebida refrescante clásica para acompañar tu pedido.\r\n', 3000.00, 'cocacola.png', '2026-05-16 21:45:35', '2026-05-16 21:45:35', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden`
--

CREATE TABLE `orden` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_price` float(10,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'En Espera',
  `metodo_pago` varchar(50) DEFAULT 'efectivo',
  `nombre_cliente` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `correo_cliente` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `orden`
--

INSERT INTO `orden` (`id`, `customer_id`, `total_price`, `created`, `modified`, `status`, `metodo_pago`, `nombre_cliente`, `telefono`, `direccion`, `correo_cliente`) VALUES
(8, 1, 71500.00, '2024-08-02 17:09:58', '2024-08-02 17:09:58', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(9, 1, 9000.00, '2024-08-02 17:22:39', '2024-08-02 17:22:39', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(10, 1, 5500.00, '2024-08-04 15:00:56', '2024-08-04 15:00:57', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(11, 1, 5500.00, '2024-09-13 16:27:52', '2024-09-13 16:27:52', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(12, 1, 43000.00, '2024-09-13 17:28:56', '2024-09-13 17:28:56', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(13, 1, 43900.00, '2024-09-13 18:09:46', '2024-09-13 18:09:46', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(14, 1, 43900.00, '2026-03-27 08:08:45', '2026-03-27 08:08:45', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(15, 1, 307300.00, '2026-04-30 15:49:12', '2026-04-30 15:49:12', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(16, 1, 43900.00, '2026-04-30 15:57:51', '2026-04-30 15:57:51', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(17, 1, 85800.00, '2026-04-30 15:58:42', '2026-04-30 15:58:42', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(18, 1, 43900.00, '2026-04-30 16:16:08', '2026-04-30 16:16:08', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(19, 1, 43900.00, '2026-04-30 20:23:57', '2026-04-30 20:23:57', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(20, 1, 43900.00, '2026-05-01 07:42:41', '2026-05-01 07:42:41', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(21, 1, 41900.00, '2026-05-01 07:46:47', '2026-05-01 07:46:47', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(22, 1, 43900.00, '2026-05-01 08:22:40', '2026-05-01 08:22:40', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(23, 1, 43900.00, '2026-05-15 07:41:48', '2026-05-15 07:41:48', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(24, 1, 171600.00, '2026-05-16 14:13:17', '2026-05-16 14:13:17', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(25, 1, 22000.00, '2026-05-17 09:41:59', '2026-05-17 09:41:59', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(26, 1, 35000.00, '2026-05-18 12:43:34', '2026-05-18 12:43:34', 'Pendiente', 'efectivo', NULL, NULL, NULL, NULL),
(39, 14, 22000.00, '2026-05-21 13:37:53', '2026-05-21 13:37:53', 'Pendiente', 'nequi', 'Samuel', '334344', 'calle 1', 'saas@gamil.com'),
(40, 14, 20000.00, '2026-05-21 13:39:01', '2026-05-21 13:39:01', 'Pendiente', 'nequi', 'Samuel', '334344', 'calle 1', 'saas@gamil.com'),
(41, 16, 24000.00, '2026-05-21 16:10:41', '2026-05-21 16:10:41', 'Pendiente', 'daviplata', 'Samuel', '3142756300', 'calle 1', 'samuelpedrozobaena9@gmail.com'),
(42, 16, 92000.00, '2026-05-21 18:27:56', '2026-05-21 18:27:56', 'En Cocina', 'bancolombia', 'Samuel', '3142756300', 'calle 1', 'samuelpedrozobaena9@gmail.com'),
(43, 16, 22000.00, '2026-05-21 21:08:34', '2026-05-21 21:08:34', 'Cancelado', 'daviplata', 'Samuel', '3142756300', 'calle 1', 'samuelpedrozobaena9@gmail.com'),
(44, 17, 44000.00, '2026-05-22 15:40:54', '2026-05-22 15:40:54', 'Pagado', 'nequi', 'Samuel', '3142756300', 'calle 1', 'samuelpedrozobaena1@gmail.com'),
(45, 16, 26500.00, '2026-05-23 11:58:58', '2026-05-23 11:58:58', 'Pagado', 'nequi', 'Samuel', '3142756300', 'calle 1', 'samuelpedrozobaena9@gmail.com'),
(46, 16, 20000.00, '2026-05-25 13:21:10', '2026-05-25 13:21:10', 'En Espera', 'daviplata', 'Samuel', '3142756300', 'calle 1', 'samuelpedrozobaena9@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_articulos`
--

CREATE TABLE `orden_articulos` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `orden_articulos`
--

INSERT INTO `orden_articulos` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(4, 8, 1, 1),
(5, 8, 3, 1),
(6, 8, 4, 1),
(7, 9, 5, 1),
(8, 9, 6, 1),
(9, 10, 6, 1),
(10, 11, 6, 1),
(11, 12, 4, 2),
(12, 13, 9, 1),
(13, 14, 10, 1),
(14, 15, 9, 1),
(15, 15, 10, 6),
(16, 16, 10, 1),
(17, 17, 8, 1),
(18, 17, 10, 1),
(19, 18, 9, 1),
(20, 19, 9, 1),
(21, 20, 10, 1),
(22, 21, 8, 1),
(23, 22, 10, 1),
(24, 23, 10, 1),
(25, 24, 7, 1),
(26, 24, 8, 1),
(27, 24, 9, 2),
(28, 25, 2, 1),
(29, 26, 1, 1),
(43, 39, 2, 1),
(44, 40, 3, 1),
(45, 41, 5, 1),
(46, 42, 1, 2),
(47, 42, 2, 1),
(48, 43, 2, 1),
(49, 44, 2, 2),
(50, 45, 2, 1),
(51, 45, 15, 1),
(52, 46, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `numero_tarjeta` varchar(20) DEFAULT NULL,
  `fecha_expiracion` varchar(5) DEFAULT NULL,
  `codigo_seguridad` varchar(4) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_pedido`, `nombre`, `email`, `cantidad`, `numero_tarjeta`, `fecha_expiracion`, `codigo_seguridad`, `metodo_pago`) VALUES
(1, 1, 'samuel pedrozo', 'SADASD@gmail.com', 12212.00, '1222122332', '1222', '122', 'tarjeta'),
(2, 1, 'samuel pedrozo', 'SADASD@gmail.com', 1111.00, '122121', '2123', '233', 'tarjeta'),
(3, 1, 'samuel pedrozo', 'SADASD@gmail.com', 1111.00, '122121', '2123', '233', 'tarjeta'),
(4, 1, 'samuel pedrozo', 'SADASD@gmail.com', 1111.00, '122121', '2123', '233', 'tarjeta'),
(5, 1, 'samuel pedrozo', 'samuelpedrozobaena9@gmail.com', 121212.00, '1212', '1211', '111', 'tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_registrados`
--

CREATE TABLE `pedidos_registrados` (
  `id` int(11) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `correo_cliente` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` text NOT NULL,
  `resumen_productos` text NOT NULL,
  `total_pagar` decimal(10,2) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` varchar(50) DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_registrados`
--

INSERT INTO `pedidos_registrados` (`id`, `nombre_cliente`, `correo_cliente`, `telefono`, `direccion`, `resumen_productos`, `total_pagar`, `fecha_registro`, `estado`) VALUES
(1, 'Samuel', 'samuelpedrozobaena9@gmail.com', '12328328', 'calle 1', '• Sánguche de camaron x1 — $20.000 COP\n', 20000.00, '2026-05-20 11:56:47', 'Pendiente'),
(2, 'Samuel', 'samuelpedrozobaena9@gmail.com', '12328328', 'calle 1', '• Camaclitos x1 — $22.000 COP\n• Sánguche de camaron x1 — $20.000 COP\n', 42000.00, '2026-05-20 13:58:06', 'Pendiente'),
(3, 'Samuel', 'samuelpedrozobaena9@gmail.com', '12328328', 'calle 1', '• Coctel intermedio x1 — $20.000 COP\n', 20000.00, '2026-05-20 14:01:19', 'Pendiente'),
(4, 'Samuel', 'samuelpedrozobaena9@gmail.com', '12328328', 'calle 1', '• Camaclitos x1 — $22.000 COP\n', 22000.00, '2026-05-20 14:02:51', 'Pendiente'),
(5, 'Samuel', 'saas@gamil.com', '334344', 'calle 1', '• Camaclitos x1 — $22.000 COP\n', 22000.00, '2026-05-21 13:37:53', 'Pendiente'),
(6, 'Samuel', 'saas@gamil.com', '334344', 'calle 1', '• Sánguche de camaron x1 — $20.000 COP\n', 20000.00, '2026-05-21 13:39:01', 'Pendiente'),
(7, 'Samuel', 'samuelpedrozobaena9@gmail.com', '3142756300', 'calle 1', '• Camaritos x1 — $24.000 COP\n', 24000.00, '2026-05-21 16:10:41', 'Pendiente'),
(8, 'Samuel', 'samuelpedrozobaena9@gmail.com', '3142756300', 'calle 1', '• Cazuela de mariscos x2 — $70.000 COP\n• Camaclitos x1 — $22.000 COP\n', 92000.00, '2026-05-21 18:27:56', 'Pendiente'),
(9, 'Samuel', 'samuelpedrozobaena9@gmail.com', '3142756300', 'calle 1', '• Camaclitos x1 — $22.000 COP\n', 22000.00, '2026-05-21 21:08:34', 'Cancelado'),
(10, 'Samuel', 'samuelpedrozobaena1@gmail.com', '3142756300', 'calle 1', '• Camaclitos x2 — $44.000 COP\n', 44000.00, '2026-05-22 15:40:54', 'En Camino'),
(11, 'Samuel', 'samuelpedrozobaena9@gmail.com', '3142756300', 'calle 1', '• Camaclitos x1 — $22.000 COP\n• Fuztea te negro limon x1 — $4.500 COP\n', 26500.00, '2026-05-23 11:58:58', 'En Cocina'),
(12, 'Samuel', 'samuelpedrozobaena9@gmail.com', '3142756300', 'calle 1', '• Sánguche de camaron x1 — $20.000 COP\n', 20000.00, '2026-05-25 13:21:10', 'Pendiente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mis_productos`
--
ALTER TABLE `mis_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden`
--
ALTER TABLE `orden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indices de la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indices de la tabla `pedidos_registrados`
--
ALTER TABLE `pedidos_registrados`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mis_productos`
--
ALTER TABLE `mis_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `orden`
--
ALTER TABLE `orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pedidos_registrados`
--
ALTER TABLE `pedidos_registrados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `orden`
--
ALTER TABLE `orden`
  ADD CONSTRAINT `fk_orden_datos` FOREIGN KEY (`customer_id`) REFERENCES `registro`.`datos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  ADD CONSTRAINT `orden_articulos_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orden` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
