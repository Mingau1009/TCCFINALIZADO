-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/06/2025 às 05:57
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `heman`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `cpf` char(11) NOT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `frequencia` int(11) DEFAULT NULL,
  `objetivo` varchar(255) DEFAULT NULL,
  `data_matricula` date DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `criar_aula`
--

CREATE TABLE `criar_aula` (
  `id` int(11) NOT NULL,
  `nome_aula` varchar(100) NOT NULL,
  `dia_aula` varchar(100) NOT NULL,
  `horario_aula` time NOT NULL,
  `professor_aula` varchar(100) NOT NULL,
  `local_aula` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dieta`
--

CREATE TABLE `dieta` (
  `id` int(11) NOT NULL,
  `nome_aluno` varchar(50) NOT NULL,
  `dia_refeicao` varchar(50) NOT NULL,
  `tipo_refeicao` varchar(50) NOT NULL,
  `horario_refeicao` time NOT NULL,
  `descricao` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento_aluno`
--

CREATE TABLE `evento_aluno` (
  `id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(220) NOT NULL,
  `color` varchar(45) NOT NULL,
  `start` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `exercicio`
--

CREATE TABLE `exercicio` (
  `nome` varchar(255) NOT NULL,
  `tipo_exercicio` varchar(255) NOT NULL,
  `grupo_muscular` varchar(255) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `exercicio`
--

INSERT INTO `exercicio` (`nome`, `tipo_exercicio`, `grupo_muscular`, `id`) VALUES
('Corrida', 'MUSCULAÇÃO', 'ABDÔMEN', 11),
('Abdominal', 'MUSCULAÇÃO', 'ABDÔMEN', 12),
('Campe', 'MUSCULAÇÃO', 'CARDIO', 13),
('Terra', 'CARDIO', 'ABDÔMEN', 14),
('ASD', 'CARDIO', 'CARDIO', 15),
('Sumo', 'MUSCULAÇÃO', 'CARDIO', 16),
('Corrida', 'CARDIO', 'ABDÔMEN', 17);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ficha`
--

CREATE TABLE `ficha` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `dia_treino` varchar(335) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ficha_exercicio`
--

CREATE TABLE `ficha_exercicio` (
  `id` int(11) NOT NULL,
  `ficha_id` int(11) DEFAULT NULL,
  `exercicio_id` int(11) DEFAULT NULL,
  `num_series` int(11) DEFAULT NULL,
  `num_repeticoes` int(11) DEFAULT NULL,
  `tempo_descanso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `turno_disponivel` varchar(255) DEFAULT NULL,
  `data_matricula` date NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `cpf` varchar(11) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario_email` varchar(255) DEFAULT NULL,
  `acao` varchar(255) NOT NULL,
  `tabela_afetada` varchar(100) DEFAULT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `dados_anteriores` longtext DEFAULT NULL,
  `dados_novos` longtext DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `data_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf_unico` (`cpf`) USING BTREE;

--
-- Índices de tabela `criar_aula`
--
ALTER TABLE `criar_aula`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `dieta`
--
ALTER TABLE `dieta`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `evento_aluno`
--
ALTER TABLE `evento_aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evento_id` (`evento_id`),
  ADD KEY `aluno_id` (`aluno_id`);

--
-- Índices de tabela `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `exercicio`
--
ALTER TABLE `exercicio`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ficha`
--
ALTER TABLE `ficha`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ficha_exercicio`
--
ALTER TABLE `ficha_exercicio`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf_unico_funcionario` (`cpf`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `criar_aula`
--
ALTER TABLE `criar_aula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `dieta`
--
ALTER TABLE `dieta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `evento_aluno`
--
ALTER TABLE `evento_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de tabela `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de tabela `exercicio`
--
ALTER TABLE `exercicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `ficha`
--
ALTER TABLE `ficha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `ficha_exercicio`
--
ALTER TABLE `ficha_exercicio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `evento_aluno`
--
ALTER TABLE `evento_aluno`
  ADD CONSTRAINT `evento_aluno_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `criar_aula` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evento_aluno_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
