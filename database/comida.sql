-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-09-2024 a las 02:30:32
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
-- Base de datos: `comida`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comiditadeli`
--

CREATE TABLE `comiditadeli` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `personas` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `mensaje` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comiditadeli`
--

INSERT INTO `comiditadeli` (`id`, `nombre`, `celular`, `personas`, `fecha`, `hora`, `mensaje`) VALUES
(0, 'luisa', '30254', 1, '2024-12-10', '08:00:00', 'jhkjkj'),
(0, 'maria', '30025489789', 1, '5024-02-10', '08:00:00', 'khjkhj'),
(0, 'maria', '321548', 5, '0000-00-00', '08:00:00', 'kjljkljk'),
(0, '', '', 1, '0000-00-00', '08:00:00', ''),
(0, '', '', 1, '0000-00-00', '08:00:00', ''),
(0, '', '', 1, '0000-00-00', '08:00:00', ''),
(0, '', '', 1, '0000-00-00', '08:00:00', ''),
(0, '', '', 1, '0000-00-00', '08:00:00', ''),
(0, '', '', 1, '0000-00-00', '08:00:00', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
