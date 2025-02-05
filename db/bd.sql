-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-02-2025 a las 07:23:14
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
(2, 2, 'jose@gmail.com', 1),
(5, 5, 'mario@gmail.com', 1),
(6, 5, 'mario1@gmail.com', 1),
(7, 6, 'pedrocorreo@gmail.com', 1),
(8, 7, 'lcarlos@gmail.com', 1),
(9, 8, 'amelgarejo@gmail.com', 1),
(10, 12, 'fdurand@gmail.com', 1),
(12, 15, 'fdurand1@gmail.com', 1),
(13, 16, 'fdurand255@gmail.com', 1),
(14, 17, 'fdurand2@gmail.com', 1),
(15, 18, 'fdurand123@gmail.com', 1),
(16, 19, 'jmaguino@gmail.com', 1),
(17, 20, 'mariomelo001@gmail.com', 1),
(18, 20, 'mariomelo002@gmail.com', 1);

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
  `nombres` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellido_paterno` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellido_materno` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ccp_persona`
--

INSERT INTO `ccp_persona` (`id`, `nombres`, `apellido_paterno`, `apellido_materno`, `direccion`, `estado`) VALUES
(1, 'Kevin Elvis', 'Melo', 'Medina', NULL, 1),
(2, 'Jose Luis', 'Melo', 'Medina', NULL, 1),
(5, 'Mario', 'Melo', 'Melo 2', 'El Agustino', 1),
(6, 'Pedro', 'Matilde', 'Campos', 'Miraflores', 1),
(7, 'leonel carlos', 'Melo', 'sfsdfbd', '', 1),
(8, 'Abel', 'Melgarejo', 'Vargas', 'Pueblo Libre', 1),
(12, 'FELIPE', 'DURAND', 'GONZALES', '', 1),
(15, 'FELIPE', 'DURAND', 'GONZALES', '', 1),
(16, 'FELIPE', 'DURAND', 'GONZALES', 'El Agustino', 1),
(17, 'MARIO', 'MELO', 'MELO 2', '', 1),
(18, 'MARIANO', 'SANTOS', 'GONZALES', 'El Agustino', 1),
(19, 'JUAN CARLOS', 'MAGUIÑO', 'TECHÉRA', '', 1),
(20, 'MARIO', 'MELOSDFVSDV', 'SDVSDVSDVS', 'Miraflores', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_persona_identificacion`
--

