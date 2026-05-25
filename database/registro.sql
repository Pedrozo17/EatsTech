-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2026 a las 20:54:23
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
-- Base de datos: `registro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `id` int(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_reg` varchar(15) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `confirmar_contraseña` varchar(255) NOT NULL,
  `codigo_reset` int(6) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL,
  `tipo` varchar(20) DEFAULT 'persona'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`id`, `nombre`, `correo`, `telefono`, `direccion`, `fecha_reg`, `cedula`, `contraseña`, `confirmar_contraseña`, `codigo_reset`, `codigo_expira`, `tipo`) VALUES
(1, 'pedro cely', '', NULL, NULL, '09/06/24', '', '', '', NULL, NULL, 'persona'),
(2, 'pedro cely', '', NULL, NULL, '09/06/24', '', '', '', NULL, NULL, 'persona'),
(3, 'pedro cely', '', NULL, NULL, '09/06/24', '', '', '', NULL, NULL, 'persona'),
(4, 'pedro cely', '', NULL, NULL, '09/06/24', '', '', '', NULL, NULL, 'persona'),
(5, 'pedro', 'pcelymejia@gmail.com', NULL, NULL, '09/06/24', '', '', '', NULL, NULL, 'persona'),
(6, 'pedro cely', 'pcelymejia@gmail.com', NULL, NULL, '11/06/24', '', '', '', NULL, NULL, 'persona'),
(7, 'pedro cely', 'pcelymejia@gmail.com', NULL, NULL, '11/06/24', '', '', '', NULL, NULL, 'persona'),
(8, 'pedro', 'pedro@dvx', NULL, NULL, '15/07/24', '', '', '', NULL, NULL, 'persona'),
(14, 'Samuel', 'saas@gamil.com', '334344', 'calle 1', '', '23232', '$2y$10$bGLK8m8o5AKiWX6bTqLal.BMTYwk.wLDOrP2x9PzkjxZT99cC7VO2', '', NULL, NULL, 'persona'),
(15, 'ssassa', 'saas@gamil.com', '1212', 'sasa', '', '1221', '$2y$10$6HXh0kc4HiQWQDxHkIs1RuZXf3akKzSzoXRJXXkfb7WwkDiJFPmPe', '', NULL, NULL, 'persona'),
(16, 'Samuel', 'samuelpedrozobaena9@gmail.com', '3142756300', 'calle 1', '', '18273734', '$2y$10$DjhkwT3ATi7SLt7w9G16yO80lDEWFw0Lw7lgH4lcQcgvGT3hxpfEq', '', 115688, '2026-05-23 12:11:29', 'persona'),
(17, 'Samuel', 'samuelpedrozobaena1@gmail.com', '3142756300', 'calle 1', '', '1027402576', '$2y$10$mUTH9u1O2L9DE8Sx9S.P9OswqU1c4bi89iSWOc1vUtdtWMJj3h9QS', '', NULL, NULL, 'empresa'),
(18, 'juan fernandez', 'juan@gmail.com', '3142756300', 'calle 2', '', '1111', '$2y$10$fCQ5yKme47O53f935nafbuNihNI3a3zlAIpxMkDRhltXG2Y0jpPee', '', NULL, NULL, 'empresa'),
(19, 'kikos osas', 'kikoosas@gmail.com', '3142756300', 'calle 3', '', '27328973', '$2y$10$vW2awuu1KhW753OHOjCIGexvehun80XuHauEf1UU30Hyx1nGsd8dO', '', NULL, NULL, 'empresa'),
(20, 'Samuel', 'samuelpedrozobaena2@gmail.com', '3142756300', 'calle 1', '', '1027402576', '$2y$10$rkytYMY.aWewTQdNomkrr.ufq0.iMkhzqVmJ1sDyEo9MS94rKwM3q', '', NULL, NULL, 'empresa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurantes`
--

CREATE TABLE `restaurantes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre_restaurante` varchar(100) NOT NULL,
  `slug_carpeta` varchar(50) NOT NULL,
  `color_principal` varchar(7) DEFAULT '#FFB900'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `restaurantes`
--

INSERT INTO `restaurantes` (`id`, `usuario_id`, `nombre_restaurante`, `slug_carpeta`, `color_principal`) VALUES
(1, 17, 'Camaron Express', 'admin', '#cf9465'),
(2, 18, 'ejemplo', 'admin', '#cf9465'),
(3, 19, 'ejemplo2', 'admin', '#cf9465'),
(4, 20, 'ejemplo3', 'admin', '#cf9465');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `datos`
--
ALTER TABLE `datos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `datos`
--
ALTER TABLE `datos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD CONSTRAINT `restaurantes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `datos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
