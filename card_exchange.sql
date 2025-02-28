-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-02-2025 a las 10:55:43
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
-- Base de datos: `card_exchange`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rarity` enum('common','rare','epic','legendary') NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cards`
--

INSERT INTO `cards` (`id`, `name`, `rarity`, `image_url`) VALUES
(1, 'Charizard', 'rare', '../assets/img/card4.jpg'),
(2, 'Tinkaton', 'common', '../assets/img/card1.jpg'),
(3, 'Flareon', 'legendary', '../assets/img/card2.jpg'),
(4, 'Toxicroak', 'epic', '../assets/img/card3.jpg'),
(5, 'Lapras', 'common', '../assets/img/card5.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_promo`
--

CREATE TABLE `codigos_promo` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `codigos_promo`
--

INSERT INTO `codigos_promo` (`id`, `code`, `created_at`) VALUES
(1, 'new-code', '2025-02-28 07:53:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marketplace`
--

CREATE TABLE `marketplace` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_sold` tinyint(1) DEFAULT 0,
  `listed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marketplace`
--

INSERT INTO `marketplace` (`id`, `seller_id`, `card_id`, `price`, `is_sold`, `listed_at`, `notified`) VALUES
(14, 1, 3, 500.00, 1, '2025-02-28 08:50:16', 0),
(16, 1, 2, 20.00, 1, '2025-02-28 08:50:26', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `balance`) VALUES
(1, 'Manuel', '$2y$10$cyArmfUgfJz0Hdo37FUoDOFOpM9yWBXn5oN9dKZPsiXvSvexwz/em', '2025-02-19 07:01:06', 30.00),
(2, 'Pepe', '$2y$10$m94R7z4foyJNtwz9FE2H/eOQBGaYlTysLaRadd8RPPedWLlDpsQ..', '2025-02-19 07:30:10', 0.00),
(3, 'Aitor', '$2y$10$y7JkaXm8VWJ1YBjAH3/ZoOk7FbQwtNep0UAs6uCYY9r.mXE4b2Vua', '2025-02-20 07:43:15', 0.00),
(4, 'Antonio', '$2y$10$Zrj8sBjzcl.ZweOYMjUwcemGxkZgWgqdJu/GkV.9Ui8qfUfNaeb4y', '2025-02-24 07:08:27', 0.00),
(6, 'Admin', '$2y$10$Q8LMvcOvXnp4qkse1VK4M.WNSo1SLyXvsNoaUfftk18FJXjnmW40W', '2025-02-24 07:37:39', 40.00),
(9, 'paco', '$2y$10$329bTrmqNjSufHfg0TxZ4uLBlgnobe9RwR6/p8qT7xPGQx1TLjYrK', '2025-02-27 13:00:12', 0.00),
(10, 'N4STR09', '$2y$10$/1nCFSL.D/4Goh4WNWBh1u7Rwo00COZ1Z5TpucRywbQcMJS78jIai', '2025-02-27 18:07:23', 0.00),
(11, 'marcos', '$2y$10$nbdZxpFwvM2Miv/PirdbZ.Clg.I3sUhWYBXchW3.CTAKhf3EEmxcK', '2025-02-27 18:53:34', 0.00),
(12, 'prueba1', '$2y$10$vLYZJ8OxlIA4KeXlkpJbTOqUxOudFvfd6CL80bcwepKz9h8ZGw4xa', '2025-02-28 07:53:53', 910.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_cards`
--

CREATE TABLE `user_cards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `obtained_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_cards`
--

INSERT INTO `user_cards` (`id`, `user_id`, `card_id`, `obtained_at`) VALUES
(2, 2, 1, '2025-02-24 07:33:46'),
(3, 4, 1, '2025-02-24 07:36:24'),
(10, 2, 1, '2025-02-27 08:57:02'),
(19, 6, 1, '2025-02-27 12:27:38'),
(20, 4, 5, '2025-02-27 12:33:46'),
(21, 4, 1, '2025-02-27 12:33:46'),
(23, 4, 2, '2025-02-27 12:33:46'),
(24, 4, 4, '2025-02-27 12:33:46'),
(25, 6, 5, '2025-02-27 12:43:33'),
(26, 6, 3, '2025-02-27 12:51:53'),
(27, 6, 1, '2025-02-27 12:55:06'),
(28, 9, 2, '2025-02-27 13:00:46'),
(29, 9, 1, '2025-02-27 13:00:46'),
(30, 9, 5, '2025-02-27 13:00:46'),
(31, 9, 3, '2025-02-27 13:00:46'),
(32, 9, 4, '2025-02-27 13:00:46'),
(33, 10, 1, '2025-02-27 18:14:26'),
(34, 10, 5, '2025-02-27 18:14:26'),
(35, 10, 4, '2025-02-27 18:14:26'),
(36, 10, 2, '2025-02-27 18:14:26'),
(37, 10, 3, '2025-02-27 18:14:26'),
(38, 11, 4, '2025-02-27 18:54:56'),
(39, 11, 2, '2025-02-27 18:54:56'),
(40, 11, 5, '2025-02-27 18:54:56'),
(41, 11, 3, '2025-02-27 18:54:56'),
(42, 11, 1, '2025-02-27 18:54:56'),
(46, 12, 3, '2025-02-28 08:11:11'),
(47, 12, 2, '2025-02-28 08:11:11'),
(49, 1, 4, '2025-02-28 08:37:04'),
(50, 1, 5, '2025-02-28 08:37:04'),
(51, 1, 1, '2025-02-28 08:37:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_progress`
--

CREATE TABLE `user_progress` (
  `user_id` int(11) NOT NULL,
  `progress` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_progress`
--

INSERT INTO `user_progress` (`user_id`, `progress`) VALUES
(1, 5),
(6, 15),
(9, 10),
(10, 10),
(11, 5),
(12, 5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `codigos_promo`
--
ALTER TABLE `codigos_promo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `marketplace`
--
ALTER TABLE `marketplace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `card_id` (`card_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `user_cards`
--
ALTER TABLE `user_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `card_id` (`card_id`);

--
-- Indices de la tabla `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `codigos_promo`
--
ALTER TABLE `codigos_promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `marketplace`
--
ALTER TABLE `marketplace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `user_cards`
--
ALTER TABLE `user_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `marketplace`
--
ALTER TABLE `marketplace`
  ADD CONSTRAINT `marketplace_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `marketplace_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_cards`
--
ALTER TABLE `user_cards`
  ADD CONSTRAINT `user_cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cards_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
