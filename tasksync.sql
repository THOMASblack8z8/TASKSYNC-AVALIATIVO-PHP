-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/05/2026 às 14:41
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
-- Banco de dados: `tasksync`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefas`
--

CREATE TABLE `tarefas` (
  `id_tarefa` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `setor` varchar(50) NOT NULL,
  `prioridade` enum('baixa','média','alta') NOT NULL,
  `status` enum('a fazer','fazendo','concluído') DEFAULT 'a fazer',
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefas`
--

INSERT INTO `tarefas` (`id_tarefa`, `id_usuario`, `descricao`, `setor`, `prioridade`, `status`, `data_cadastro`) VALUES
(1, 1, 'Desenvolver módulo de cadastro', 'TI', 'alta', 'fazendo', '2026-05-15 15:55:54'),
(2, 1, 'Criar relatório mensal de vendas', 'Comercial', 'alta', 'a fazer', '2026-05-15 15:55:54'),
(3, 1, 'Atualizar documentação do sistema', 'TI', 'baixa', 'concluído', '2026-05-15 15:55:54'),
(4, 1, '\r\nO ADM DE TUDO', 'TI', 'alta', 'a fazer', '2026-05-18 08:26:48'),
(6, 7, 'Ele que manda.\r\n', 'Financeiro', 'alta', 'fazendo', '2026-05-18 09:03:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `setor` varchar(50) NOT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `setor`, `data_cadastro`) VALUES
(1, 'Admin TaskSync', 'admin@tasksync.com', 'TI', '2026-05-15 15:55:54'),
(2, 'Maria Silva', 'maria.silva@tasksync.com', 'Comercial', '2026-05-18 08:51:44'),
(3, 'João Santos', 'joao.santos@tasksync.com', 'TI', '2026-05-18 08:51:44'),
(4, 'Ana Oliveira', 'ana.oliveira@tasksync.com', 'Marketing', '2026-05-18 08:51:44'),
(5, 'Carlos Souza', 'carlos.souza@tasksync.com', 'RH', '2026-05-18 08:51:44'),
(6, 'Fernanda Lima', 'fernanda.lima@tasksync.com', 'Financeiro', '2026-05-18 08:51:44'),
(7, 'THOMASblack8z8', 'thomas@email.com', 'Financeiro', '2026-05-18 08:57:04');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tarefas`
--
ALTER TABLE `tarefas`
  ADD PRIMARY KEY (`id_tarefa`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tarefas`
--
ALTER TABLE `tarefas`
  MODIFY `id_tarefa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tarefas`
--
ALTER TABLE `tarefas`
  ADD CONSTRAINT `tarefas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
