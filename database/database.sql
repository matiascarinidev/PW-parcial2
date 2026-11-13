-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql306.infinityfree.com
-- Tiempo de generación: 13-11-2026 a las 15:33:44
-- Versión del servidor: 11.4.13-MariaDB
-- Versión de PHP: 7.2.22
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET
    AUTOCOMMIT = 0;

START TRANSACTION;

SET
    time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_43221022_parcial2`
--
CREATE DATABASE IF NOT EXISTS `Foro_MatiasCarini` DEFAULT CHARACTER
SET
    latin1 COLLATE latin1_swedish_ci;

USE `Foro_MatiasCarini`;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `amistades`
--
CREATE TABLE
    `amistades` (
        `id_amistad` int (11) NOT NULL,
        `usuario_id` int (11) NOT NULL,
        `amigo_id` int (11) NOT NULL,
        `estado` enum ('pendiente', 'aceptada', 'rechazada') NOT NULL DEFAULT 'pendiente',
        `fecha_solicitud` timestamp NULL DEFAULT current_timestamp()
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `amistades`
--
INSERT INTO
    `amistades` (
        `id_amistad`,
        `usuario_id`,
        `amigo_id`,
        `estado`,
        `fecha_solicitud`
    )
VALUES
    (1, 3, 4, 'aceptada', '2026-11-10 17:47:29'),
    (2, 3, 2, 'pendiente', '2026-11-10 17:47:29'),
    (3, 3, 1, 'aceptada', '2026-11-12 20:18:22'),
    (4, 15, 2, 'pendiente', '2026-11-13 20:25:13'),
    (5, 15, 13, 'pendiente', '2026-11-13 20:25:33');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `likes`
--
CREATE TABLE
    `likes` (
        `id_like` int (11) NOT NULL,
        `publicacion_id` int (11) NOT NULL,
        `usuario_id` int (11) NOT NULL
    ) ENGINE = MyISAM DEFAULT CHARSET = latin1 COLLATE = latin1_swedish_ci;

--
-- Volcado de datos para la tabla `likes`
--
INSERT INTO
    `likes` (`id_like`, `publicacion_id`, `usuario_id`)
VALUES
    (1, 7, 1),
    (2, 6, 1),
    (3, 5, 2),
    (7, 10, 15),
    (5, 5, 15),
    (6, 4, 15);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `perfiles`
--
CREATE TABLE
    `perfiles` (
        `id_perfil` int (11) NOT NULL,
        `usuario_id` int (11) NOT NULL,
        `usuario` varchar(50) NOT NULL,
        `nombre` varchar(100) NOT NULL,
        `apellido` varchar(100) NOT NULL,
        `telefono` varchar(20) DEFAULT NULL,
        `biografia` text DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `perfiles`
--
INSERT INTO
    `perfiles` (
        `id_perfil`,
        `usuario_id`,
        `usuario`,
        `nombre`,
        `apellido`,
        `telefono`,
        `biografia`
    )
VALUES
    (
        1,
        1,
        'admin_foro',
        'Admin',
        'Principal',
        '123456789',
        'Administrador del foro.'
    ),
    (
        2,
        2,
        'mod_juan',
        'Juan',
        'Perez',
        '987654321',
        'Moderador de contenido.'
    ),
    (
        3,
        3,
        'pepe',
        'Jose',
        'Gomez',
        '555555555',
        'Usuario regular y amante del foro.'
    ),
    (
        4,
        4,
        'maria',
        'Maria',
        'Lopez',
        '444444444',
        'Nueva en el foro.'
    ),
    (
        5,
        11,
        'Colo Barco',
        'Valentin',
        'Barco',
        '1111111111',
        'Jugador de futbol'
    ),
    (
        6,
        12,
        'El PIBE JANSON',
        'PIBE',
        'JANSON',
        '1123865888',
        'SDDSDS'
    ),
    (
        7,
        13,
        'Ray',
        'Facundo',
        'Romero',
        '',
        'Profe en dv, MR30 en warframe (es mi bio en redes)'
    ),
    (
        8,
        14,
        'testingRegistro',
        'usuario',
        'cinco',
        '1234567890',
        'registro->crear_perfil->perfil->amistades->aÃ±adir_amigos'
    ),
    (
        9,
        15,
        'LuzBeLiTo',
        'Matias',
        'Carini',
        '123456789',
        'registro->crear_perfil->perfil->publicar'
    );

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `publicaciones`
--
CREATE TABLE
    `publicaciones` (
        `id_publicacion` int (11) NOT NULL,
        `autor_id` int (11) NOT NULL,
        `contenido` text NOT NULL,
        `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
        `me_gusta` int (11) DEFAULT 0,
        `estado_visibilidad` enum ('visible', 'oculta') NOT NULL DEFAULT 'visible'
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--
INSERT INTO
    `publicaciones` (
        `id_publicacion`,
        `autor_id`,
        `contenido`,
        `fecha_creacion`,
        `me_gusta`,
        `estado_visibilidad`
    )
