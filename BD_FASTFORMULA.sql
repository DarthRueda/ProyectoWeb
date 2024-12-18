-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versió del servidor:          9.0.1 - MySQL Community Server - GPL
-- SO del servidor:              Linux
-- HeidiSQL Versió:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table BD_FASTFORMULA.bebidas: ~9 rows (approximately)
INSERT INTO `bebidas` (`id_bebida`, `nombre`, `descripcion`, `precio`, `imagen`) VALUES
	(1, 'Fanta Naranja', 'Fanta de Naranja', 1.99, 'views/img/bebidas/fanta_naranja.png'),
	(2, 'Fanta Limon', 'Fanta de Limon', 1.99, 'views/img/bebidas/fanta_limon.png'),
	(3, 'Coca-Cola', 'Coca-Cola', 1.99, 'views/img/bebidas/coca-cola.png'),
	(4, 'Sprite', 'Sprite', 1.99, 'views/img/bebidas/sprite.png'),
	(5, 'Agua Mineral', 'Agua', 1.99, 'views/img/bebidas/agua-mineral.png'),
	(6, 'Cerveza', 'Cerveza', 1.99, 'views/img/bebidas/cerveza.png'),
	(7, 'Acuarius', 'Acuarius', 1.99, 'views/img/bebidas/acuarius.png'),
	(8, 'Monster', 'Monster', 1.99, 'views/img/bebidas/monster.png'),
	(9, 'Trina Naranja', 'Trina de Naranja', 1.99, 'views/img/bebidas/trina-naranja.png');

-- Dumping data for table BD_FASTFORMULA.complementos: ~5 rows (approximately)
INSERT INTO `complementos` (`id_complemento`, `nombre`, `descripcion`, `precio`, `imagen`) VALUES
	(1, 'Patatas', 'Patatas Fritas', 3.99, 'views/img/complementos/patatas.png'),
	(2, 'Patatas F1', 'Patatas Deluxe', 4.99, 'views/img/complementos/patatas_f1.png'),
	(3, 'Alitas de Pollo', 'Alitas de Polllo', 5, 'views/img/complementos/alitas.png'),
	(4, 'Aros Pirelli', 'Aros de cebolla Pirelli', 3.99, 'views/img/complementos/pirelli.png'),
	(5, 'Pitstop', 'Explosion Pitstop', 3.99, 'views/img/complementos/pitstop.png');

-- Dumping data for table BD_FASTFORMULA.hamburguesas: ~11 rows (approximately)
INSERT INTO `hamburguesas` (`id_hamburguesa`, `nombre`, `descripcion`, `precio`, `imagen`) VALUES
	(1, 'Tsunoda', 'Hamburguesa de Verdura', 7.99, 'views/img/hamburguesas/tsunoda.png'),
	(2, 'Piastri', 'Hamburguesa de Pollo', 7.99, 'views/img/hamburguesas/piastri.png'),
	(3, 'Webber', 'Hamburguesa con huevo', 7.99, 'views/img/hamburguesas/webber.png'),
	(4, 'Alonso', 'Hamburguesa de tus muertos', 7.99, 'views/img/hamburguesas/alonso.png'),
	(6, 'Perez', 'Hamburguesa picante con verduras', 7.99, 'views/img/hamburguesas/perez.png'),
	(7, 'Verstappen', 'Hamburguesa para campeones', 7.99, 'views/img/hamburguesas/verstappen.png'),
	(8, 'Sainz', 'Hamburguesa Bacon', 7.99, 'views/img/hamburguesas/sainz.png'),
	(9, 'Ocon', 'Ocon', 7.99, 'views/img/hamburguesas/ocon.png'),
	(10, 'Senna', 'Senna', 7.99, 'views/img/hamburguesas/senna.png'),
	(11, 'Hamilton', 'Hamilton', 7.99, 'views/img/hamburguesas/hamilton.png'),
	(12, 'Lauda', 'Lauda', 7.99, 'views/img/hamburguesas/nikilauda.png');

-- Dumping data for table BD_FASTFORMULA.menus: ~6 rows (approximately)
INSERT INTO `menus` (`id_menu`, `nombre`, `descripcion`, `precio`, `imagen`, `id_hamburguesa`, `id_bebida`, `id_complemento`) VALUES
	(1, 'Menu Alonso', 'Menu Alonso', 11.99, 'views/img/menus/menu_alonso.png', 4, NULL, NULL),
	(2, 'Menu Verstappen', 'Menu Verstappen', 11.99, 'views/img/menus/menu_verstappen.png', 7, NULL, NULL),
	(3, 'Menu Perez', 'Menu Perez', 11.99, 'views/img/menus/menu_perez.png', 6, NULL, NULL),
	(4, 'Menu Sainz', 'Menu Sainz', 11.99, 'views/img/menus/menu_sainz.png', 8, NULL, NULL),
	(5, 'Menu Hamilton', 'Menu Hamilton', 11.99, 'views/img/menus/menu_hamilton.png', 11, NULL, NULL),
	(7, 'Menu Russell', 'Menu Russell', 9.99, 'views/img/menus/menu_russell.png', 2, NULL, NULL);

