-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2018 a las 05:40:59
-- Versión del servidor: 10.1.26-MariaDB
-- Versión de PHP: 7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `libs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facebookuser`
--

CREATE TABLE `facebookuser` (
  `facebookuser_id` int(11) NOT NULL,
  `facebookuser_img` varchar(300) COLLATE utf8_bin NOT NULL,
  `facebookuser_url` varchar(300) COLLATE utf8_bin NOT NULL,
  `facebookuser_name` varchar(300) COLLATE utf8_bin NOT NULL,
  `facebookuser_location_work` varchar(300) COLLATE utf8_bin NOT NULL,
  `facebookuser_created_at` datetime NOT NULL,
  `facebookuser_updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `facebookuser`
--

INSERT INTO `facebookuser` (`facebookuser_id`, `facebookuser_img`, `facebookuser_url`, `facebookuser_name`, `facebookuser_location_work`, `facebookuser_created_at`, `facebookuser_updated_at`) VALUES
(25, '', '', '', '', '2018-06-14 05:35:14', '0000-00-00 00:00:00'),
(24, '', '', '', '', '2018-06-14 05:35:14', '0000-00-00 00:00:00'),
(23, '', '', '', '', '2018-06-14 05:35:14', '0000-00-00 00:00:00'),
(22, '', '', '', '', '2018-06-14 05:35:14', '0000-00-00 00:00:00'),
(21, '', '', '', '', '2018-06-14 05:31:26', '0000-00-00 00:00:00'),
(20, '', '', '', '', '2018-06-14 05:31:26', '0000-00-00 00:00:00'),
(19, '', '', '', '', '2018-06-14 05:31:26', '0000-00-00 00:00:00'),
(18, '', '', '', '', '2018-06-14 05:31:26', '0000-00-00 00:00:00'),
(16, '', '', '', '', '2018-06-14 05:31:26', '0000-00-00 00:00:00'),
(17, '', '', '', '', '2018-06-14 05:31:26', '0000-00-00 00:00:00'),
(26, '', '', '', '', '2018-06-14 05:35:14', '0000-00-00 00:00:00'),
(27, '', '', '', '', '2018-06-14 05:35:14', '0000-00-00 00:00:00'),
(28, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(29, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(30, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(31, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(32, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(33, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(34, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(35, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(36, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(37, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(38, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(39, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(40, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00'),
(41, '', '', '', '', '2018-06-14 05:38:28', '0000-00-00 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `facebookuser`
--
ALTER TABLE `facebookuser`
  ADD PRIMARY KEY (`facebookuser_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `facebookuser`
--
ALTER TABLE `facebookuser`
  MODIFY `facebookuser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
