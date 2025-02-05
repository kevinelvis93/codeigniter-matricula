-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/ Holaaaaaaaaa
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-11-2024 a las 08:31:41
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
-- Base de datos: `sistema_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_email`
--

CREATE TABLE `ccp_email` (
  `id` int(10) UNSIGNED NOT NULL,
  `persona_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_spanish_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_email`
--

INSERT INTO `ccp_email` (`id`, `persona_id`, `email`, `estado`) VALUES
(1, 1, 'melomedinakevin@gmail.com', 1),
(2, 2, 'jose@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_log`
--

CREATE TABLE `ccp_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `accion` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `modulo` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_log`
--

INSERT INTO `ccp_log` (`id`, `usuario_id`, `accion`, `modulo`, `fecha`) VALUES
(1, 1, 'Asignó el rol de Administrador a sí mismo', 'roles', '2024-11-06 22:30:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_menu`
--

CREATE TABLE `ccp_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `ruta` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `padre_id` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `icono` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `orden` int(10) UNSIGNED NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_menu`
--

INSERT INTO `ccp_menu` (`id`, `nombre`, `ruta`, `padre_id`, `icono`, `orden`, `estado`) VALUES
(1, 'Dashboard', '/plantilla', '0', 'fa-dashboard', 1, 1),
(2, 'Persona', '', '0', 'fa-dashboard', 2, 1),
(3, 'Colaborador', '/colaborador', '2', 'fa-dashboard', 1, 1),
(4, 'Alumno', '/alumno', '2', 'fa-dashboard', 2, 1),
(5, 'Interesado', '/interesado', '2', 'fa-dashboard', 3, 1),
(6, 'Academico', '/academico', '0', 'fa-dashboard', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_persona`
--

CREATE TABLE `ccp_persona` (
  `id` int(10) UNSIGNED NOT NULL,
  `dni` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellido_paterno` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellido_materno` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_persona`
--

INSERT INTO `ccp_persona` (`id`, `dni`, `nombres`, `apellido_paterno`, `apellido_materno`, `direccion`, `estado`) VALUES
(1, '47923146', 'Kevin Elvis', 'Melo', 'Medina', NULL, 1),
(2, '47923145', 'Jose Luis', 'Melo', 'Medina', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_rol`
--

CREATE TABLE `ccp_rol` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_rol`
--

INSERT INTO `ccp_rol` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Acceso total al sistema'),
(2, 'Comercial', 'Acceso a los modulos comerciales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_rol_permiso`
--

CREATE TABLE `ccp_rol_permiso` (
  `id` int(10) UNSIGNED NOT NULL,
  `rol_id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED NOT NULL,
  `crear` int(10) UNSIGNED NOT NULL,
  `editar` int(10) UNSIGNED NOT NULL,
  `eliminar` int(10) UNSIGNED NOT NULL,
  `detalle` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_rol_permiso`
--

INSERT INTO `ccp_rol_permiso` (`id`, `rol_id`, `menu_id`, `crear`, `editar`, `eliminar`, `detalle`) VALUES
(1, 1, 1, 0, 0, 0, 0),
(2, 1, 2, 0, 0, 0, 0),
(3, 1, 3, 1, 1, 1, 1),
(4, 1, 4, 1, 1, 1, 1),
(5, 1, 5, 1, 1, 1, 1),
(6, 1, 6, 1, 1, 1, 1),
(7, 2, 1, 0, 0, 0, 0),
(8, 2, 6, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_telefono`
--

CREATE TABLE `ccp_telefono` (
  `id` int(10) UNSIGNED NOT NULL,
  `persona_id` int(10) UNSIGNED NOT NULL,
  `numero` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_usuario`
--

CREATE TABLE `ccp_usuario` (
  `id` int(10) UNSIGNED NOT NULL,
  `persona_id` int(10) UNSIGNED NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_usuario`
--

INSERT INTO `ccp_usuario` (`id`, `persona_id`, `usuario`, `password`, `estado`) VALUES
(1, 1, 'kmelom', '$2y$10$inhuRp7e3tKoXChdb7Ekhen9RF8bvKAFw5NLpO2iFQuBqW7WxLClu', 1),
(2, 2, 'jmelom', '$2y$10$inhuRp7e3tKoXChdb7Ekhen9RF8bvKAFw5NLpO2iFQuBqW7WxLClu', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_usuario_rol`
--

CREATE TABLE `ccp_usuario_rol` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `rol_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_usuario_rol`
--

INSERT INTO `ccp_usuario_rol` (`id`, `usuario_id`, `rol_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ccp_email`
--
ALTER TABLE `ccp_email`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `ccp_log`
--
ALTER TABLE `ccp_log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ccp_menu`
--
ALTER TABLE `ccp_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ccp_persona`
--
ALTER TABLE `ccp_persona`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `ccp_rol`
--
ALTER TABLE `ccp_rol`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `ccp_rol_permiso`
--
ALTER TABLE `ccp_rol_permiso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ccp_telefono`
--
ALTER TABLE `ccp_telefono`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ccp_usuario`
--
ALTER TABLE `ccp_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ccp_usuario_rol`
--
ALTER TABLE `ccp_usuario_rol`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ccp_email`
--
ALTER TABLE `ccp_email`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ccp_log`
--
ALTER TABLE `ccp_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ccp_menu`
--
ALTER TABLE `ccp_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ccp_persona`
--
ALTER TABLE `ccp_persona`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ccp_rol`
--
ALTER TABLE `ccp_rol`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ccp_rol_permiso`
--
ALTER TABLE `ccp_rol_permiso`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ccp_telefono`
--
ALTER TABLE `ccp_telefono`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ccp_usuario`
--
ALTER TABLE `ccp_usuario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ccp_usuario_rol`
--
ALTER TABLE `ccp_usuario_rol`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