VALUES
    (
        1,
        3,
        'Este es mi primer post. Saludos a todos.',
        '2026-11-10 17:47:17',
        0,
        'visible'
    ),
    (
        2,
        4,
        '¿Cómo puedo conectar a MySQLi de forma segura?',
        '2026-11-10 17:47:17',
        0,
        'visible'
    ),
    (
        3,
        3,
        'Aprendiendo mucho en este foro.',
        '2026-11-10 17:47:17',
        0,
        'oculta'
    ),
    (
        4,
        11,
        'Buenas tardes! Me presento soy colo barco quizas alguno me conozca. En mis tiempos libres estoy aprendiendo a programar pero no se como hacer la conexión en infinityfree, alguien me podrá ayudar?',
        '2026-11-10 19:08:15',
        0,
        'visible'
    ),
    (
        5,
        13,
        'Veamos como persiste la información de un user',
        '2026-11-11 18:49:17',
        0,
        'visible'
    ),
    (
        6,
        1,
        'Publicacion1',
        '2026-11-12 20:45:49',
        0,
        'visible'
    ),
    (
        7,
        1,
        'publicacion 2',
        '2026-11-12 20:45:55',
        0,
        'oculta'
    ),
    (
        8,
        2,
        'asdasd',
        '2026-11-13 20:05:21',
        0,
        'visible'
    ),
    (
        9,
        2,
        'asdasd',
        '2026-11-13 20:05:23',
        0,
        'oculta'
    ),
    (
        10,
        15,
        'Testing de publicacion n1',
        '2026-11-13 20:08:17',
        0,
        'visible'
    ),
    (
        11,
        15,
        'Esto mas que un foro parece twitter jejeje',
        '2026-11-13 20:26:17',
        0,
        'visible'
    );

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuarios`
--
CREATE TABLE
    `usuarios` (
        `id` int (11) NOT NULL,
        `email` varchar(100) NOT NULL,
        `password` varchar(255) NOT NULL,
        `rol` enum ('admin', 'moderador', 'usuario') NOT NULL DEFAULT 'usuario',
        `perfil_completo` tinyint (1) NOT NULL DEFAULT 0,
        `baneado` tinyint (1) NOT NULL DEFAULT 0
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--
INSERT INTO
    `usuarios` (
        `id`,
        `email`,
        `password`,
        `rol`,
        `perfil_completo`,
        `baneado`
    )
VALUES
    (
        1,
        'admin@test.com',
        '$2y$10$w0kt9FAz9fjN3EHQAEQmcOcOKWdRNKLOxA1THlb9htP0mNsAFxrL.',
        'admin',
        1,
        0
    ),
    (
        2,
        'user1@test.com',
        '$2y$10$p02HB1ADNCKFI8KixUuXKOHhYDw69K1fb3ZOOO6AqRo0W8MjmsUJK',
        'moderador',
        1,
        0
    ),
    (
        3,
        'user2@test.com',
        '$2y$10$THXq1/s6Tyr7.fbvBT72cuGMt0AbiVQCWuKqizJ8ks8UiNM070bDS',
        'usuario',
        1,
        0
    ),
    (
        4,
        'user3@test.com',
        '$2y$10$Am3f.dkadXqGVEP6jrfxLe1qLiEBdsQpfmOnf3gdV1T7546Umu2bS',
        'usuario',
        1,
        1
    ),
    (
        11,
        'colobarco@gmail',
        '$2y$12$/nSHPqEV/IxbG1E2E3KhJ.wG7gBZv/9ie9wFKKO6Ds0vBwqyC9DPe',
        'usuario',
        1,
        0
    ),
    (
        12,
        'brian.lagorio@davinci.edu.ar',
        '$2y$12$6r3gNePK797P9QII2Owfb.W8KrzJBFc9RsyFTFfWuPJyb29HNMu4i',
        'usuario',
        1,
        0
    ),
    (
        13,
        'facundo.iromero@davinci.edu.ar',
        '$2y$12$iC6xslxf1EFfh6wJ7l5rqe.Bs46ekEmn1JJ40fGMzlubME.1Embw2',
        'usuario',
        1,
        0
    ),
    (
        14,
        'user5@test.com',
        '$2y$12$Kb1ktIjVi7H0qfZe/APFjuf.pjIHquiHyl8DESXzIJd3fOqOnxiT6',
        'usuario',
        1,
        0
    ),
    (
        15,
        'mati@test.com',
        '$2y$12$qQSPebAfE3mr546LwnNehOygrYTqPW9pET68zKroN1bcwWSe.O2F.',
        'usuario',
        1,
        0
    );

--
-- Índices para tablas volcadas
--
--
-- Indices de la tabla `amistades`
--
ALTER TABLE `amistades` ADD PRIMARY KEY (`id_amistad`),
ADD UNIQUE KEY `usuario_id` (`usuario_id`, `amigo_id`),
ADD KEY `amigo_id` (`amigo_id`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes` ADD PRIMARY KEY (`id_like`),
ADD UNIQUE KEY `publicacion_id` (`publicacion_id`, `usuario_id`),
ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles` ADD PRIMARY KEY (`id_perfil`),
ADD UNIQUE KEY `usuario_id` (`usuario_id`),
ADD UNIQUE KEY `apodo` (`usuario`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones` ADD PRIMARY KEY (`id_publicacion`),
ADD KEY `autor_id` (`autor_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--
--
-- AUTO_INCREMENT de la tabla `amistades`
--
ALTER TABLE `amistades` MODIFY `id_amistad` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 6;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes` MODIFY `id_like` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 8;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles` MODIFY `id_perfil` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 10;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones` MODIFY `id_publicacion` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 16;

--
-- Restricciones para tablas volcadas
--
--
-- Filtros para la tabla `amistades`
--
ALTER TABLE `amistades` ADD CONSTRAINT `amistades_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `amistades_ibfk_2` FOREIGN KEY (`amigo_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `perfiles`
--
ALTER TABLE `perfiles` ADD CONSTRAINT `perfiles_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones` ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;