-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-09-2024 a las 20:33:37
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
(1, 'Spaghetti Marinera', 'Espagueti Marinera (pescado,\r\ncamarón y calamar) en salsa roja\r\ny blanca', 53500.00, '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(2, 'Spaghetti Pesto', 'Espagueti en salsa pesto (salsa\r\nde albahaca, queso y aceite de\r\noliva)\r\n', 41900.00, '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(3, 'Spaghetti Con pollo', 'Espagueti en salsa bechamel\r\nCassarola y pollo\r\n', 41900.00, '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(4, 'Spaghetti a La Boloñesa', 'Espagueti boloñesa (salsa de\r\ntomates frescos, hierbas y carne\r\nde res)', 41900.00, '2024-08-02 08:21:25', '2024-08-02 08:21:25', '1'),
(5, 'Jugo De Maracuyá', 'Te invitamos a probar nuestro jugo de maracuyá el cual es un tradicional jugo, muy refrescante, nutritivo y económico. Se consume mayormente en temporada veraniega', 3500.00, '2024-08-03 00:12:28', '2024-08-03 00:12:28', '1'),
(6, 'Postre Tres Leches De Chocolate', 'Delicioso postre tres leches personal el cual tiene los tres tipos de leche: leche condensada, leche liquida y crema de leche, contamos con el sabor de chocolate', 5500.00, '2024-08-03 00:17:31', '2024-08-03 00:17:31', '1'),
(7, 'Fetuccine Carbonara', 'Fetuccinne en salsa bechamel Cassarola y\r\ntocineta\r\n', 41900.00, '2024-09-14 00:45:11', '2024-09-14 00:45:11', '1'),
(8, 'Fetuccine Carbonara', 'Fetuccinne en salsa bechamel Cassarola y\r\ntocineta\r\n', 41900.00, '2024-09-14 00:45:11', '2024-09-14 00:45:11', '1'),
(9, 'Fettuccine de carnes y vegetales', 'pollo y carne de res con brócoli, apio,calabacín,\r\ncebolla y pimentón en una exquisita\r\nsalsa con hierbas y vino blanco\r\n', 43900.00, '2024-09-14 00:47:09', '2024-09-14 00:47:09', '1'),
(10, 'Fettuccine de carnes y vegetales', 'pollo y carne de res con brócoli, apio,calabacín,\r\ncebolla y pimentón en una exquisita\r\nsalsa con hierbas y vino blanco\r\n', 43900.00, '2024-09-14 00:47:09', '2024-09-14 00:47:09', '1');

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
(13, 1, 43900.00, '2024-09-13 18:09:46', '2024-09-13 18:09:46', '1');

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
(12, 13, 9, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `orden_articulos`
--
ALTER TABLE `orden_articulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
