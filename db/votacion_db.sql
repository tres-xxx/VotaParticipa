-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-07-2021 a las 17:59:59
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `votacion_db`
--
CREATE DATABASE IF NOT EXISTS `votacion_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `votacion_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `numero` int(11) NOT NULL,
  `texto` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `tipo` text NOT NULL,
  `numero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`tipo`, `numero`) VALUES
('Administrador', 1),
('Votante', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `documento` text NOT NULL,
  `apartamento` text NOT NULL,
  `rol_numero` int(11) DEFAULT NULL,
  `usuario` varchar(25) NOT NULL,
  `pwd` varchar(25) NOT NULL,
  `acceso` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `documento`, `apartamento`, `rol_numero`, `usuario`, `pwd`, `acceso`) VALUES
(1, 'administrador', 'administrador', '11111111', '00', 1, 'admin', 'admin', 'no'),
(12, 'usuario', 'usuario', 'usuario', 'usuario-usuario', 2, 'usuario-usuario', 'USusuario', 'si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votacionopcion`
--

CREATE TABLE `votacionopcion` (
  `opcion` text NOT NULL,
  `votacionNumero` int(11) NOT NULL,
  `votacionOpcion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `votacionopcion`
--

INSERT INTO `votacionopcion` (`opcion`, `votacionNumero`, `votacionOpcion`) VALUES
('Si', 27, 44),
('No', 27, 45);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votacionpregunta`
--

CREATE TABLE `votacionpregunta` (
  `pregunta` text NOT NULL,
  `numero` int(11) NOT NULL,
  `cantidadVotantes` int(11) NOT NULL,
  `activa` varchar(5) NOT NULL,
  `horaCierre` time NOT NULL,
  `tiempoCierre` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `votacionpregunta`
--

INSERT INTO `votacionpregunta` (`pregunta`, `numero`, `cantidadVotantes`, `activa`, `horaCierre`, `tiempoCierre`) VALUES
('Vota por mi', 27, 1, 'No', '00:03:00', '2021-07-05 10:50:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voto`
--

CREATE TABLE `voto` (
  `usuario` int(11) NOT NULL,
  `votacionNumero` int(11) NOT NULL,
  `numeroPregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `voto`
--

INSERT INTO `voto` (`usuario`, `votacionNumero`, `numeroPregunta`) VALUES
(12, 44, 27);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`numero`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_numero` (`rol_numero`);

--
-- Indices de la tabla `votacionopcion`
--
ALTER TABLE `votacionopcion`
  ADD PRIMARY KEY (`votacionOpcion`),
  ADD KEY `votacionNumero` (`votacionNumero`);

--
-- Indices de la tabla `votacionpregunta`
--
ALTER TABLE `votacionpregunta`
  ADD PRIMARY KEY (`numero`);

--
-- Indices de la tabla `voto`
--
ALTER TABLE `voto`
  ADD KEY `usuario` (`usuario`),
  ADD KEY `votacionNumero` (`votacionNumero`),
  ADD KEY `numeroPregunta` (`numeroPregunta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `votacionopcion`
--
ALTER TABLE `votacionopcion`
  MODIFY `votacionOpcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `votacionpregunta`
--
ALTER TABLE `votacionpregunta`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_numero`) REFERENCES `rol` (`numero`);

--
-- Filtros para la tabla `votacionopcion`
--
ALTER TABLE `votacionopcion`
  ADD CONSTRAINT `votacionopcion_ibfk_1` FOREIGN KEY (`votacionNumero`) REFERENCES `votacionpregunta` (`numero`);

--
-- Filtros para la tabla `voto`
--
ALTER TABLE `voto`
  ADD CONSTRAINT `voto_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `voto_ibfk_2` FOREIGN KEY (`votacionNumero`) REFERENCES `votacionopcion` (`votacionOpcion`),
  ADD CONSTRAINT `voto_ibfk_3` FOREIGN KEY (`numeroPregunta`) REFERENCES `votacionpregunta` (`numero`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
