-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/07/2023 às 10:03
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `siasb`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbchamados`
--

CREATE TABLE `tbchamados` (
  `IDChamado` int(11) NOT NULL,
  `assunto` varchar(50) NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `dataAbertura` datetime NOT NULL,
  `status_chamado` int(11) DEFAULT NULL,
  `responsavel` int(11) DEFAULT NULL,
  `autor` int(11) DEFAULT NULL,
  `equipamento` int(11) DEFAULT NULL,
  `imagem` blob DEFAULT NULL,
  `categoria` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbchamados`
--

INSERT INTO `tbchamados` (`IDChamado`, `assunto`, `descricao`, `dataAbertura`, `status_chamado`, `responsavel`, `autor`, `equipamento`, `imagem`, `categoria`) VALUES
(1, 'chamado teste', 'aqui entraria a descricao....', '2023-06-19 09:48:57', 1, 1, 2, 100, '', '0'),
(2, 'chamado teste 2', 'aqui entraria a outra descricao....', '2023-06-20 09:48:53', 1, 1, 2, 100, '', '0'),
(3, 'chamado teste 3', 'aqui entraria a outra descricao....', '2023-06-20 09:48:49', 1, 1, 2, 100, '', '0'),
(4, 'chamado teste 4', 'aqui entraria a outra descricao....', '2023-06-20 09:48:35', 1, 1, 2, 100, '', '0'),
(5, 'chamado teste 5', 'aqui entraria a outra descricao....', '2023-06-22 12:15:00', 1, 1, 2, 100, '', '0'),
(6, 'chamado teste 6', 'impressora com defeito...', '2023-06-23 10:33:00', 1, 1, 2, 105, '', '0'),
(7, 'teste', 'workbench', '2023-07-07 16:02:45', 1, NULL, 1, 105, '', 'hardware'),
(8, 'teste', 'teste', '2023-07-07 16:49:30', 1, NULL, 1, NULL, '', 'software'),
(9, 'teste 2 ', 'interfacesdssd', '2023-07-07 17:22:13', 1, NULL, 1, 100, '', 'hardware'),
(10, 'teste hora', 'interfacesdssd', '2023-07-07 17:48:20', 1, NULL, 1, 105, '', 'hardware'),
(11, 'teste hora mais um', 'interfacesdssd', '2023-07-07 17:50:07', 1, NULL, 1, 105, '', 'hardware'),
(12, 'teste hora mais um', 'asdas', '2023-07-07 17:51:08', 1, NULL, 1, NULL, '', 'software'),
(13, 'horario', 'asdasd', '2023-07-07 12:51:53', 1, NULL, 1, NULL, '', 'software');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbchamados`
--
ALTER TABLE `tbchamados`
  ADD PRIMARY KEY (`IDChamado`),
  ADD KEY `status_chamado` (`status_chamado`),
  ADD KEY `autor` (`autor`),
  ADD KEY `equipamento` (`equipamento`),
  ADD KEY `tbchamados_ibfk_5` (`responsavel`);

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbchamados`
--
ALTER TABLE `tbchamados`
  ADD CONSTRAINT `tbchamados_ibfk_1` FOREIGN KEY (`status_chamado`) REFERENCES `tbstatus_chamado` (`IDStatus`),
  ADD CONSTRAINT `tbchamados_ibfk_3` FOREIGN KEY (`autor`) REFERENCES `tbusuario` (`IDUsuario`),
  ADD CONSTRAINT `tbchamados_ibfk_4` FOREIGN KEY (`equipamento`) REFERENCES `tbequipamentos` (`sti_ID`),
  ADD CONSTRAINT `tbchamados_ibfk_5` FOREIGN KEY (`responsavel`) REFERENCES `tbusuario` (`IDUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
