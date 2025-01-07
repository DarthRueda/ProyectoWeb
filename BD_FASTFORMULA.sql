CREATE DATABASE IF NOT EXISTS `BD_FASTFORMULA`
USE `BD_FASTFORMULA`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `contrasena` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` int NOT NULL,
  `direccion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `administrador` tinyint DEFAULT '0',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `nombre`, `apellido`, `contrasena`, `email`, `telefono`, `direccion`, `administrador`) VALUES
	(4, 'arnau04', 'Arnau', 'Rueda', '$2y$10$juHIRMB6R.7wnvh3/eK2..2WXLsOjwEf8tpa5gEAICLQmvBlMmHIm', 'ruedaar04@gmail.com', 675412345, 'C/ Ave del Paraiso Num 7', 1),
	(6, 'julian222', 'Julian', 'Pastor', '$2y$10$6t6bygHqaKxnm0eVyn8SrO2dSsATwVfJc6CY3de4zKTVEiH57UHIW', 'julianpastor@yahoo.es', 32432432, '', 0),
	(12, 'asda', 'asda', 'asdsa', '$2y$10$T3wszj9y0oR4Hi.D0Yx7DexghXldIUFLeBzYA92OUj.FdMO1XDdO2', 'asda@gasda', 2342, NULL, 0);

