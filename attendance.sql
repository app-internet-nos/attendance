-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para attendance
CREATE DATABASE IF NOT EXISTS `attendance` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `attendance`;

-- Volcando estructura para tabla attendance.asignaturas
CREATE TABLE IF NOT EXISTS `asignaturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nombre` (`nombre`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla attendance.asignaturas: ~0 rows (aproximadamente)
REPLACE INTO `asignaturas` (`id`, `nombre`, `descripcion`) VALUES
	(1, 'Ofimática', 'Unidad didáctica de Empleabilidad');

-- Volcando estructura para tabla attendance.ciclos
CREATE TABLE IF NOT EXISTS `ciclos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla attendance.ciclos: ~6 rows (aproximadamente)
REPLACE INTO `ciclos` (`id`, `nombre`, `descripcion`) VALUES
	(1, 'I', 'Primer ciclo (abril-julio)'),
	(2, 'II', 'Segundo ciclo (agosto-diciembre)'),
	(3, 'III', 'Tercer ciclo (abril-julio)'),
	(4, 'IV', 'Cuarto ciclo (agosto-diciembre)'),
	(5, 'V', 'Quito ciclo (abril-julio)'),
	(6, 'VI', 'Sexto ciclo (agosto-diciembre)');

-- Volcando estructura para tabla attendance.clases
CREATE TABLE IF NOT EXISTS `clases` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_asignatura` int DEFAULT NULL,
  `id_docente` int DEFAULT NULL,
  `id_seccion` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id_asignatura` (`id_asignatura`) USING BTREE,
  KEY `id_seccion` (`id_seccion`) USING BTREE,
  KEY `id_docente` (`id_docente`) USING BTREE,
  CONSTRAINT `clases_ibfk_1` FOREIGN KEY (`id_asignatura`) REFERENCES `asignaturas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `clases_ibfk_3` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_clases_usuarios` FOREIGN KEY (`id_docente`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla attendance.clases: ~2 rows (aproximadamente)
REPLACE INTO `clases` (`id`, `id_asignatura`, `id_docente`, `id_seccion`) VALUES
	(1, 1, 63, 1),
	(2, 1, 63, 2);

-- Volcando estructura para tabla attendance.estudiantes_clases
CREATE TABLE IF NOT EXISTS `estudiantes_clases` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_clase` int NOT NULL,
  `id_estudiante` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `id_estudiante` (`id_estudiante`,`id_clase`) USING BTREE,
  KEY `id_clase` (`id_clase`) USING BTREE,
  CONSTRAINT `estudiantes_clases_ibfk_2` FOREIGN KEY (`id_clase`) REFERENCES `clases` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_estudiantes_clases_usuarios` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla attendance.estudiantes_clases: ~64 rows (aproximadamente)
REPLACE INTO `estudiantes_clases` (`id`, `id_clase`, `id_estudiante`) VALUES
	(76, 1, 2),
	(77, 1, 3),
	(78, 1, 4),
	(79, 1, 5),
	(80, 1, 6),
	(81, 1, 7),
	(82, 1, 8),
	(83, 1, 9),
	(84, 1, 10),
	(85, 1, 11),
	(86, 1, 12),
	(87, 1, 13),
	(88, 1, 14),
	(89, 1, 15),
	(90, 1, 16),
	(91, 1, 17),
	(92, 1, 18),
	(93, 1, 19),
	(94, 1, 20),
	(95, 1, 21),
	(96, 1, 22),
	(97, 1, 23),
	(98, 1, 24),
	(99, 1, 25),
	(100, 1, 26),
	(102, 1, 27),
	(27, 2, 28),
	(28, 2, 29),
	(29, 2, 30),
	(30, 2, 31),
	(31, 2, 32),
	(32, 2, 33),
	(33, 2, 34),
	(34, 2, 35),
	(35, 2, 36),
	(36, 2, 37),
	(37, 2, 38),
	(38, 2, 39),
	(39, 2, 40),
	(40, 2, 41),
	(41, 2, 42),
	(42, 2, 43),
	(43, 2, 44),
	(44, 2, 45),
	(45, 2, 46),
	(46, 2, 47),
	(47, 2, 48),
	(48, 2, 49),
	(49, 2, 50),
	(50, 2, 51),
	(51, 2, 52),
	(52, 2, 53),
	(53, 2, 54),
	(54, 2, 55),
	(55, 2, 56),
	(56, 2, 57),
	(57, 2, 58),
	(58, 2, 59),
	(59, 2, 60),
	(60, 2, 61),
	(61, 2, 62),
	(103, 1, 64),
	(104, 1, 65),
	(105, 2, 66);

-- Volcando estructura para tabla attendance.marcados
CREATE TABLE IF NOT EXISTS `marcados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dni` char(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `tipo` enum('entrada','salida') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_estudiantes_clases` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id_estudiantes_clases` (`id_estudiantes_clases`) USING BTREE,
  CONSTRAINT `FK_marcados_estudiantes_clases` FOREIGN KEY (`id_estudiantes_clases`) REFERENCES `estudiantes_clases` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla attendance.marcados: ~144 rows (aproximadamente)
REPLACE INTO `marcados` (`id`, `dni`, `fecha_hora`, `tipo`, `id_estudiantes_clases`) VALUES
	(2, '75990170', '2024-08-23 07:10:00', 'entrada', 77),
	(3, '71872845', '2024-08-23 07:10:00', 'entrada', 78),
	(4, '77339393', '2024-08-23 07:10:00', 'entrada', 79),
	(5, '62471857', '2024-08-23 07:10:00', 'entrada', 80),
	(6, '75745991', '2024-08-23 07:10:00', 'entrada', 81),
	(7, '60508851', '2024-08-23 07:10:00', 'entrada', 82),
	(8, '61035596', '2024-08-23 07:10:00', 'entrada', 83),
	(9, '60821445', '2024-08-23 07:10:00', 'entrada', 84),
	(10, '73067928', '2024-08-23 07:10:00', 'entrada', 85),
	(11, '73777355', '2024-08-23 07:10:00', 'entrada', 86),
	(12, '60412261', '2024-08-23 07:10:00', 'entrada', 87),
	(13, '60929859', '2024-08-23 07:10:00', 'entrada', 88),
	(14, '75742367', '2024-08-23 07:10:00', 'entrada', 89),
	(15, '74361094', '2024-08-23 07:10:00', 'entrada', 90),
	(16, '61743129', '2024-08-23 07:10:00', 'entrada', 91),
	(17, '75900029', '2024-08-23 07:10:00', 'entrada', 92),
	(18, '62972641', '2024-08-23 07:10:00', 'entrada', 93),
	(19, '76287612', '2024-08-23 07:10:00', 'entrada', 94),
	(20, '60412360', '2024-08-23 07:10:00', 'entrada', 95),
	(21, '60439288', '2024-08-23 07:10:00', 'entrada', 76),
	(22, '60232208', '2024-08-23 07:10:00', 'entrada', 96),
	(23, '60130312', '2024-08-23 07:10:00', 'entrada', 97),
	(24, '72208360', '2024-08-23 07:10:00', 'entrada', 98),
	(25, '71587081', '2024-08-23 07:10:00', 'entrada', 99),
	(26, '74919081', '2024-08-23 07:10:00', 'entrada', 100),
	(27, '74288487', '2024-08-23 07:10:00', 'entrada', 102),
	(28, '71508844', '2024-08-22 07:10:00', 'entrada', 27),
	(29, '77501343', '2024-08-22 07:10:00', 'entrada', 28),
	(30, '60034766', '2024-08-22 07:10:00', 'entrada', 29),
	(31, '70537108', '2024-08-22 07:10:00', 'entrada', 30),
	(32, '76066707', '2024-08-22 07:10:00', 'entrada', 31),
	(33, '60751947', '2024-08-22 07:10:00', 'entrada', 32),
	(34, '75270594', '2024-08-22 07:10:00', 'entrada', 33),
	(35, '76621324', '2024-08-22 07:10:00', 'entrada', 34),
	(36, '60454435', '2024-08-22 07:10:00', 'entrada', 35),
	(37, '72423393', '2024-08-22 07:10:00', 'entrada', 36),
	(38, '75099150', '2024-08-22 07:10:00', 'entrada', 37),
	(39, '75316546', '2024-08-22 07:10:00', 'entrada', 38),
	(40, '71274636', '2024-08-22 07:10:00', 'entrada', 39),
	(41, '71275482', '2024-08-22 07:10:00', 'entrada', 40),
	(42, '76796036', '2024-08-22 07:10:00', 'entrada', 41),
	(43, '61023628', '2024-08-22 07:10:00', 'entrada', 42),
	(44, '76374216', '2024-08-22 07:10:00', 'entrada', 43),
	(45, '74777820', '2024-08-22 07:10:00', 'entrada', 44),
	(46, '60494430', '2024-08-22 07:10:00', 'entrada', 45),
	(47, '47879022', '2024-08-22 07:10:00', 'entrada', 46),
	(48, '75166783', '2024-08-22 07:10:00', 'entrada', 47),
	(49, '71479826', '2024-08-22 07:10:00', 'entrada', 48),
	(50, '60256863', '2024-08-22 07:10:00', 'entrada', 49),
	(51, '60412274', '2024-08-22 07:10:00', 'entrada', 50),
	(52, '75813091', '2024-08-22 07:10:00', 'entrada', 51),
	(53, '60132627', '2024-08-22 07:10:00', 'entrada', 52),
	(54, '72743190', '2024-08-22 07:10:00', 'entrada', 53),
	(55, '60128370', '2024-08-22 07:10:00', 'entrada', 54),
	(56, '61021239', '2024-08-22 07:10:00', 'entrada', 55),
	(57, '76656763', '2024-08-22 07:10:00', 'entrada', 56),
	(58, '60129522', '2024-08-22 07:10:00', 'entrada', 57),
	(59, '62085352', '2024-08-22 07:10:00', 'entrada', 58),
	(60, '60356497', '2024-08-22 07:10:00', 'entrada', 59),
	(61, '60131439', '2024-08-22 07:10:00', 'entrada', 60),
	(62, '63434406', '2024-08-22 07:10:00', 'entrada', 61),
	(63, '60356497', '2024-09-19 08:02:09', 'entrada', 59),
	(64, '74777820', '2024-09-19 08:02:21', 'entrada', 44),
	(65, '60494430', '2024-09-19 08:02:47', 'entrada', 45),
	(66, '47879022', '2024-09-19 08:03:05', 'entrada', 46),
	(67, '60751947', '2024-09-19 08:03:19', 'entrada', 32),
	(68, '60034766', '2024-09-19 08:04:05', 'entrada', 29),
	(69, '71508844', '2024-09-19 08:04:21', 'entrada', 27),
	(70, '75270594', '2024-09-19 08:04:36', 'entrada', 33),
	(71, '61023628', '2024-09-19 08:04:47', 'entrada', 42),
	(72, '63434406', '2024-09-19 08:05:02', 'entrada', 61),
	(73, '75316546', '2024-09-19 08:05:13', 'entrada', 38),
	(74, '76796036', '2024-09-19 08:05:24', 'entrada', 41),
	(75, '76066707', '2024-09-19 08:05:39', 'entrada', 31),
	(76, '77501343', '2024-09-19 08:05:59', 'entrada', 28),
	(77, '60129522', '2024-09-19 08:06:33', 'entrada', 57),
	(78, '71479826', '2024-09-19 08:06:47', 'entrada', 48),
	(79, '60412274', '2024-09-19 08:06:57', 'entrada', 50),
	(80, '75813091', '2024-09-19 08:07:10', 'entrada', 51),
	(81, '60454435', '2024-09-19 08:07:22', 'entrada', 35),
	(82, '76621324', '2024-09-19 08:07:57', 'entrada', 34),
	(83, '60131439', '2024-09-19 08:08:18', 'entrada', 60),
	(84, '71275482', '2024-09-19 08:08:31', 'entrada', 40),
	(85, '60128370', '2024-09-19 08:09:00', 'entrada', 54),
	(86, '76656763', '2024-09-19 08:09:21', 'entrada', 56),
	(87, '60132627', '2024-09-19 08:09:52', 'entrada', 52),
	(88, '61021239', '2024-09-19 08:10:11', 'entrada', 55),
	(89, '62085352', '2024-09-19 08:11:56', 'entrada', 58),
	(90, '62972641', '2024-09-20 07:46:16', 'entrada', 93),
	(91, '73777355', '2024-09-20 07:47:31', 'entrada', 86),
	(92, '60130312', '2024-09-20 07:47:49', 'entrada', 97),
	(93, '61743129', '2024-09-20 07:48:07', 'entrada', 91),
	(94, '62471857', '2024-09-20 07:48:25', 'entrada', 80),
	(95, '73067928', '2024-09-20 07:49:11', 'entrada', 85),
	(96, '74361094', '2024-09-20 07:49:26', 'entrada', 90),
	(97, '60821445', '2024-09-20 07:50:10', 'entrada', 84),
	(98, '61035596', '2024-09-20 07:50:31', 'entrada', 83),
	(99, '77152782', '2024-09-20 08:02:10', 'entrada', 103),
	(100, '74452463', '2024-09-20 08:07:00', 'entrada', 104),
	(101, '71275482', '2024-09-26 07:14:38', 'entrada', 40),
	(102, '60356497', '2024-09-26 07:15:02', 'entrada', 59),
	(103, '60494430', '2024-09-26 07:15:20', 'entrada', 45),
	(104, '60131439', '2024-09-26 07:15:37', 'entrada', 60),
	(105, '70537108', '2024-09-26 07:15:57', 'entrada', 30),
	(106, '71479826', '2024-09-26 07:16:11', 'entrada', 48),
	(107, '76374216', '2024-09-26 07:16:26', 'entrada', 43),
	(108, '60132627', '2024-09-26 07:16:42', 'entrada', 52),
	(109, '61023628', '2024-09-26 07:16:53', 'entrada', 42),
	(110, '77501343', '2024-09-26 07:17:06', 'entrada', 28),
	(111, '60128370', '2024-09-26 07:17:25', 'entrada', 54),
	(112, '71508844', '2024-09-26 07:17:36', 'entrada', 27),
	(113, '76066707', '2024-09-26 07:17:56', 'entrada', 31),
	(114, '76656763', '2024-09-26 07:18:22', 'entrada', 56),
	(115, '60412274', '2024-09-26 07:18:35', 'entrada', 50),
	(116, '75270594', '2024-09-26 07:18:51', 'entrada', 33),
	(117, '60129522', '2024-09-26 07:19:09', 'entrada', 57),
	(118, '60751947', '2024-09-26 07:19:27', 'entrada', 32),
	(119, '76796036', '2024-09-26 07:19:58', 'entrada', 41),
	(120, '61021239', '2024-09-26 07:20:18', 'entrada', 55),
	(121, '63434406', '2024-09-26 07:20:31', 'entrada', 61),
	(122, '75316546', '2024-09-26 07:20:46', 'entrada', 38),
	(123, '62085352', '2024-09-26 07:21:16', 'entrada', 58),
	(124, '76621324', '2024-09-26 07:22:09', 'entrada', 34),
	(125, '60454435', '2024-09-26 07:22:21', 'entrada', 35),
	(126, '60034766', '2024-09-26 07:22:48', 'entrada', 29),
	(127, '47879022', '2024-09-26 07:23:13', 'entrada', 46),
	(128, '60130268', '2024-09-26 08:40:26', 'entrada', 105),
	(129, '74777820', '2024-09-26 08:40:48', 'entrada', 44),
	(130, '73067928', '2024-09-27 08:13:53', 'entrada', 85),
	(131, '62972641', '2024-09-27 08:14:24', 'entrada', 93),
	(132, '60130312', '2024-09-27 08:14:53', 'entrada', 97),
	(133, '71587081', '2024-09-27 08:15:12', 'entrada', 99),
	(134, '60929859', '2024-09-27 08:15:27', 'entrada', 88),
	(135, '77339393', '2024-09-27 08:15:47', 'entrada', 79),
	(136, '75900029', '2024-09-27 08:16:10', 'entrada', 92),
	(137, '71872845', '2024-09-27 08:16:27', 'entrada', 78),
	(138, '75745991', '2024-09-27 08:16:40', 'entrada', 81),
	(139, '74452463', '2024-09-27 08:16:54', 'entrada', 104),
	(140, '74361094', '2024-09-27 08:17:08', 'entrada', 90),
	(141, '60508851', '2024-09-27 08:17:43', 'entrada', 82),
	(142, '61035596', '2024-09-27 08:18:03', 'entrada', 83),
	(143, '75742367', '2024-09-27 08:18:16', 'entrada', 89),
	(144, '61743129', '2024-09-27 08:18:36', 'entrada', 91),
	(145, '62471857', '2024-09-27 08:18:48', 'entrada', 80);

-- Volcando estructura para tabla attendance.programas_estudio
CREATE TABLE IF NOT EXISTS `programas_estudio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_corto` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla attendance.programas_estudio: ~10 rows (aproximadamente)
REPLACE INTO `programas_estudio` (`id`, `nombre`, `nombre_corto`) VALUES
	(1, 'Administración de Centros de Computo', 'ACC'),
	(2, 'Construcción Civil', 'CCI'),
	(3, 'Contabilidad', 'CON'),
	(4, 'Electricidad Industrial', 'ELE'),
	(5, 'Electrónica Industrial', 'ELO'),
	(6, 'Manejo Forestal', 'MFO'),
	(7, 'Mecánica Agrícola', 'MAG'),
	(8, 'Mecánica Automotriz', 'MAU'),
	(9, 'Mecánica de Producción Industrial', 'MPR'),
	(10, 'Producción Agropecuaria', 'PAG');

-- Volcando estructura para tabla attendance.secciones
CREATE TABLE IF NOT EXISTS `secciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_programa_estudio` int DEFAULT NULL,
  `id_ciclo` int DEFAULT NULL,
  `año` year DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id_programa_estudio` (`id_programa_estudio`) USING BTREE,
  KEY `id_ciclo` (`id_ciclo`) USING BTREE,
  CONSTRAINT `secciones_ibfk_1` FOREIGN KEY (`id_programa_estudio`) REFERENCES `programas_estudio` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `secciones_ibfk_2` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla attendance.secciones: ~2 rows (aproximadamente)
REPLACE INTO `secciones` (`id`, `id_programa_estudio`, `id_ciclo`, `año`) VALUES
	(1, 3, 2, '2024'),
	(2, 9, 2, '2024');

-- Volcando estructura para tabla attendance.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `role` enum('admin','docente','estudiante') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'estudiante',
  `status` enum('activo','inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT 'activo',
  `first_login` tinyint(1) DEFAULT '1',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombres` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellidos` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `dni` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `genero` enum('Masculino','Femenino') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `foto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ultima_conexion` timestamp NULL DEFAULT NULL,
  `intentos_fallidos` int DEFAULT '0',
  `reset_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `reset_token_expiry` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  UNIQUE KEY `dni` (`dni`) USING BTREE,
  KEY `idx_username` (`username`) USING BTREE,
  KEY `idx_role` (`role`) USING BTREE,
  KEY `idx_status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla attendance.usuarios: ~66 rows (aproximadamente)
REPLACE INTO `usuarios` (`id`, `username`, `password`, `role`, `status`, `first_login`, `email`, `nombres`, `apellidos`, `dni`, `genero`, `foto`, `fecha_creacion`, `ultima_conexion`, `intentos_fallidos`, `reset_token`, `reset_token_expiry`) VALUES
	(1, 'admin', '$2y$10$eqit/ewNzYnSjoyt6hRbRe8wZdxmc2E4v9yLEdPkKnJUeAcQHzbZy', 'admin', 'activo', 0, 'appinternetnos@gmail.com', 'admin', 'admin', '18887777', 'Masculino', '1726271850_3906.jpg', '2024-08-31 14:26:08', '2024-10-30 18:16:45', 0, NULL, NULL),
	(2, 'mamacifuenfatama', '$2y$10$5UeHTFSq5LCHsI.X8LQ/LO4Zevaz9dfnLgMWx/HHJ35DDQyWl/6wq', 'estudiante', 'activo', 0, '8c912531@example.com', 'Mark Anllelo', 'AMACIFUEN FATAMA', '60439288', 'Masculino', NULL, '2024-09-14 03:41:29', '2024-09-14 04:16:17', 0, NULL, NULL),
	(3, 'darellanonavarro', '$2y$10$Hk/roaaKYiu78yWEXfTGL..9yhmd77/z75o7hmZnrRqQapq4QW0bO', 'estudiante', 'activo', 0, '09822c51@example.com', 'Danilo Sebastian', 'ARELLANO NAVARRO', '75990170', 'Masculino', NULL, '2024-09-14 03:42:03', '2024-09-15 15:14:56', 0, NULL, NULL),
	(4, 'aarmasrios', '$2y$10$25YH96v2tHaN8EuiZNqI3uKJnXPyf2MXuuk1TMMLydaFPUOApj/zC', 'estudiante', 'activo', 0, '9ed56234@example.com', 'Alicia Isabel', 'ARMAS RIOS', '71872845', 'Femenino', NULL, '2024-09-14 03:42:37', NULL, 0, NULL, NULL),
	(5, 'aayalanomberto', '$2y$10$3LGC2/zteaAPzWwsD76pDO5G9c7.Y95PYJiVkDTgXw8Aq9UejHu.W', 'estudiante', 'activo', 0, '3eb1ad2e@example.com', 'Angelita Brigith', 'AYALA NOMBERTO', '77339393', 'Femenino', NULL, '2024-09-14 03:43:47', NULL, 0, NULL, NULL),
	(6, 'pberzzottimera', '$2y$10$o43PUfQOsfUaDHKpFEqemOPb2vuBTeJQxwR1TxWH0Tu1eV69KRn1W', 'estudiante', 'activo', 0, '6f1e194d@example.com', 'Priscilla Kimberly', 'BERZZOTTI MERA', '62471857', 'Femenino', NULL, '2024-09-14 03:44:10', NULL, 0, NULL, NULL),
	(7, 'jcabreraayala', '$2y$10$nlPikMUbuyrOBU8BuWlTN..pmILf5bkH.9nfx6mZSRvgdhImFVx8W', 'estudiante', 'activo', 0, '719bcc54@example.com', 'Jhon Deivis', 'CABRERA AYALA', '75745991', 'Masculino', NULL, '2024-09-14 03:44:53', NULL, 0, NULL, NULL),
	(8, 'ncuynanaripilco', '$2y$10$cuo3Odg1v2BEHr63yIhMSO51bGx7gibfqT42uWKaTi3SyHZtF1.wO', 'estudiante', 'activo', 0, 'b4f83cec@example.com', 'Nirey', 'CUYNANARI PILCO', '60508851', 'Femenino', NULL, '2024-09-14 03:45:23', NULL, 0, NULL, NULL),
	(9, 'iechevarriavargas', '$2y$10$yp/fcrs4nnOXBz9bO2p.ROWFKqUjneE48EOzhI9nxRNsspNodiJ6m', 'estudiante', 'activo', 0, '0a333ee2@example.com', 'Ingry Pamela', 'ECHEVARRIA VARGAS', '61035596', 'Femenino', NULL, '2024-09-14 03:45:46', NULL, 0, NULL, NULL),
	(10, 'pestareslopez', '$2y$10$1HKisAZ26iH0OumswyutOeFl54crQpvpi7qrln2k/IGWoCfd8luye', 'estudiante', 'activo', 0, 'a32f951c@example.com', 'Pedro Miguel', 'ESTARES LOPEZ', '60821445', 'Masculino', NULL, '2024-09-14 03:46:11', NULL, 0, NULL, NULL),
	(11, 'pfababaromero', '$2y$10$V1G6nT7ebELznnnlUjkI5.Vb51rQODDf9rBLkmCZ9cbJSTD181juq', 'estudiante', 'activo', 0, '379a2e6a@example.com', 'Patrick Gerard', 'FABABA ROMERO', '73067928', 'Masculino', NULL, '2024-09-14 03:46:34', NULL, 0, NULL, NULL),
	(12, 'jgonzalesamasifuen', '$2y$10$hUhjjbYfIJ.F85SUR32G5.f6FttWuXRQrGcOcDS.LOnwCkyhUDn2m', 'estudiante', 'activo', 0, '65d92267@example.com', 'Jhorlan Manuel', 'GONZALES AMASIFUEN', '73777355', 'Masculino', NULL, '2024-09-14 03:47:31', NULL, 0, NULL, NULL),
	(13, 'dolascuagaleveau', '$2y$10$AKlq/J8/0MGZC05MhaPGB.YHKQ7nB.2jtO2M4HPw0vj38CdkGLKWa', 'estudiante', 'activo', 0, 'bbe3144c@example.com', 'Dayane Mishel', 'OLASCUAGA LEVEAU', '60412261', 'Femenino', NULL, '2024-09-14 03:47:56', NULL, 0, NULL, NULL),
	(14, 'jperezperez', '$2y$10$POI2t1Y3JtpybKk2w9f73e.aZGfipaJRThKBeAU/1rLbqbh1CMU8O', 'estudiante', 'activo', 0, '570d04db@example.com', 'Julian', 'PEREZ PEREZ', '60929859', 'Masculino', NULL, '2024-09-14 03:48:20', NULL, 0, NULL, NULL),
	(15, 'apilcotapullima', '$2y$10$vYqlKZRi274YJdKYBm7kuOB6SW.Ew27JeQjep9Q5rM6F93ogtJAxi', 'estudiante', 'activo', 0, 'cd8c58ae@example.com', 'Adriana', 'PILCO TAPULLIMA', '75742367', 'Femenino', NULL, '2024-09-14 03:48:46', NULL, 0, NULL, NULL),
	(16, 'ppinapinedo', '$2y$10$WBm41eg3R/13FcWF1CV5Zeoau1bf0tDafed4VcOp.SRrW9KTgSMfO', 'estudiante', 'activo', 0, '49bd13e9@example.com', 'Paul Jhair', 'PIÑA PINEDO', '74361094', 'Masculino', NULL, '2024-09-14 03:49:31', NULL, 0, NULL, NULL),
	(17, 'jquispemera', '$2y$10$vsXbnz1xd1.kSfTya1iVz.H0B7X4fhYmK.5b9EQ1fLTTRKVuu.Fx.', 'estudiante', 'activo', 0, 'd024c317@example.com', 'Johana', 'QUISPE MERA', '61743129', 'Femenino', NULL, '2024-09-14 03:50:24', NULL, 0, NULL, NULL),
	(18, 'aramirezmori', '$2y$10$A.TBd/iCNCCp4EwnmZagCe.yyAYipDjDttW59CP6ifnXV8s4w5F6i', 'estudiante', 'activo', 0, '7446a3f2@example.com', 'Alfonso Jesus', 'RAMIREZ MORI', '75900029', 'Masculino', NULL, '2024-09-14 03:51:26', NULL, 0, NULL, NULL),
	(19, 'kreateguiruiz', '$2y$10$WIP4510v0M5OvCGMXPAMx.5be1dRvDqqWdxffjqz1bLzCuSPV7x9O', 'estudiante', 'activo', 0, '83b284a3@example.com', 'Ketty Paola', 'REATEGUI RUIZ', '62972641', 'Femenino', NULL, '2024-09-14 03:52:09', NULL, 0, NULL, NULL),
	(20, 'nromerocaballero', '$2y$10$QVC8dTmWmAtTRQPPNnrQqu1Oe7nY3GW98B/Izq6fmhhhasfuCdlji', 'estudiante', 'activo', 0, '8e930272@example.com', 'Nicole Alexandra', 'ROMERO CABALLERO', '76287612', 'Femenino', NULL, '2024-09-14 03:52:41', NULL, 0, NULL, NULL),
	(21, 'jsaboyafarge', '$2y$10$lvGtyqezFVIBZTkuyGI9a.qmjLpLWXjmzDB282ZqnbBPy4PFtsVJG', 'estudiante', 'activo', 0, '94c0ed4b@example.com', 'Jose Luis', 'SABOYA FARGE', '60412360', 'Masculino', NULL, '2024-09-14 03:53:16', NULL, 0, NULL, NULL),
	(22, 'jsanchezperez', '$2y$10$2r6YTdyBpYO2hBgeKafN/evQ5.K/FfJvDJUVNIqrlaPtF4i4Hjy0G', 'estudiante', 'activo', 0, 'f077c7a3@example.com', 'Johan Luis', 'SANCHEZ PEREZ', '60232208', 'Masculino', NULL, '2024-09-14 03:56:11', NULL, 0, NULL, NULL),
	(23, 'mshupingahuavela', '$2y$10$qxyEwSY4spS9pmQVMiVaHObqAi.z.qpm0yvBzT1yLpwezLiwP/onS', 'estudiante', 'activo', 0, '6a795427@example.com', 'Maikol Estiban', 'SHUPINGAHUA VELA', '60130312', 'Masculino', NULL, '2024-09-14 03:56:35', NULL, 0, NULL, NULL),
	(24, 'atuestarios', '$2y$10$Fai9NpWu/Ubv280qQDE8YO4qTRt6Hrf3vpvn3nurEG/qEFEZxPxqm', 'estudiante', 'activo', 0, '91a20fe2@example.com', 'Alexandra Fiorela', 'TUESTA RIOS', '72208360', 'Femenino', NULL, '2024-09-14 03:57:02', NULL, 0, NULL, NULL),
	(25, 'rvallesotero', '$2y$10$uwIFxYmpBm6Q8D0pTGV3ieKWruChO6CxuTGYGU1l6kz4FDZ.fWKgO', 'estudiante', 'activo', 0, '0ed4326f@example.com', 'Roberto Mario', 'VALLES OTERO', '71587081', 'Masculino', NULL, '2024-09-14 03:57:24', NULL, 0, NULL, NULL),
	(26, 'mvillacortapezo', '$2y$10$4q5trziGtGODBX0.KZwwzeMSSYSiLgrXmfodBadmeQskM62nhVSgy', 'estudiante', 'activo', 0, '3cbbf63a@example.com', 'Mark Affleck', 'VILLACORTA PEZO', '74919081', 'Masculino', NULL, '2024-09-14 03:57:52', NULL, 0, NULL, NULL),
	(27, 'avillalobosrodriguez', '$2y$10$uGgjp3vNP04J0sZpnTQM7eNDigCQbZAZN/H6J/Hz2TNQRH6xlMr4C', 'estudiante', 'activo', 0, '0c068d19@example.com', 'Alexandra Yamali', 'VILLALOBOS RODRIGUEZ', '74288487', 'Femenino', NULL, '2024-09-14 03:58:16', NULL, 0, NULL, NULL),
	(28, 'sarevaloampuero', '$2y$10$T5pMLDIsUuymh0g.UbWu5eCp4N5OsaHupB6xcTcERpLgardvRoD6O', 'estudiante', 'activo', 0, '06993b6f@example.com', 'Salvador', 'AREVALO AMPUERO', '71508844', 'Masculino', NULL, '2024-09-14 04:23:57', NULL, 0, NULL, NULL),
	(29, 'wchinguelmundaca', '$2y$10$OVjTO2j8u4X45BvlEMDZdut9nc5wI5wB2eBhcvFvTqh6snjhu/UCO', 'estudiante', 'activo', 0, 'f8320b86@example.com', 'Wilington Alex', 'CHINGUEL MUNDACA', '77501343', 'Masculino', NULL, '2024-09-14 04:24:24', NULL, 0, NULL, NULL),
	(30, 'hdelluna', '$2y$10$TAMtljEwVdUumvycwznd4uiw4MFCYZfcN9vtmJW5e.JMNO4W52W36', 'estudiante', 'activo', 0, '6a9c53ee@example.com', 'Henry', 'DEL AGUILA LUNA', '60034766', 'Masculino', NULL, '2024-09-14 04:24:57', NULL, 0, NULL, NULL),
	(31, 'ddelgadoquispe', '$2y$10$efIbfet9ibsrRYjR/gtHV.ZDwEOM6ACxJ4/krtMG77gvTVp392CDK', 'estudiante', 'activo', 0, '0351498f@example.com', 'Diego Janpier', 'DELGADO QUISPE', '70537108', 'Masculino', NULL, '2024-09-14 04:25:48', NULL, 0, NULL, NULL),
	(32, 'fdiazsalas', '$2y$10$OGAUFsXGAKOPKpaaLanV8.OlPHgEQaBFC3PUonTTgZZoK8YkzyuFC', 'estudiante', 'activo', 0, 'e59935e3@example.com', 'Frank Jhordani', 'DIAZ SALAS', '76066707', 'Masculino', NULL, '2024-09-14 04:26:22', NULL, 0, NULL, NULL),
	(33, 'hfasanandotafur', '$2y$10$WE58kBZVRcL13rxjpKNla.d8l6zjVkp8PCYucvpn5TTyHi60u9YhO', 'estudiante', 'activo', 0, 'b8b3c50e@example.com', 'Heinner Juvenal', 'FASANANDO TAFUR', '60751947', 'Masculino', NULL, '2024-09-14 04:27:09', NULL, 0, NULL, NULL),
	(34, 'dgeraldoizquierdo', '$2y$10$eSyDBO59IUWD6/cpIdhLdOYgGu6pmMSQMHNpN8LSElMZ.mgDx5ECa', 'estudiante', 'activo', 0, '59dcf6eb@example.com', 'Daniel Gonzalo', 'GERALDO IZQUIERDO', '75270594', 'Masculino', NULL, '2024-09-14 04:27:39', NULL, 0, NULL, NULL),
	(35, 'jgonzalessaavedra', '$2y$10$VXJn3FewcIm6.ZdWX0wEduThP6d34Od24ZKMWWM65yiPBOAaKCBCG', 'estudiante', 'activo', 0, '5e592830@example.com', 'Jared', 'GONZALES SAAVEDRA', '76621324', 'Masculino', NULL, '2024-09-14 04:28:09', NULL, 0, NULL, NULL),
	(36, 'shidalgoguevara', '$2y$10$mx3p1oktxaO8ehobhs623eOX.uj2aKbXkmx97.dQDqcvM4fA8cSRa', 'estudiante', 'activo', 0, 'ea164bf3@example.com', 'Solly Korayma', 'HIDALGO GUEVARA', '60454435', 'Femenino', NULL, '2024-09-14 04:28:35', NULL, 0, NULL, NULL),
	(37, 'minapisoto', '$2y$10$5YbxxN.s./WkxnecWSGG3.KrQ./Rxb7WxFZCCzMoI60bik10NdwYa', 'estudiante', 'activo', 0, '5fd51dd9@example.com', 'Miguel Angel', 'IÑAPI SOTO', '72423393', 'Masculino', NULL, '2024-09-14 04:29:00', NULL, 0, NULL, NULL),
	(38, 'gishuizaguerra', '$2y$10$zTDeJ9OdFM10DR.AGaFfA.w4GEDLURkp0als9hlSMZZxjpWDEpN5K', 'estudiante', 'activo', 0, '239768ed@example.com', 'Gerardo', 'ISHUIZA GUERRA', '75099150', 'Masculino', NULL, '2024-09-14 04:32:54', NULL, 0, NULL, NULL),
	(39, 'hlozanomas', '$2y$10$UHNTOlq/gJQxbgWzvvH6yeQA8gXZ9/fWFjjovwDzM8xm/W7t0AnjK', 'estudiante', 'activo', 0, '8aaff4b0@example.com', 'Hasler Hans', 'LOZANO MAS', '75316546', 'Masculino', NULL, '2024-09-14 04:33:18', NULL, 0, NULL, NULL),
	(40, 'cmoripinchi', '$2y$10$544bb8r5kGh6BFKXqKy3rOelAKIyLD.aTkderPFiZvth9ijBnsYdO', 'estudiante', 'activo', 0, '5acdb9b5@example.com', 'Camilo', 'MORI PINCHI', '71274636', 'Masculino', NULL, '2024-09-14 04:33:41', NULL, 0, NULL, NULL),
	(41, 'apenasanchez', '$2y$10$DGxKjQoMTPfW7jInYi/PeOJMU9OcJHw83NXCwY3qyqWONsQyqPASm', 'estudiante', 'activo', 0, 'c94b1d61@example.com', 'Arison Steve', 'PEÑA SANCHEZ', '71275482', 'Masculino', NULL, '2024-09-14 04:34:18', NULL, 0, NULL, NULL),
	(42, 'apinchituanama', '$2y$10$urjGKvD4bOLV0VBU2wI/hOatzs7JvY6znwljOTHqP.MZkotezlSm2', 'estudiante', 'activo', 0, '09c29e57@example.com', 'Anhelo', 'PINCHI TUANAMA', '76796036', 'Masculino', NULL, '2024-09-14 04:34:41', NULL, 0, NULL, NULL),
	(43, 'mplasenciashupingahua', '$2y$10$O0sEY6bJ0OV2BOggMaS5/O.2/YrBrNePGbLz9ogKXYouttwPEuG7S', 'estudiante', 'activo', 0, '57f8300c@example.com', 'Maykol', 'PLASENCIA SHUPINGAHUA', '61023628', 'Masculino', NULL, '2024-09-14 04:35:19', NULL, 0, NULL, NULL),
	(44, 'mrafaelsinufara', '$2y$10$q9lpJCXRgBpHAbZagANno.6mT1b6ovm2lLH.wNDgwGhncXjTaLPRC', 'estudiante', 'activo', 0, '4674dc1a@example.com', 'Manuel', 'RAFAEL SINUFARA', '76374216', 'Masculino', NULL, '2024-09-14 04:35:45', NULL, 0, NULL, NULL),
	(45, 'eramoshurtado', '$2y$10$ZYt/qSUtAo3r/tfdme1GvOd7k0Jgfn7w.9WaTiDpomiMdKn4ZKC.u', 'estudiante', 'activo', 0, 'e352ba47@example.com', 'Edin Guzman', 'RAMOS HURTADO', '74777820', 'Masculino', NULL, '2024-09-14 04:36:13', NULL, 0, NULL, NULL),
	(46, 'rruizlozano', '$2y$10$nVy22eg0rVpGLGxM1oHtmeztFNPCCHz0y6yqbmFw2umH7yas9Crxq', 'estudiante', 'activo', 0, 'a64fca67@example.com', 'Raul Oswaldo', 'RUIZ LOZANO', '60494430', 'Masculino', NULL, '2024-09-14 04:36:36', NULL, 0, NULL, NULL),
	(47, 'wsaavedraushinahua', '$2y$10$OoT1C79.XU892Ay8YsGRq.RpOqCrEUf1oK97Rge/c6.ib4iuXRmP2', 'estudiante', 'activo', 0, 'e1a9d49e@example.com', 'Wilson', 'SAAVEDRA USHIÑAHUA', '47879022', 'Masculino', NULL, '2024-09-14 04:37:14', NULL, 0, NULL, NULL),
	(48, 'jsaavedraviena', '$2y$10$mRKjK60owhOrn/W9kQSjhu.whb3ocaaleeJk40MiSjS3SP2df2hce', 'estudiante', 'activo', 0, '4585487d@example.com', 'Jose Luis', 'SAAVEDRA VIENA', '75166783', 'Masculino', NULL, '2024-09-14 04:38:10', NULL, 0, NULL, NULL),
	(49, 'bsalasgarcia', '$2y$10$YpS4iMfpsFk3lDyJhS5ovu5wF2eKzBrh/BHfOu/WqBmCSrRURNLwq', 'estudiante', 'activo', 0, '4a10dd16@example.com', 'Bily Lley', 'SALAS GARCIA', '71479826', 'Masculino', NULL, '2024-09-14 04:38:38', NULL, 0, NULL, NULL),
	(50, 'asanchezdiaz', '$2y$10$gCMoz0Q1UZ8ZRHXbIVW0S.sa.JLM.APeOpoVG.T6xTsKruk4JujV6', 'estudiante', 'activo', 0, 'ceff6d06@example.com', 'Adriano', 'SANCHEZ DIAZ', '60256863', 'Masculino', NULL, '2024-09-14 04:39:33', '2024-09-16 19:31:29', 0, NULL, NULL),
	(51, 'dsandovaltrigoso', '$2y$10$iWfoBSVPSAyZRYQ9LHQWQOidnVXgfBu5SAYbREpyRXhdNdFySU4Cu', 'estudiante', 'activo', 0, '1358d20b@example.com', 'Dan Anthony', 'SANDOVAL TRIGOSO', '60412274', 'Masculino', NULL, '2024-09-14 04:39:56', NULL, 0, NULL, NULL),
	(52, 'asandovalurquia', '$2y$10$e2uhkZs78UV5XKjHrk48j.eTf.H711oDfDKJOfCD1L8bkwmQjUeMK', 'estudiante', 'activo', 0, '80a67374@example.com', 'Alvaro Eduardo', 'SANDOVAL URQUIA', '75813091', 'Masculino', NULL, '2024-09-14 04:40:23', NULL, 0, NULL, NULL),
	(53, 'xsatalayaisminio', '$2y$10$BndSY2.5zpv6lz0rFBKlAur5NjMtkSwfXSzo3ll15YVYOjVB2Wwjq', 'estudiante', 'activo', 0, '0ce4d9af@example.com', 'Xavier', 'SATALAYA ISMINIO', '60132627', 'Masculino', NULL, '2024-09-14 04:41:57', NULL, 0, NULL, NULL),
	(54, 'jsemperteguivela', '$2y$10$AmLreoIdY0G54s9TpTyjauZQT.Y7FRHrkLE011MzsDZblrucnxDxK', 'estudiante', 'activo', 0, '4ebba3d8@example.com', 'Jean Pier', 'SEMPERTEGUI VELA', '72743190', 'Masculino', NULL, '2024-09-14 04:42:20', NULL, 0, NULL, NULL),
	(55, 'jshupingahuasalas', '$2y$10$hpoIq9dpDqhozqbElw4oNOX17O/le7yHW3GyNHef1oXWqQ2OvIwyG', 'estudiante', 'activo', 0, 'b58a9838@example.com', 'Jheferson', 'SHUPINGAHUA SALAS', '60128370', 'Masculino', NULL, '2024-09-14 04:42:44', NULL, 0, NULL, NULL),
	(56, 'jsolsoltarazona', '$2y$10$M4xjqyz.J3vPKrbmQoximOD4i4uMpAkPBtox7gPjjaPlm0tJQfiVW', 'estudiante', 'activo', 0, '3b3453f8@example.com', 'Jose Manuel', 'SOLSOL TARAZONA', '61021239', 'Masculino', NULL, '2024-09-14 04:43:06', NULL, 0, NULL, NULL),
	(57, 'gtanantatapullima', '$2y$10$1OQSkcGV1CuOx9BRePQRLeX09Jq4bOwS1B8wIJSZLfNSSYFV8ucv6', 'estudiante', 'activo', 0, 'd2961cda@example.com', 'Geral', 'TANANTA TAPULLIMA', '76656763', 'Masculino', NULL, '2024-09-14 04:43:32', NULL, 0, NULL, NULL),
	(58, 'jtapullimatapullima', '$2y$10$oKnNBsTH/jnoAmKQuUjag.QenF60sPZOOxAILAIiijtgoQMAS7OJ.', 'estudiante', 'activo', 0, 'f9392861@example.com', 'Jhon Rafael', 'TAPULLIMA TAPULLIMA', '60129522', 'Masculino', NULL, '2024-09-14 04:45:02', NULL, 0, NULL, NULL),
	(59, 'cupiachihuarojas', '$2y$10$veuQVLq1LUkexXAOEu6S7ua4YWMIxsNPZuGlCMvSihk3OwRxu2xY2', 'estudiante', 'activo', 0, 'e7ee9b50@example.com', 'Chris Junior', 'UPIACHIHUA ROJAS', '62085352', 'Masculino', NULL, '2024-09-14 04:45:31', NULL, 0, NULL, NULL),
	(60, 'gvelasalas', '$2y$10$JnDS6PaY0R.9EjwSCHT.7uKxuLEQWxVSZdiGq3Nj6lBUHOVzLBD82', 'estudiante', 'activo', 0, '3866e537@example.com', 'Gonzalo Manuel', 'VELA SALAS', '60356497', 'Masculino', NULL, '2024-09-14 04:45:50', NULL, 0, NULL, NULL),
	(61, 'cventurarafael', '$2y$10$/7S/9qGefY6e6vOc1Hgxy.oww/dkaJkiInRSxJ0l6GlkO1SyitK9G', 'estudiante', 'activo', 0, 'af22f726@example.com', 'Cleyder Emerson', 'VENTURA RAFAEL', '60131439', 'Masculino', NULL, '2024-09-14 04:46:21', NULL, 0, NULL, NULL),
	(62, 'pvillacortatangoa', '$2y$10$.8nKMjT5lVkMtLjbqUt4TuDjZtUnMkrTwWr4HEzqI6R2YSMdfdG2e', 'estudiante', 'activo', 0, '36b7e95a@example.com', 'Piero Giovani', 'VILLACORTA TANGOA', '63434406', 'Masculino', NULL, '2024-09-14 04:46:48', NULL, 0, NULL, NULL),
	(63, 'sruizvasquez', '$2y$10$WJmYi5KEidb2YSSNghJNdeffPSZp8JLEB1z9JD1Ox14M68BT7wZf2', 'docente', 'activo', 0, 'sruizvasquez@gmail.com', 'Santiago Andrés', 'RUIZ VÁSQUEZ', '18882577', 'Masculino', '1726290845_1988.png', '2024-09-14 05:09:02', '2024-09-20 13:44:15', 0, NULL, NULL),
	(64, 'mgarciasaboya', '$2y$10$4.L6V59SpWRAr/rT5bR4le1KRjZsYwwbmMfi5b7Zl8Gw7DF9XAgYu', 'estudiante', 'activo', 0, 'f9ab5f2d@example.com', 'Mirian Cristina', 'GARCÍA SABAOYA', '77152782', 'Femenino', NULL, '2024-09-20 12:56:27', '2024-09-20 13:48:06', 0, NULL, NULL),
	(65, 'apanaifocenepo', '$2y$10$hn7g/WWnMVcNMU4xAe0BteTbDlBPX44cl1hKZV4iseZoVuiqcrqxm', 'estudiante', 'activo', 0, '726db565@example.com', 'Alvino', 'PANAIFO CENEPO', '74452463', 'Masculino', NULL, '2024-09-20 12:57:55', '2024-09-20 13:49:23', 0, NULL, NULL),
	(66, 'aperezsalas', '$2y$10$LWyLE.18Ecjwse5JaZm0DO/.kGKoCMiQj8NRy0co/ZVhtEif8S3ua', 'estudiante', 'activo', 0, 'alexperezsalas533@gmail.com', 'Alex Adriano', 'PÉREZ SALAS', '60130268', NULL, NULL, '2024-09-26 13:37:46', '2024-09-26 13:38:00', 0, NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
