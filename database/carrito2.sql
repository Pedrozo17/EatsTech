-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-09-2024 a las 20:34:34
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
-- Base de datos: `carrito2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `name`, `email`, `phone`, `address`, `created`, `modified`, `status`) VALUES
(1, 'Samuel Pedrozo', 'samuelpedrozobaena9@gmail.com', '3142756300', 'Mosquera', '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mis_productos`
--

CREATE TABLE `mis_productos` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` float(10,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `mis_productos`
--

INSERT INTO `mis_productos` (`id`, `name`, `description`, `price`, `created`, `modified`, `status`) VALUES
(1, 'Asadito Toskana', 'Deliciosa morcilla acompañada de chorizo, longaniza de la casa, papas criollas, guacamole y arepa de queso.', 25000.00, '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(2, 'Sandwich de Res a la Parrilla\r\n', 'Con Carne de res asada a la parrilla, queso fundido y chimichurri.', 28000.00, '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(3, 'Crema de Camarones\r\n', 'Exquisitos camarones bañados en vino blanco acompañados de una mezcla de crema marinera.', 16500.00, '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(4, 'Chicharrones Crocantes\r\n', 'Deliciosa panceta de cerdo acompañada de papas criollas, ají o chimichurri de la casa.', 28000.00, '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(5, 'Chinchulines', 'Delicioso chunchullo a la parrilla con toque de hierbas acompañado de papitas criollas.', 28000.00, '2024-08-03 00:12:28', '2024-08-03 00:12:28', '1'),
(6, 'Canoas de Papa', 'Disfruta de nuestras deliciosas canoas de papa rellenas de carne desmechada acompañadas de nuestro único hogao de la casa y guacamole.', 24000.00, '2024-08-03 00:17:31', '2024-08-03 00:17:31', '1'),
(7, 'Hamburguesa Clásica', 'Fino pan de papa con carne premium a la parrilla, acompañado de la salsa de la casa\r\n', 24000.00, '2024-09-14 00:45:11', '2024-09-14 00:45:11', '1'),
(8, 'Hamburguesa Porck BBQ', 'Carne premium de 200 gramos, con queso blanco, costilla de cerdo desmechada y salsa de la casa', 30000.00, '2024-09-14 00:45:11', '2024-09-14 00:45:11', '1'),
(9, 'Papas Crysper', 'Sobrebarriga desmechada con: plátanos maduros en cubos, aguacate, guacamole y salsa de la casa', 22000.00, '2024-09-14 00:47:09', '2024-09-14 00:47:09', '1'),
(10, 'Chuleta Valluna', '300 gramos de carne de cerdo apanada con panco en deliciosa salsa tártara\r\n', 32000.00, '2024-09-14 00:47:09', '2024-09-14 00:47:09', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden`
--

CREATE TABLE `orden` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_price` float(10,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `orden`
--

INSERT INTO `orden` (`id`, `customer_id`, `total_price`, `created`, `modified`, `status`) VALUES
(8, 1, 71500.00, '2024-08-02 17:09:58', '2024-08-02 17:09:58', '1'),
(9, 1, 9000.00, '2024-08-02 17:22:39', '2024-08-02 17:22:39', '1'),
(10, 1, 5500.00, '2024-08-04 15:00:56', '2024-08-04 15:00:57', '1'),
(11, 1, 5500.00, '2024-09-13 16:27:52', '2024-09-13 16:27:52', '1'),
(12, 1, 43000.00, '2024-09-13 17:28:56', '2024-09-13 17:28:56', '1'),
(13, 1, 43900.00, '2024-09-13 18:09:46', '2024-09-13 18:09:46', '1'),
(14, 1, 43900.00, '2024-09-14 10:36:23', '2024-09-14 10:36:23', '1');

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
(13, 14, 9, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mis_productos`
--
ALTER TABLE `mis_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `orden`
--
ALTER TABLE `orden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `orden`
--
ALTER TABLE `orden`
  ADD CONSTRAINT `orden_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  ADD CONSTRAINT `orden_articulos_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orden` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
