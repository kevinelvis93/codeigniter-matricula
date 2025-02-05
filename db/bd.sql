-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-02-2025 a las 07:01:22
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistemaoperaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_correos`
--

CREATE TABLE `ccp_correos` (
  `correo_id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ccp_correos`
--

INSERT INTO `ccp_correos` (`correo_id`, `persona_id`, `correo_electronico`) VALUES
(1, 1, 'melomedinakevin@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_personas`
--

CREATE TABLE `ccp_personas` (
  `persona_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ccp_personas`
--

INSERT INTO `ccp_personas` (`persona_id`, `usuario_id`, `nombres`, `apellidos`, `dni`, `direccion`, `fecha_nacimiento`) VALUES
(1, 1, 'Kevin Elvis', 'Melo Medina', '47923146', 'Av. Principal 123', '1993-10-02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_roles`
--

CREATE TABLE `ccp_roles` (
  `rol_id` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ccp_roles`
--

INSERT INTO `ccp_roles` (`rol_id`, `nombre_rol`) VALUES
(1, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_telefonos`
--

CREATE TABLE `ccp_telefonos` (
  `telefono_id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `numero_telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ccp_telefonos`
--

INSERT INTO `ccp_telefonos` (`telefono_id`, `persona_id`, `numero_telefono`) VALUES
(1, 1, '984310226');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_usuarios`
--

CREATE TABLE `ccp_usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo','baneado') DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ccp_usuarios`
--

INSERT INTO `ccp_usuarios` (`usuario_id`, `nombre_usuario`, `contrasena_hash`, `estado`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'kmelom', '$2y$10$EjwlgkVQohRtAlsk87B9wO9f1m2ey1B5jifWPU77kB.TqSmiLKZC2', 'activo', '2024-09-18 21:56:17', '2024-09-18 21:56:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_usuario_rol`
--

CREATE TABLE `ccp_usuario_rol` (
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ccp_usuario_rol`
--

INSERT INTO `ccp_usuario_rol` (`usuario_id`, `rol_id`) VALUES
(1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ccp_correos`
--
ALTER TABLE `ccp_correos`
  ADD PRIMARY KEY (`correo_id`);

--
-- Indices de la tabla `ccp_personas`
--
ALTER TABLE `ccp_personas`
  ADD PRIMARY KEY (`persona_id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `ccp_roles`
--
ALTER TABLE `ccp_roles`
  ADD PRIMARY KEY (`rol_id`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `ccp_telefonos`
--
ALTER TABLE `ccp_telefonos`
  ADD PRIMARY KEY (`telefono_id`);

--
-- Indices de la tabla `ccp_usuarios`
--
ALTER TABLE `ccp_usuarios`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Indices de la tabla `ccp_usuario_rol`
--
ALTER TABLE `ccp_usuario_rol`
  ADD PRIMARY KEY (`usuario_id`,`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ccp_correos`
--
ALTER TABLE `ccp_correos`
  MODIFY `correo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ccp_personas`
--
ALTER TABLE `ccp_personas`
  MODIFY `persona_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ccp_roles`
--
ALTER TABLE `ccp_roles`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ccp_telefonos`
--
ALTER TABLE `ccp_telefonos`
  MODIFY `telefono_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ccp_usuarios`
--
ALTER TABLE `ccp_usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
