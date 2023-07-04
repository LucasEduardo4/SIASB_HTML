-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/07/2023 às 14:09
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
-- Estrutura para tabela `tbadministrador`
--

CREATE TABLE `tbadministrador` (
  `IDAdministrador` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `senha` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbadministrador`
--

INSERT INTO `tbadministrador` (`IDAdministrador`, `nome`, `senha`) VALUES
(1, 'ROOT', 'TOOR');

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
  `equipamento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbchamados`
--

INSERT INTO `tbchamados` (`IDChamado`, `assunto`, `descricao`, `dataAbertura`, `status_chamado`, `responsavel`, `autor`, `equipamento`) VALUES
(1, 'chamado teste', 'aqui entraria a descricao....', '0000-00-00 00:00:00', 1, 1, 2, 100),
(2, 'chamado teste 2', 'aqui entraria a outra descricao....', '0000-00-00 00:00:00', 1, 1, 2, 100),
(3, 'chamado teste 3', 'aqui entraria a outra descricao....', '0000-00-00 00:00:00', 1, 1, 2, 100),
(4, 'chamado teste 4', 'aqui entraria a outra descricao....', '0000-00-00 00:00:00', 1, 1, 2, 100),
(5, 'chamado teste 5', 'aqui entraria a outra descricao....', '2023-06-22 12:15:00', 1, 1, 2, 100),
(6, 'chamado teste 6', 'impressora com defeito...', '2023-06-23 10:33:00', 1, 1, 2, 105);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbequipamentos`
--

CREATE TABLE `tbequipamentos` (
  `sti_ID` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `setor` int(11) DEFAULT NULL,
  `secao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbequipamentos`
--

INSERT INTO `tbequipamentos` (`sti_ID`, `descricao`, `ip`, `tipo`, `usuario`, `setor`, `secao`) VALUES
(100, 'eqpm. teste', NULL, 1, 2, 1, 1),
(105, 'Impressora teste', NULL, 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbligacaochamados_log`
--

CREATE TABLE `tbligacaochamados_log` (
  `IDChamado` int(11) DEFAULT NULL,
  `CodChamado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbligacaochamados_log`
--

INSERT INTO `tbligacaochamados_log` (`IDChamado`, `CodChamado`) VALUES
(1, 1),
(1, 1),
(6, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblog_chamado`
--

CREATE TABLE `tblog_chamado` (
  `IDLog` int(11) NOT NULL,
  `mensagem` varchar(200) DEFAULT NULL,
  `dataAlteracao` datetime DEFAULT NULL,
  `responsavel` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `referencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tblog_chamado`
--

INSERT INTO `tblog_chamado` (`IDLog`, `mensagem`, `dataAlteracao`, `responsavel`, `status`, `referencia`) VALUES
(2, 'resolvendo', '2023-06-22 12:15:00', 1, 1, 1),
(3, 'fechado', '2023-06-22 12:15:00', 1, 4, 1),
(4, 'resolvendo', '2023-06-23 10:36:00', 1, 1, 6),
(5, 'resolvido', '2023-06-23 10:45:00', 1, 4, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbpessoa`
--

CREATE TABLE `tbpessoa` (
  `IDPessoa` int(11) NOT NULL,
  `nomeCompleto` varchar(100) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `matricula` varchar(45) DEFAULT NULL,
  `setor` int(11) DEFAULT NULL,
  `secao` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gestor` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbpessoa`
--

INSERT INTO `tbpessoa` (`IDPessoa`, `nomeCompleto`, `cpf`, `matricula`, `setor`, `secao`, `email`, `gestor`) VALUES
(1, 'root', '12345678900', '9999', 1, 1, 'root@test.com', b'1'),
(2, 'user', '12345678900', '9999', 1, 1, 'user@test.com', b'0'),
(3, 'Gabriel', '72828', '7272', 1, 1, 'teste@teste.com', b'1'),
(16, 'Another', '882828', '882', 2, 1, 'a@b.com', b'0'),
(17, 'Lucas', '777272727', '88228', 1, 1, 'a@b.com.br', b'0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsecao`
--

CREATE TABLE `tbsecao` (
  `IDSecao` int(11) NOT NULL,
  `descricao_secao` varchar(45) DEFAULT NULL,
  `gerente` int(11) DEFAULT NULL,
  `setor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbsecao`
--

INSERT INTO `tbsecao` (`IDSecao`, `descricao_secao`, `gerente`, `setor`) VALUES
(1, 'T.I', 1, 1),
(2, 'Teste', 3, 2),
(3, 'Teste outro', 3, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbsetor`
--

CREATE TABLE `tbsetor` (
  `IDSetor` int(11) NOT NULL,
  `descricao_setor` varchar(45) DEFAULT NULL,
  `gerente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbsetor`
--

INSERT INTO `tbsetor` (`IDSetor`, `descricao_setor`, `gerente`) VALUES
(1, 'Tecnologia', 1),
(2, 'Teste', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbstatus_chamado`
--

CREATE TABLE `tbstatus_chamado` (
  `IDStatus` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbstatus_chamado`
--

INSERT INTO `tbstatus_chamado` (`IDStatus`, `descricao`) VALUES
(1, 'Aberto'),
(2, 'Andamento'),
(3, 'Pendente'),
(4, 'Fechado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbtipo_equipamentos`
--

CREATE TABLE `tbtipo_equipamentos` (
  `IDTipo` int(11) NOT NULL,
  `descricao` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbtipo_equipamentos`
--

INSERT INTO `tbtipo_equipamentos` (`IDTipo`, `descricao`) VALUES
(1, 'teste');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbusuario`
--

CREATE TABLE `tbusuario` (
  `IDUsuario` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbusuario`
--

INSERT INTO `tbusuario` (`IDUsuario`, `nome`, `senha`) VALUES
(2, 'USER', '123');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbadministrador`
--
ALTER TABLE `tbadministrador`
  ADD PRIMARY KEY (`IDAdministrador`);

--
-- Índices de tabela `tbchamados`
--
ALTER TABLE `tbchamados`
  ADD PRIMARY KEY (`IDChamado`),
  ADD KEY `status_chamado` (`status_chamado`),
  ADD KEY `responsavel` (`responsavel`),
  ADD KEY `autor` (`autor`),
  ADD KEY `equipamento` (`equipamento`);

--
-- Índices de tabela `tbequipamentos`
--
ALTER TABLE `tbequipamentos`
  ADD PRIMARY KEY (`sti_ID`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `setor` (`setor`),
  ADD KEY `secao` (`secao`);

--
-- Índices de tabela `tbligacaochamados_log`
--
ALTER TABLE `tbligacaochamados_log`
  ADD KEY `IDChamado` (`IDChamado`),
  ADD KEY `CodChamado` (`CodChamado`);

--
-- Índices de tabela `tblog_chamado`
--
ALTER TABLE `tblog_chamado`
  ADD PRIMARY KEY (`IDLog`),
  ADD KEY `status` (`status`),
  ADD KEY `responsavel` (`responsavel`),
  ADD KEY `referencia` (`referencia`);

--
-- Índices de tabela `tbpessoa`
--
ALTER TABLE `tbpessoa`
  ADD PRIMARY KEY (`IDPessoa`);

--
-- Índices de tabela `tbsecao`
--
ALTER TABLE `tbsecao`
  ADD PRIMARY KEY (`IDSecao`),
  ADD KEY `setor` (`setor`);

--
-- Índices de tabela `tbsetor`
--
ALTER TABLE `tbsetor`
  ADD PRIMARY KEY (`IDSetor`),
  ADD KEY `tbsetor_ibfk_1` (`gerente`);

--
-- Índices de tabela `tbstatus_chamado`
--
ALTER TABLE `tbstatus_chamado`
  ADD PRIMARY KEY (`IDStatus`);

--
-- Índices de tabela `tbtipo_equipamentos`
--
ALTER TABLE `tbtipo_equipamentos`
  ADD PRIMARY KEY (`IDTipo`);

--
-- Índices de tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`IDUsuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbpessoa`
--
ALTER TABLE `tbpessoa`
  MODIFY `IDPessoa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbadministrador`
--
ALTER TABLE `tbadministrador`
  ADD CONSTRAINT `tbadministrador_ibfk_1` FOREIGN KEY (`IDAdministrador`) REFERENCES `tbpessoa` (`IDPessoa`);

--
-- Restrições para tabelas `tbchamados`
--
ALTER TABLE `tbchamados`
  ADD CONSTRAINT `tbchamados_ibfk_1` FOREIGN KEY (`status_chamado`) REFERENCES `tbstatus_chamado` (`IDStatus`),
  ADD CONSTRAINT `tbchamados_ibfk_2` FOREIGN KEY (`responsavel`) REFERENCES `tbadministrador` (`IDAdministrador`),
  ADD CONSTRAINT `tbchamados_ibfk_3` FOREIGN KEY (`autor`) REFERENCES `tbusuario` (`IDUsuario`),
  ADD CONSTRAINT `tbchamados_ibfk_4` FOREIGN KEY (`equipamento`) REFERENCES `tbequipamentos` (`sti_ID`);

--
-- Restrições para tabelas `tbequipamentos`
--
ALTER TABLE `tbequipamentos`
  ADD CONSTRAINT `tbequipamentos_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `tbtipo_equipamentos` (`IDTipo`),
  ADD CONSTRAINT `tbequipamentos_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `tbpessoa` (`IDPessoa`),
  ADD CONSTRAINT `tbequipamentos_ibfk_3` FOREIGN KEY (`setor`) REFERENCES `tbsetor` (`IDSetor`),
  ADD CONSTRAINT `tbequipamentos_ibfk_4` FOREIGN KEY (`secao`) REFERENCES `tbsecao` (`IDSecao`);

--
-- Restrições para tabelas `tbligacaochamados_log`
--
ALTER TABLE `tbligacaochamados_log`
  ADD CONSTRAINT `tbligacaochamados_log_ibfk_1` FOREIGN KEY (`IDChamado`) REFERENCES `tbchamados` (`IDChamado`),
  ADD CONSTRAINT `tbligacaochamados_log_ibfk_2` FOREIGN KEY (`CodChamado`) REFERENCES `tblog_chamado` (`referencia`);

--
-- Restrições para tabelas `tblog_chamado`
--
ALTER TABLE `tblog_chamado`
  ADD CONSTRAINT `tblog_chamado_ibfk_1` FOREIGN KEY (`status`) REFERENCES `tbstatus_chamado` (`IDStatus`),
  ADD CONSTRAINT `tblog_chamado_ibfk_2` FOREIGN KEY (`responsavel`) REFERENCES `tbadministrador` (`IDAdministrador`);

--
-- Restrições para tabelas `tbsecao`
--
ALTER TABLE `tbsecao`
  ADD CONSTRAINT `tbsecao_ibfk_1` FOREIGN KEY (`IDSecao`) REFERENCES `tbpessoa` (`IDPessoa`),
  ADD CONSTRAINT `tbsecao_ibfk_2` FOREIGN KEY (`setor`) REFERENCES `tbsetor` (`IDSetor`);

--
-- Restrições para tabelas `tbsetor`
--
ALTER TABLE `tbsetor`
  ADD CONSTRAINT `tbsetor_ibfk_1` FOREIGN KEY (`gerente`) REFERENCES `tbpessoa` (`IDPessoa`);

--
-- Restrições para tabelas `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD CONSTRAINT `tbusuario_ibfk_1` FOREIGN KEY (`IDUsuario`) REFERENCES `tbpessoa` (`IDPessoa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
