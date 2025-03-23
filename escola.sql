-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-03-2025 a las 16:25:38
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
-- Base de datos: `escola`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grades`
--

CREATE TABLE `grades` (
  `user_id` int(11) NOT NULL,
  `punto1` float DEFAULT 0,
  `punto2` float DEFAULT 0,
  `id` int(11) NOT NULL,
  `modificaciones` int(11) DEFAULT 0,
  `materia1` varchar(50) DEFAULT NULL,
  `materia2` varchar(50) DEFAULT NULL,
  `historial` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grades`
--

INSERT INTO `grades` (`user_id`, `punto1`, `punto2`, `id`, `modificaciones`, `materia1`, `materia2`, `historial`) VALUES
(24220077, 0, 0, 0, 0, '0', '0', 'Punto1: 5, Punto2: 0, Materia1: GEOMETRIA, Materia2:  (Reiniciado el 2025-02-05 08:23:59) | Punto1: 0, Punto2: 0, Materia1: 0, Materia2: 0 (Reiniciado el 2025-02-05 08:25:01) | Punto1: 4, Punto2: 3, Materia1: MATEMATICAS, Materia2: GEOGRAFIA (Reiniciado el 2025-02-05 08:26:21)'),
(24220076, 0, 0, 0, 0, '0', '0', 'Punto1: 6, Punto2: 6, Materia1: MATEMATICAS, Materia2: GEOGRAFIA (Reiniciado el 2025-02-05 08:23:59) | Punto1: 0, Punto2: 0, Materia1: 0, Materia2: 0 (Reiniciado el 2025-02-05 08:25:01) | Punto1: 0, Punto2: 0, Materia1: 0, Materia2: 0 (Reiniciado el 2025-02-05 08:26:21)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('administrador','profesor','alumno','instructor') NOT NULL,
  `Taller` varchar(25) NOT NULL,
  `Turno` varchar(50) NOT NULL,
  `Carrera` varchar(50) NOT NULL,
  `Semestre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `Taller`, `Turno`, `Carrera`, `Semestre`) VALUES
(1, 'admin', 'admin', 'administrador', '', '', '', ''),
(24220076, 'HUGO ALVARADO', 'asd1', 'instructor', 'Musica', 'Completo', 'Global', '7mo semestre'),
(24220077, 'asd', 'asd', 'alumno', 'Musica', 'Matutino', 'Agrícola', '6to semestre'),
(24220079, 'VICTOR ROLDAN', 'asd', 'profesor', 'Profesor', 'Completo', 'Global', 'Sin semestre');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `grades`
--
ALTER TABLE `grades`
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
