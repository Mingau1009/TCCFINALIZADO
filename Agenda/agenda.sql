-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 02/03/2024 às 12:27
-- Versão do servidor: 8.2.0
-- Versão do PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `celke`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `obs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int NOT NULL,
  `client_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `events`
--

INSERT INTO `events` (`id`, `title`, `color`, `start`, `end`, `obs`, `user_id`, `client_id`) VALUES
(1, 'Tutorial 1G', '#FFD700', '2024-04-01 10:00:00', '2024-04-01 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 31),
(2, 'Tutorial 2C', '#FF4500', '2024-03-04 10:00:00', '2024-03-04 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 30),
(3, 'Tutorial 3K', '#228B22', '2024-03-05 10:00:00', '2024-03-05 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 29),
(4, 'Tutorial 4C', '#A020F0', '2024-03-06 10:00:00', '2024-03-06 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 28),
(5, 'Tutorial 5', '#40E0D0', '2024-03-07 10:00:00', '2024-03-07 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 27),
(6, 'Tutorial 6C', '#0071c5', '2024-03-08 10:00:00', '2024-03-08 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 26),
(7, 'Tutorial 7', '#A020F0', '2024-04-08 10:00:00', '2024-04-08 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 25),
(8, 'Tutorial 8', '#8B0000', '2024-03-11 10:00:00', '2024-03-11 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 24),
(9, 'Tutorial 9', '#FF4500', '2024-03-13 10:00:00', '2024-03-13 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 23),
(10, 'Tutorial 10', '#228B22', '2024-03-15 10:00:00', '2024-03-15 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 2, 22),
(11, 'Tutorial 11', '#8B4513', '2024-03-18 10:00:00', '2024-03-18 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 21),
(12, 'Tutorial 12', '#FFD700', '2024-03-20 10:00:00', '2024-03-20 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 20),
(13, 'Tutorial 13', '#40E0D0', '2024-03-22 10:00:00', '2024-03-22 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 3, 19),
(14, 'Tutorial 14', '#436EEE', '2024-03-25 10:00:00', '2024-03-25 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 18),
(15, 'Tutorial 15', '#1C1C1C', '2024-03-27 10:00:00', '2024-03-27 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 17),
(16, 'Tutorial 16', '#228B22', '2024-03-29 10:00:00', '2024-03-29 11:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vitae augue eget tortor finibus laoreet ut eget dui.', 1, 16),
(17, 'Tutorial 17', '#FF4500', '2024-02-17 10:30:00', '2024-02-17 11:00:00', 'In fringilla augue eu est porta mattis.', 5, 15),
(18, 'Tutorial 18', '#0071c5', '2024-02-18 10:00:00', '2024-02-18 11:00:00', '18 Quisque rutrum, quam eget aliquet laoreet, sem metus vulputate lorem, sit amet congue ipsum.', 1, 14),
(19, 'Tutorial 21', '#228B22', '2024-02-19 10:00:00', '2024-02-19 11:00:00', 'Donec non cursus dui. Etiam eu tellus pharetra, eleifend diam et, egestas ipsum. Nam non urna eget erat suscipit dapibus. ', 2, 13),
(20, 'Tutorial 20', '#8B0000', '2024-02-20 10:00:00', '2024-02-20 11:00:00', 'Aenean venenatis aliquam leo maximus lacinia.', 1, 12),
(21, 'Tutorial 22', '#FF4500', '2024-02-21 10:00:00', '2024-02-21 11:00:00', 'Tutorial 22', 3, 11),
(22, 'Tutorial 23', '#436EEE', '2024-02-22 10:00:00', '2024-02-22 11:00:00', 'Tutorial 23', 4, 10),
(23, 'Tutorial 20', '#A020F0', '2024-02-23 10:00:00', '2024-02-23 11:00:00', 'Tutorial 20', 4, 9),
(24, 'Evento 11', '#436EEE', '2024-02-24 10:00:00', '2024-02-24 11:00:00', 'Evento 11', 1, 8),
(26, 'Evento 31', '#FFD700', '2024-04-03 10:00:00', '2024-04-03 11:00:00', 'Evento 31', 3, 7),
(27, 'Evento 20', '#A020F0', '2024-01-05 10:00:00', '2024-01-05 11:00:00', 'Evento 20', 2, 5),
(28, 'Evento 21', '#A020F0', '2024-01-07 10:00:00', '2024-01-07 11:00:00', 'Evento 21', 2, 5),
(29, 'Evento 40', '#FF4500', '2024-01-04 10:00:00', '2024-01-04 11:00:00', 'Evento 40', 4, 5),
(30, 'Evento 41', '#FF4500', '2024-01-10 10:00:00', '2024-01-10 11:00:00', 'Evento 41', 4, 5),
(31, 'Evento 45', '#FF4500', '2024-01-17 10:00:00', '2024-01-17 11:00:00', 'Evento 45', 4, 5),
(35, 'Evento 30', '#FFD700', '2024-01-08 11:00:00', '2024-01-08 12:00:00', 'Evento 30', 3, 5),
(36, 'Evento 16', '#436EEE', '2024-01-02 10:00:00', '2024-01-02 11:00:00', 'Evento 16', 1, 5),
(37, 'Evento 49', '#FF4500', '2024-01-06 10:00:00', '2024-01-06 11:00:00', 'Evento 49', 4, 5),
(38, 'Evento 38A', '#8B0000', '2024-02-26 15:00:00', '2024-02-26 16:00:00', 'Evento 38A', 6, 19),
(40, 'Evento 40A', '#A020F0', '2024-04-05 10:30:00', '2024-04-05 11:00:00', 'Evento 40A', 2, 17);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(220) NOT NULL,
  `phone` varchar(220) NOT NULL,
  `profissional` enum('S','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `profissional`) VALUES
(1, 'Cesar', '(12) 34567-8901', 'S'),
(2, 'Kelly', '(34) 23456-7890', 'S'),
(3, 'Jessica', '(21) 34567-8901', 'S'),
(4, 'Gabrielly', '(11) 45678-9012', 'S'),
(5, 'Jose', '(41) 56789-0123', 'S'),
(6, 'Ana', '(61) 67890-1234', 'S'),
(7, 'Elias', '(71) 78901-2345', 'N'),
(8, 'Ezequiel', '(81) 89012-3456', 'N'),
(9, 'Fernando', '(91) 90123-4567', 'N'),
(10, 'Fabiano', '(61) 01234-5678', 'N'),
(11, 'Eugênio', '(31) 12345-6789', 'N'),
(12, 'Juliana', '(62) 23456-7890', 'N'),
(13, 'Emanoel', '(51) 34567-8901', 'N'),
(14, 'Jackson', '(21) 45678-9012', 'N'),
(15, 'Cármen', '(11) 56789-0123', 'N'),
(16, 'Abgail', '(85) 67890-1234', 'N'),
(17, 'Thalissa', '(71) 78901-2345', 'N'),
(18, 'Madalena', '(12) 89012-3456', 'N'),
(19, 'Eduardo', '(21) 90123-4567', 'N'),
(20, 'Albano', '(19) 01234-5678', 'N'),
(21, 'Luciana', '(22) 12345-6789', 'N'),
(22, 'Gilson', '(23) 23456-7890', 'N'),
(23, 'Jerônimo', '(24) 34567-8901', 'N'),
(24, 'Isadora', '(25) 45678-9012', 'N'),
(25, 'Matias', '(26) 56789-0123', 'N'),
(26, 'Andrea', '(27) 67890-1234', 'N'),
(27, 'Breno', '(28) 78901-2345', 'N'),
(28, 'Alessandre', '(29) 89012-3456', 'N'),
(29, 'Rafaela', '(30) 90123-4567', 'N'),
(30, 'Christopher', '(31) 01234-5678', 'N'),
(31, 'Santiago', '(32) 12345-6789', 'N');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