CREATE TABLE IF NOT EXISTS `bebidas` (
  `id_bebida` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `precio` float NOT NULL,
  `imagen` varchar(100) NOT NULL,
  PRIMARY KEY (`id_bebida`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bebidas` (`id_bebida`, `nombre`, `descripcion`, `precio`, `imagen`) VALUES
	(1, 'Fanta Naranja', 'Refréscate con el gran sabor a Naranja de Fanta. Sin azúcares añadidos y sin calorías. Sola o combinada, tómala bien fría, con hielo y una rodaja de naranja para disfrutar al máximo del momento.', 1.99, 'views/img/bebidas/fanta_naranja.svg'),
	(2, 'Fanta Limon', 'Refréscate con el gran sabor a Limón de Fanta. Sin azúcares añadidos y sin calorías. Sola o combinada, tómala bien fría, con hielo y una rodaja de limón para disfrutar al máximo del momento.', 1.99, 'views/img/bebidas/fanta_limon.svg'),
	(3, 'Coca-Cola', 'Nada como el sabor auténtico de una Coca-Cola bien fría para hacer el momento todavía más especial. Tómala muy fría con hielo', 1.99, 'views/img/bebidas/coca-cola.svg'),
	(4, 'Sprite', 'Gracias a su poder refrescante e hidratante Sprite siempre sienta bien. Sin azúcar y sin calorías. Tómalo bien frío, con hielo', 1.99, 'views/img/bebidas/sprite.svg'),
	(5, 'Agua Mineral', 'Refrescante agua mineral para reponer la hidratación y disfrutar del placer del agua fria', 1.99, 'views/img/bebidas/agua-mineral.svg'),
	(6, 'Cerveza', 'Disfruta de la mejor cerveza para acompañar tu menu y relajarte', 1.99, 'views/img/bebidas/cerveza.svg'),
	(7, 'Aquarius', 'Gracias a su poder refrescante e hidratante Aquarius Zero siempre sienta bien. Sin azúcar y sin calorías. Tómalo bien frío, con hielo', 1.99, 'views/img/bebidas/acuarius.svg'),
	(8, 'Monster', 'Bebida energética refrescante que contiene vitaminas y minerales', 1.99, 'views/img/bebidas/monster.svg'),
	(9, 'Trina Naranja', 'Deliciosa bebida de naranja sin gas para aquellos que prefieren una bebida mas ligera', 1.99, 'views/img/bebidas/trina-naranja.svg');

CREATE TABLE IF NOT EXISTS `complementos` (
  `id_complemento` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `precio` float NOT NULL,
  `imagen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_complemento`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `complementos` (`id_complemento`, `nombre`, `descripcion`, `precio`, `imagen`) VALUES
	(1, 'Patatas', 'Las famosas patatas fritas de las que tanto has oido hablar, si estás son, las mejores, las más crujientes, las que tienen más sabor, si las pruebas entenderás el porqué de su fama.', 3.99, 'views/img/complementos/patatas.svg'),
	(2, 'Patatas F1', 'Ahora puedes acompañar tus menús con las deliciosas Patatas F1', 4.99, 'views/img/complementos/patatas_f1.svg'),
	(3, 'Alitas de Pollo', 'Prueba nuestras nuevas alitas, más grandes y sabrosas, perfectas para los hambrientos amantes del buen pollo', 5, 'views/img/complementos/alitas.svg'),
	(4, 'Aros Pirelli', ' Los aros de cebolla Pirelli se pueden solicitar como entrada o acompañamiento, para compartir o solo para ti, son perfectos para todos', 3.99, 'views/img/complementos/pirelli.svg'),
	(5, 'Pitstop', 'Explosion picante de salsa cheddar y jalapeños perfecta para los mas valientes', 3.99, 'views/img/complementos/pitstop.svg');

CREATE TABLE IF NOT EXISTS `hamburguesas` (
  `id_hamburguesa` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `precio` float NOT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_hamburguesa`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `hamburguesas` (`id_hamburguesa`, `nombre`, `descripcion`, `precio`, `imagen`) VALUES
	(1, 'Tsunoda', 'Pequeña hamburguesa para quitar el hambre con rapidez', 7.99, 'views/img/hamburguesas/tsunoda.svg'),
	(2, 'Piastri', 'Deliciosa hamburguesa de Pollo con su pan de semilla', 7.99, 'views/img/hamburguesas/piastri.svg'),
	(3, 'Webber', 'Riquisima hamburguesa con huevo y ternera que mezcla lo mejor de los 2 sabores', 7.99, 'views/img/hamburguesas/webber.svg'),
	(4, 'Alonso', 'Hamburguesa doble de tenera digna de un campeon', 7.99, 'views/img/hamburguesas/alonso.svg'),
	(6, 'Perez', 'Hamburguesa picante con verduras al mas puro estilo Méxicano', 7.99, 'views/img/hamburguesas/perez.svg'),
	(7, 'Verstappen', 'La hamburguesa del campeon del mundo con ternera y queso fundido', 7.99, 'views/img/hamburguesas/verstappen.svg'),
	(8, 'Sainz', 'Deliciosa Hamburguesa con Bacon y Ternera acompañada de queso y pepinillos', 7.99, 'views/img/hamburguesas/sainz.svg'),
	(9, 'Ocon', 'Hamburguesa con ternera y pepinillos para todos aquelos que quieren un pequeño aperitivo', 7.99, 'views/img/hamburguesas/ocon.svg'),
	(10, 'Senna', 'Hamburguesa de ternera y lechuga con delicioso tomate', 7.99, 'views/img/hamburguesas/senna.svg'),
	(11, 'Hamilton', 'Hamburguesa vegana riquisima para aquellos que no quieren carne', 7.99, 'views/img/hamburguesas/hamilton.svg'),
	(12, 'Lauda', 'Doble ternera y bacon con explosion de sabores y perfecta lechuga', 7.99, 'views/img/hamburguesas/nikilauda.svg');

CREATE TABLE IF NOT EXISTS `menus` (
  `id_menu` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `precio` float NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `id_hamburguesa` int NOT NULL,
  `id_bebida` int DEFAULT NULL,
  `id_complemento` int DEFAULT NULL,
  PRIMARY KEY (`id_menu`),
  KEY `id_hamburguesa_idx` (`id_hamburguesa`),
  KEY `id_bebida_idx` (`id_bebida`),
  KEY `id_complemento_idx` (`id_complemento`),
  CONSTRAINT `id_bebida` FOREIGN KEY (`id_bebida`) REFERENCES `bebidas` (`id_bebida`),
  CONSTRAINT `id_complemento` FOREIGN KEY (`id_complemento`) REFERENCES `complementos` (`id_complemento`),
  CONSTRAINT `id_hamburguesa` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesas` (`id_hamburguesa`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `menus` (`id_menu`, `nombre`, `descripcion`, `precio`, `imagen`, `id_hamburguesa`, `id_bebida`, `id_complemento`) VALUES
	(1, 'Menú Alonso', 'La mezcla perfecta. Guarde espacio para la hamburguesa Doble Cheeseburger, dos carnes a la parrilla con queso', 11.99, 'views/img/menus/menu_alonso.svg', 4, NULL, NULL),
	(2, 'Menú Verstappen', 'Doble contraste y doble sabor, queso fundido sobre doble de carne jugosa a la parrilla, lechuga, pepinillos y cebolla', 11.99, 'views/img/menus/menu_verstappen.svg', 7, NULL, NULL),
	(3, 'Menú Pérez', 'Crujiente por fuera, tierno por dentro. El mejor pollo con un empanado crujiente y ligeramente picante, tomates recién cortados, lechuga fresca y mayonesa en un pan de semillas recién tostado. Una auténtica obra maestra.', 11.99, 'views/img/menus/menu_perez.svg', 6, NULL, NULL),
	(4, 'Menú Sainz', 'Haz doble tu hamburguesa de queso, añádele bacon y ahora aumenta su tamaño… lo sabemos, impresiona', 11.99, 'views/img/menus/menu_sainz.svg', 8, NULL, NULL),
	(5, 'Menú Hamilton', 'Vegetariano. Si eres cero de carne y mucho de plantas, te va a flipar el Hamilton Vegetariano', 11.99, 'views/img/menus/menu_hamilton.svg', 11, NULL, NULL),
	(7, 'Menú Russell', 'Menú elegido por Russell que selecciona los mejores componentes para un menu', 9.99, 'views/img/menus/menu_russell.svg', 2, NULL, NULL);

CREATE TABLE IF NOT EXISTS `ofertas` (
  `id_oferta` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `descuento` int NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  PRIMARY KEY (`id_oferta`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ofertas` (`id_oferta`, `nombre`, `descripcion`, `descuento`, `fecha_inicio`, `fecha_fin`) VALUES
	(1, 'alonso33', 'Codigo alonso', 33, '2024-11-25', '2024-11-28'),
	(2, 'mclaren2024', 'Campeones del mundo 2024', 24, '2024-12-09', '2024-12-31');

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` int NOT NULL AUTO_INCREMENT,
  `pedido` float NOT NULL,
  `iva` float NOT NULL,
  `total` float NOT NULL,
  `pagado` tinyint NOT NULL DEFAULT '0',
  `id_usuario` int DEFAULT NULL,
  `id_oferta` int DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `id_usuario_idx` (`id_usuario`),
  KEY `id_oferta_idx` (`id_oferta`),
  CONSTRAINT `id_oferta` FOREIGN KEY (`id_oferta`) REFERENCES `ofertas` (`id_oferta`),
  CONSTRAINT `id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=244 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pedidos` (`id_pedido`, `pedido`, `iva`, `total`, `pagado`, `id_usuario`, `id_oferta`, `fecha`) VALUES
	(127, 47.94, 4.79, 52.73, 0, NULL, NULL, '2024-12-27 12:16:35'),
	(128, 27.96, 2.8, 30.76, 0, NULL, NULL, '2024-12-27 12:16:52'),
	(129, 37.95, 3.8, 41.75, 1, 4, NULL, '2024-12-27 12:19:26'),
	(130, 11.99, 1.2, 13.19, 0, 4, NULL, '2024-12-27 12:20:28'),
	(141, 26.97, 2.7, 29.67, 0, 4, NULL, '2024-12-27 12:49:19'),
	(142, 25.96, 2.6, 28.56, 0, NULL, NULL, '2024-12-27 12:51:03'),
	(143, 31.95, 3.2, 35.15, 0, 4, NULL, '2024-12-27 12:52:07'),
	(145, 21.94, 2.19, 24.13, 1, 6, NULL, '2024-12-27 12:55:23'),
	(146, 29.96, 3, 32.96, 0, 6, NULL, '2024-12-27 13:03:38'),
	(147, 19.96, 2, 21.96, 0, 6, NULL, '2024-12-27 13:07:32'),
	(148, 27.96, 2.8, 30.76, 0, NULL, NULL, '2024-12-27 13:07:57'),
	(184, 32.97, 3.3, 36.27, 1, NULL, NULL, '2024-12-29 12:27:58'),
	(185, 33.97, 3.4, 37.37, 0, NULL, NULL, '2024-12-29 12:50:21'),
	(186, 23.98, 2.4, 26.38, 0, NULL, NULL, '2024-12-29 12:55:11'),
	(187, 23.98, 2.4, 26.38, 0, NULL, NULL, '2024-12-29 13:03:02'),
	(188, 47.96, 4.8, 52.76, 0, NULL, NULL, '2024-12-29 13:12:37'),
	(189, 47.95, 4.8, 52.75, 0, NULL, NULL, '2024-12-29 13:14:44'),
	(190, 27.97, 2.8, 30.77, 1, 4, NULL, '2024-12-29 14:10:04'),
	(191, 37.96, 3.8, 41.76, 0, 4, NULL, '2024-12-29 14:15:00'),
	(192, 59.93, 5.99, 50.1, 1, 4, 2, '2024-12-29 14:20:54'),
	(193, 23.98, 2.4, 26.38, 0, NULL, NULL, '2024-12-29 14:53:45'),
	(194, 51.95, 5.2, 57.15, 0, NULL, NULL, '2024-12-29 14:59:55'),
	(195, 23.98, 2.4, 26.38, 0, NULL, NULL, '2024-12-29 16:27:30'),
	(196, 15.95, 1.6, 17.55, 0, NULL, NULL, '2024-12-29 16:37:57'),
	(197, 36.93, 3.69, 40.62, 1, NULL, NULL, '2024-12-29 16:40:39'),
	(198, 23.98, 2.4, 26.38, 1, NULL, NULL, '2024-12-29 16:43:43'),
	(199, 39.95, 4, 43.95, 1, NULL, 2, '2024-12-29 16:51:19'),
	(200, 51.91, 5.19, 57.1, 1, 4, NULL, '2024-12-29 16:52:11'),
	(201, 32.96, 3.3, 36.26, 1, 6, NULL, '2024-12-30 13:12:28'),
	(205, 23.98, 2.4, 26.38, 1, 4, NULL, '2024-12-30 13:18:33'),
	(206, 33.96, 3.4, 37.36, 1, NULL, NULL, '2024-12-30 16:05:08'),
	(209, 23.96, 2.4, 26.36, 1, 6, NULL, '2024-12-31 11:36:07'),
	(211, 15.98, 1.6, 17.58, 1, 6, NULL, '2024-12-31 11:42:41'),
	(212, 15.98, 1.6, 17.58, 0, 6, NULL, '2024-12-31 11:45:16'),
	(213, 24.96, 2.5, 27.46, 1, 6, NULL, '2024-12-31 11:45:42'),
	(214, 24.96, 2.5, 27.46, 0, 6, NULL, '2024-12-31 11:49:19'),
	(215, 11.99, 1.2, 13.19, 0, 6, NULL, '2024-12-31 11:49:37'),
	(216, 11.99, 1.2, 13.19, 1, 6, NULL, '2024-12-31 11:52:04'),
	(217, 29.95, 3, 32.95, 1, 4, NULL, '2024-12-31 11:54:33'),
	(218, 29.95, 3, 32.95, 1, 4, NULL, '2024-12-31 11:57:12'),
	(221, 11.99, 1.2, 13.19, 0, 4, NULL, '2024-12-31 11:58:54'),
	(222, 11.99, 1.2, 13.19, 0, 4, NULL, '2024-12-31 11:58:58'),
	(228, 11.99, 1.2, 13.19, 1, 4, NULL, '2024-12-31 12:06:23'),
	(229, 11.99, 1.2, 13.19, 1, 4, NULL, '2024-12-31 12:06:29'),
	(231, 33.93, 3.39, 37.32, 0, NULL, NULL, '2024-12-31 12:45:32'),
	(232, 28.98, 2.9, 31.88, 1, 4, NULL, '2024-12-31 12:57:49'),
	(234, 23.98, 2.4, 26.38, 0, NULL, NULL, '2025-01-02 11:50:48'),
	(235, 15.98, 1.6, 17.58, 0, NULL, NULL, '2025-01-02 11:54:27'),
	(236, 23.98, 2.4, 26.38, 0, NULL, NULL, '2025-01-02 11:55:45'),
	(237, 23.98, 2.4, 26.38, 0, 4, NULL, '2025-01-02 11:58:23'),
	(238, 9.98, 1, 10.98, 1, 6, NULL, '2025-01-02 11:58:36'),
	(239, 11.99, 1.2, 13.19, 1, 4, NULL, '2025-01-02 11:58:48'),
	(240, 11.99, 1.2, 13.19, 1, NULL, NULL, '2025-01-02 11:59:04'),
	(242, 11.99, 1.2, 13.19, 1, 4, NULL, '2025-01-02 13:52:36'),
	(243, 13.98, 1.4, 15.38, 1, 4, NULL, '2025-01-02 13:54:35');

CREATE TABLE IF NOT EXISTS `pedido_bebida` (
  `id_pedido` int NOT NULL,
  `id_bebida` int NOT NULL,
  KEY `id_pedido_bebida` (`id_pedido`),
  KEY `id_bebida_pedido` (`id_bebida`),
  CONSTRAINT `id_bebida_pedido` FOREIGN KEY (`id_bebida`) REFERENCES `bebidas` (`id_bebida`),
  CONSTRAINT `id_pedido_bebida` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pedido_bebida` (`id_pedido`, `id_bebida`) VALUES
	(129, 1),
	(141, 2),
	(142, 6),
	(143, 3),
	(145, 3),
	(145, 5),
	(145, 4),
	(146, 2),
	(147, 2),
	(147, 3),
	(148, 2),
	(148, 1),
	(192, 4),
	(192, 8),
	(192, 9),
	(196, 2),
	(196, 2),
	(196, 2),
	(196, 5),
	(197, 4),
	(197, 4),
	(200, 1),
	(200, 1),
	(200, 1),
	(127, 5),
	(127, 5),
	(209, 2),
	(209, 5),
	(217, 3),
	(231, 5),
	(231, 5),
	(231, 5),
	(231, 5),
	(238, 1),
	(206, 9),
	(240, 3);

CREATE TABLE IF NOT EXISTS `pedido_complemento` (
  `id_pedido` int NOT NULL,
  `id_complemento` int NOT NULL,
  KEY `id_pedido_complemento` (`id_pedido`),
  KEY `id_complemento_pedido` (`id_complemento`),
  CONSTRAINT `id_complemento_pedido` FOREIGN KEY (`id_complemento`) REFERENCES `complementos` (`id_complemento`),
  CONSTRAINT `id_pedido_complemento` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pedido_complemento` (`id_pedido`, `id_complemento`) VALUES
	(128, 4),
	(128, 1),
	(141, 3),
	(142, 4),
	(143, 2),
	(143, 2),
	(145, 5),
	(145, 4),
	(184, 3),
	(191, 3),
	(191, 2),
	(192, 3),
	(192, 3),
	(197, 2),
	(197, 1),
	(200, 2),
	(200, 2),
	(201, 1),
	(201, 2),
	(213, 2),
	(213, 1),
	(217, 1),
	(217, 1),
	(232, 3),
	(232, 3),
	(232, 3),
	(232, 4),
	(243, 2),
	(243, 3),
	(243, 1);

CREATE TABLE IF NOT EXISTS `pedido_hamburguesa` (
  `id_pedido` int NOT NULL,
  `id_hamburguesa` int NOT NULL,
  KEY `id_pedido_idx` (`id_pedido`),
  KEY `id_hamburguesa_idx` (`id_hamburguesa`),
  CONSTRAINT `id_hamburguesa_pedido` FOREIGN KEY (`id_hamburguesa`) REFERENCES `hamburguesas` (`id_hamburguesa`),
  CONSTRAINT `id_pedido_hamburguesa` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pedido_hamburguesa` (`id_pedido`, `id_hamburguesa`) VALUES
	(128, 3),
	(129, 2),
	(129, 3),
	(129, 12),
	(141, 3),
	(142, 11),
	(143, 2),
	(145, 2),
	(146, 2),
	(146, 3),
	(147, 2),
	(147, 2),
	(184, 2),
	(184, 9),
	(189, 7),
	(189, 7),
	(189, 7),
	(190, 2),
	(190, 3),
	(191, 6),
	(191, 4),
	(192, 2),
	(194, 2),
	(194, 3),
	(196, 3),
	(197, 2),
	(197, 2),
	(197, 2),
	(200, 2),
	(200, 2),
	(200, 3),
	(199, 2),
	(199, 2),
	(199, 3),
	(199, 6),
	(199, 11),
	(127, 2),
	(209, 3),
	(211, 3),
	(211, 2),
	(213, 2),
	(213, 1),
	(217, 2),
	(231, 1),
	(231, 3),
	(235, 4),
	(235, 6),
	(238, 12),
	(206, 2);

CREATE TABLE IF NOT EXISTS `pedido_menu` (
  `id_pedido` int NOT NULL,
  `id_menu` int NOT NULL,
  KEY `id_pedido_idx` (`id_pedido`),
  KEY `id_menu_idx` (`id_menu`),
  CONSTRAINT `id_menu_pedido` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`),
  CONSTRAINT `id_pedido_menu` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pedido_menu` (`id_pedido`, `id_menu`) VALUES
	(128, 5),
	(129, 4),
	(130, 3),
	(141, 1),
	(142, 4),
	(143, 4),
	(146, 1),
	(148, 1),
	(148, 5),
	(184, 2),
	(185, 5),
	(185, 5),
	(185, 7),
	(186, 3),
	(186, 4),
	(187, 2),
	(187, 5),
	(188, 1),
	(188, 1),
	(188, 1),
	(188, 2),
	(189, 1),
	(189, 2),
	(190, 1),
	(191, 1),
	(192, 1),
	(192, 1),
	(192, 1),
	(193, 4),
	(193, 4),
	(194, 1),
	(194, 1),
	(194, 5),
	(195, 1),
	(195, 4),
	(198, 4),
	(198, 3),
	(200, 4),
	(201, 1),
	(201, 1),
	(205, 1),
	(205, 1),
	(127, 3),
	(127, 3),
	(127, 3),
	(209, 4),
	(215, 2),
	(216, 1),
	(217, 1),
	(221, 1),
	(228, 4),
	(231, 7),
	(232, 7),
	(234, 1),
	(234, 2),
	(236, 1),
	(236, 2),
	(237, 1),
	(237, 2),
	(239, 1),
	(206, 1),
	(206, 2),
	(240, 1),
	(242, 2);