CREATE TABLE `ccp_persona_identificacion` (
  `id_persona_identificacion` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL,
  `id_tipo_identificacion` int(11) DEFAULT NULL,
  `identificacion_descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ccp_persona_identificacion`
--

INSERT INTO `ccp_persona_identificacion` (`id_persona_identificacion`, `id_persona`, `id_tipo_identificacion`, `identificacion_descripcion`) VALUES
(1, 1, 1, '47923146'),
(3, 12, 1, '44444444'),
(5, 15, 1, '55555555'),
(6, 16, 1, '33333333'),
(7, 17, 1, '22222222'),
(8, 18, 1, '77777777'),
(9, 19, 1, '11111111'),
(10, 20, 1, '65852145');

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

--
-- Volcado de datos para la tabla `ccp_telefono`
--

INSERT INTO `ccp_telefono` (`id`, `persona_id`, `numero`, `estado`) VALUES
(1, 1, '984310226', 1),
(2, 2, '999999999', 1),
(3, 5, '985654123', 1),
(4, 5, '9856541231', 1),
(5, 6, '985632541', 1),
(6, 12, '999999998', 1),
(7, 12, '999999997', 1),
(8, 15, '999999998', 1),
(9, 16, '999999998', 1),
(10, 18, '999999998', 1),
(11, 19, '985632545', 1),
(12, 20, '999999998', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ccp_tipo_identificacion`
--

CREATE TABLE `ccp_tipo_identificacion` (
  `id_tipo_identificacion` int(11) NOT NULL,
  `identificacion_nombre` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ccp_tipo_identificacion`
--

INSERT INTO `ccp_tipo_identificacion` (`id_tipo_identificacion`, `identificacion_nombre`) VALUES
(1, 'DNI'),
(2, 'Carnet de Extranjería');

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
(2, 2, 'jmelom', '$2y$10$inhuRp7e3tKoXChdb7Ekhen9RF8bvKAFw5NLpO2iFQuBqW7WxLClu', 1),
(4, 5, 'mmelom', '$2y$10$yalH4C2p8U.EWlwpvswcBeUSnOPSdHKh4Mdv.J9XZh90J.a/upjC2', 1),
(5, 6, 'pmatildec', '$2y$10$NbQ2EdOhU/q3Rbh3U9CQyu0HCqfAqtto7bXOngl5Ol3FgXQUL2b0i', 1),
(6, 7, 'lcarlos', '$2y$10$Gtjrs/BzLZWxLYeVw9r6Y.bEgHMULFAORT0iMfQuAtkbj2Lvg1/E.', 1),
(7, 8, 'amelgarejov', '$2y$10$JT8LYTkI1Gbh1EvB9xXZKeNozViSuX0mEiqu0r6r6gt.4PvEZhvRi', 1),
(11, 12, 'fdurandg', '$2y$10$nA0q0xwxvsrzkcwMDRWcg.TQSwbmt4qVAsl1bcdwxMtFHAk89Z1/y', 1),
(14, 15, 'feldurandg', '$2y$10$srxNefOPnvKM2UxyiS5Cq.L2gr1TrhrKFpMueeOYAcxoY0NtgNbU6', 1),
(15, 16, 'feldurandg', '$2y$10$axtmDCsgotq/bZQ9d4jp6uVh843YeHR4zhfcc62MTGIkHPKEGH8su', 1),
(16, 17, 'marmelom', '$2y$10$HaSCbY/cwiBAs/ba8CzDoeAOaQGb3NdOh7ZdZf4SZqEGX/NchNevO', 1),
(17, 18, 'marsantosg', '$2y$10$MsGeV7PgUlQn/gizhi8KROIbSbAY05AmGBPJSATHY6AhjvY3DTHEu', 1),
(18, 19, 'juamaguinot', '$2y$10$3lOyPy.nb9o0KF7bZnZbhutU2oPfrLylc9ZJekGUqCNWzgBnf1jvm', 1),
(19, 20, 'marmelosdfvsdvs', '$2y$10$/3.QV9fno2YJXBwpGInMouxi37/fhrK5QMTR/JoWba7YSO2vkU.JG', 1);

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
(3, 2, 2),
(5, 4, 2),
(6, 5, 2),
(7, 6, 2),
(8, 7, 2),
(9, 11, 1),
(10, 11, 2),
(12, 14, 2),
(13, 15, 2),
(14, 16, 1),
(15, 17, 2),
(16, 18, 2),
(17, 19, 2);

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
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ccp_persona_identificacion`
--
ALTER TABLE `ccp_persona_identificacion`
  ADD PRIMARY KEY (`id_persona_identificacion`),
  ADD UNIQUE KEY `identificacion_descripcion_UNIQUE` (`identificacion_descripcion`);

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
-- Indices de la tabla `ccp_tipo_identificacion`
--
ALTER TABLE `ccp_tipo_identificacion`
  ADD PRIMARY KEY (`id_tipo_identificacion`);

--
-- Indices de la tabla `ccp_usuario`
--
ALTER TABLE `ccp_usuario`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `ccp_persona_identificacion`
--
ALTER TABLE `ccp_persona_identificacion`
  MODIFY `id_persona_identificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `ccp_tipo_identificacion`
--
ALTER TABLE `ccp_tipo_identificacion`
  MODIFY `id_tipo_identificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ccp_usuario`
--
ALTER TABLE `ccp_usuario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `ccp_usuario_rol`
--
ALTER TABLE `ccp_usuario_rol`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