-- Dumping data for table BD_FASTFORMULA.ofertas: ~2 rows (approximately)
INSERT INTO `ofertas` (`id_oferta`, `nombre`, `descripcion`, `descuento`, `fecha_inicio`, `fecha_fin`) VALUES
	(1, 'alonso33', 'Codigo alonso', 33, '2024-11-25', '2024-11-28'),
	(2, 'mclaren2024', 'Campeones del mundo 2024', 24, '2024-12-09', '2024-12-31');

-- Dumping data for table BD_FASTFORMULA.pedidos: ~30 rows (approximately)
INSERT INTO `pedidos` (`id_pedido`, `pedido`, `iva`, `total`, `pagado`, `id_usuario`, `id_oferta`, `fecha`) VALUES
	(78, 15.98, 1.6, 17.58, 1, 4, NULL, '2024-12-10 18:56:24'),
	(79, 27.97, 2.8, 23.39, 1, 4, 2, '2024-12-10 19:02:35'),
	(80, 36.94, 3.69, 40.63, 1, 4, NULL, '2024-12-11 18:34:59'),
	(81, 7.99, 0.8, 8.79, 0, 4, NULL, '2024-12-11 18:35:47'),
	(82, 3.98, 0.4, 4.38, 1, 6, NULL, '2024-12-11 18:37:23'),
	(83, 34.96, 3.5, 38.46, 1, NULL, NULL, '2024-12-11 18:38:17'),
	(84, 39.95, 4, 43.95, 0, NULL, NULL, '2024-12-11 19:03:28'),
	(85, 1.99, 0.2, 2.19, 1, NULL, NULL, '2024-12-11 19:03:41'),
	(86, 77.9, 7.79, 85.69, 1, 6, NULL, '2024-12-11 19:04:52'),
	(87, 19.98, 2, 21.98, 1, 6, NULL, '2024-12-11 19:05:10'),
	(88, 27.95, 2.8, 23.37, 1, 6, 2, '2024-12-11 19:05:39'),
	(89, 53.93, 5.39, 59.32, 0, 4, NULL, '2024-12-11 19:06:28'),
	(90, 11.99, 1.2, 13.19, 0, NULL, NULL, '2024-12-11 19:06:51'),
	(91, 31.96, 3.2, 35.16, 1, 4, NULL, '2024-12-12 16:19:19'),
	(92, 23.98, 2.4, 26.38, 1, NULL, NULL, '2024-12-12 16:19:42'),
	(93, 25.97, 2.6, 21.71, 1, NULL, 2, '2024-12-13 19:19:57'),
	(94, 23.97, 2.4, 26.37, 1, 6, NULL, '2024-12-13 19:37:03'),
	(95, 9.99, 1, 10.99, 1, 4, NULL, '2024-12-13 19:38:01'),
	(97, 19.96, 2, 21.96, 1, NULL, NULL, '2024-12-17 15:29:52'),
	(98, 7.99, 0.8, 8.79, 1, NULL, NULL, '2024-12-17 15:30:26'),
	(99, 23.96, 2.4, 26.36, 1, 6, NULL, '2024-12-17 15:31:37'),
	(102, 7.99, 0.8, 8.79, 0, NULL, NULL, '2024-12-17 17:30:27'),
	(103, 27.97, 2.8, 30.77, 0, NULL, NULL, '2024-12-17 17:30:39'),
	(104, 15.98, 1.6, 17.58, 0, NULL, NULL, '2024-12-17 17:54:35'),
	(105, 41.94, 4.19, 46.13, 0, NULL, NULL, '2024-12-17 17:54:52'),
	(106, 11.98, 1.2, 13.18, 0, NULL, NULL, '2024-12-17 17:57:09'),
	(107, 15.98, 1.6, 17.58, 1, 4, NULL, '2024-12-17 18:34:06'),
	(108, 15.98, 1.6, 17.58, 0, NULL, NULL, '2024-12-17 19:14:35'),
	(109, 89.91, 8.99, 98.9, 0, NULL, NULL, '2024-12-17 19:15:00'),
	(110, 27.97, 2.8, 30.77, 0, NULL, NULL, '2024-12-18 18:43:16');

-- Dumping data for table BD_FASTFORMULA.pedido_bebida: ~0 rows (approximately)

-- Dumping data for table BD_FASTFORMULA.pedido_complemento: ~0 rows (approximately)

-- Dumping data for table BD_FASTFORMULA.pedido_hamburguesa: ~0 rows (approximately)

-- Dumping data for table BD_FASTFORMULA.pedido_menu: ~0 rows (approximately)

-- Dumping data for table BD_FASTFORMULA.usuarios: ~1 rows (approximately)
INSERT INTO `usuarios` (`id_usuario`, `usuario`, `nombre`, `apellido`, `contrasena`, `email`, `telefono`, `direccion`, `administrador`) VALUES
	(4, 'arnau04', 'Arnau', 'Rueda', '$2y$10$juHIRMB6R.7wnvh3/eK2..2WXLsOjwEf8tpa5gEAICLQmvBlMmHIm', 'ruedaar04@gmail.com', 675412345, 'C/ Ave del Paraiso Num 7', 1),
	(6, 'julian222', 'Julian', 'Pastor', '$2y$10$6t6bygHqaKxnm0eVyn8SrO2dSsATwVfJc6CY3de4zKTVEiH57UHIW', 'julianpastor@yahoo.es', 32432432, '', 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
