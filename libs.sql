-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2018 a las 07:30:23
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
-- Estructura de tabla para la tabla `league`
--

CREATE TABLE `league` (
  `league_name` varchar(200) COLLATE utf8_bin NOT NULL,
  `league_created_at` datetime NOT NULL,
  `league_updated_at` datetime NOT NULL,
  `league_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `league`
--

INSERT INTO `league` (`league_name`, `league_created_at`, `league_updated_at`, `league_id`) VALUES
('Premier League', '2018-05-28 04:40:33', '0000-00-00 00:00:00', 1),
('La Liga', '2018-05-28 04:40:33', '0000-00-00 00:00:00', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `person_id` int(11) NOT NULL,
  `person_name` varchar(200) COLLATE utf8_bin NOT NULL,
  `person_surname` varchar(200) COLLATE utf8_bin NOT NULL,
  `person_birthdate` date NOT NULL,
  `person_created_at` datetime NOT NULL,
  `person_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`person_id`, `person_name`, `person_surname`, `person_birthdate`, `person_created_at`, `person_updated_at`) VALUES
(1, 'Mohamed', 'Salah', '0000-00-00', '2018-05-28 04:40:33', '0000-00-00 00:00:00'),
(2, 'Loris', 'Karius', '0000-00-00', '2018-05-28 04:40:33', '0000-00-00 00:00:00'),
(3, 'Willy', 'Caballero', '0000-00-00', '2018-05-28 04:40:34', '0000-00-00 00:00:00'),
(4, 'Eden', 'Hazard', '0000-00-00', '2018-05-28 04:40:34', '0000-00-00 00:00:00'),
(5, 'Alex', 'Bruce', '0000-00-00', '2018-05-28 04:40:34', '0000-00-00 00:00:00'),
(6, 'Nick', 'Powell', '0000-00-00', '2018-05-28 04:40:34', '0000-00-00 00:00:00'),
(7, 'Lionel', 'Messi', '0000-00-00', '2018-05-28 04:40:34', '0000-00-00 00:00:00'),
(8, 'Luis', 'SuÃ¡rez', '0000-00-00', '2018-05-28 04:40:34', '0000-00-00 00:00:00'),
(9, 'Gareth', 'Bale', '0000-00-00', '2018-05-28 04:40:34', '0000-00-00 00:00:00'),
(10, 'Sergio', 'Ramos', '0000-00-00', '2018-05-28 04:40:34', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team`
--

CREATE TABLE `team` (
  `team_name` varchar(200) COLLATE utf8_bin NOT NULL,
  `team_created_at` datetime NOT NULL,
  `team_updated_at` datetime NOT NULL,
  `team_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `team`
--

INSERT INTO `team` (`team_name`, `team_created_at`, `team_updated_at`, `team_id`) VALUES
('Liverpool F.C', '2018-05-28 04:40:33', '0000-00-00 00:00:00', 1),
('Chelsea F.C', '2018-05-28 04:40:33', '0000-00-00 00:00:00', 2),
('Wigan Athletic', '2018-05-28 04:40:33', '0000-00-00 00:00:00', 3),
('F.C Barcelona', '2018-05-28 04:40:33', '0000-00-00 00:00:00', 4),
('Real Madrid F.C', '2018-05-28 04:40:33', '0000-00-00 00:00:00', 5);

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
(1, 1, 1, 'league', 'team', '', 'teams', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(2, 1, 2, 'league', 'team', '', 'teams', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(3, 1, 3, 'league', 'team', '', 'teams', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(4, 2, 4, 'league', 'team', '', 'teams', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(5, 2, 5, 'league', 'team', '', 'teams', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(6, 1, 1, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(7, 1, 2, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(8, 2, 3, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(9, 2, 4, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(10, 3, 5, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(11, 3, 6, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(12, 4, 7, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(13, 4, 8, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(14, 5, 9, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0),
(15, 5, 10, 'team', 'person', '', 'players', 0, 0, '2018-05-28 04:40:34', '0000-00-00 00:00:00', '', '', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `league`
--
ALTER TABLE `league`
  ADD PRIMARY KEY (`league_id`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`person_id`);

--
-- Indices de la tabla `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`);

--
-- Indices de la tabla `_relations`
--
ALTER TABLE `_relations`
  ADD PRIMARY KEY (`relation_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `league`
--
ALTER TABLE `league`
  MODIFY `league_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `_relations`
--
ALTER TABLE `_relations`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
