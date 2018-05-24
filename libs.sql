-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-05-2018 a las 22:24:35
-- Versión del servidor: 10.1.16-MariaDB
-- Versión de PHP: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Estructura de tabla para la tabla `job`
--

CREATE TABLE `job` (
  `job_id` int(11) NOT NULL,
  `job_description` text COLLATE utf8_bin NOT NULL,
  `job_salary` double(15,2) NOT NULL,
  `job_created_at` date NOT NULL,
  `job_updated_at` date NOT NULL,
  `job_name` varchar(200) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `job`
--

INSERT INTO `job` (`job_id`, `job_description`, `job_salary`, `job_created_at`, `job_updated_at`, `job_name`) VALUES
(1, '', 21000.00, '2018-05-24', '0000-00-00', 'PHP programmer'),
(3, '', 30000.00, '2018-05-24', '0000-00-00', 'Programador JAVA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `person_id` int(11) NOT NULL,
  `person_name` varchar(200) COLLATE utf8_bin NOT NULL,
  `person_surname` varchar(200) COLLATE utf8_bin NOT NULL,
  `person_birthdate` date NOT NULL,
  `person_created_at` date NOT NULL,
  `person_updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`person_id`, `person_name`, `person_surname`, `person_birthdate`, `person_created_at`, `person_updated_at`) VALUES
(1, 'Gabriel', 'Macus', '1996-06-16', '2018-05-24', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `_relations`
--

CREATE TABLE `_relations` (
  `relation_id` int(11) NOT NULL,
  `relation_parent_id` int(11) DEFAULT NULL,
  `relation_child_id` int(11) DEFAULT NULL,
  `relation_parent_table` varchar(200) DEFAULT NULL,
  `relation_child_table` varchar(200) DEFAULT NULL,
  `relation_parent_key` varchar(200) DEFAULT NULL COMMENT 'Parent key in child',
  `relation_child_key` varchar(200) DEFAULT NULL COMMENT 'Child key in parent',
  `relation_parent_position` int(11) DEFAULT NULL COMMENT 'Parent position in child',
  `relation_child_position` int(11) DEFAULT NULL COMMENT 'Child position in parent',
  `relation_created_at` datetime DEFAULT NULL,
  `relation_updated_at` datetime DEFAULT NULL,
  `relation_extra_1` varchar(200) DEFAULT NULL,
  `relation_extra_2` varchar(200) DEFAULT NULL,
  `relation_extra_3` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `_relations`
--

INSERT INTO `_relations` (`relation_id`, `relation_parent_id`, `relation_child_id`, `relation_parent_table`, `relation_child_table`, `relation_parent_key`, `relation_child_key`, `relation_parent_position`, `relation_child_position`, `relation_created_at`, `relation_updated_at`, `relation_extra_1`, `relation_extra_2`, `relation_extra_3`) VALUES
(1, 1, 1, 'person', 'job', '', '', 0, 0, '2018-05-24 00:00:00', '0000-00-00 00:00:00', '', '', 0),
(2, 1, 3, 'person', 'job', '', '', 0, 0, '2018-05-24 00:00:00', '0000-00-00 00:00:00', '', '', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`job_id`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`person_id`);

--
-- Indices de la tabla `_relations`
--
ALTER TABLE `_relations`
  ADD PRIMARY KEY (`relation_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `job`
--
ALTER TABLE `job`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `_relations`
--
ALTER TABLE `_relations`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
