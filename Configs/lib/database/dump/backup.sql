-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: siasb
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `siasb`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `siasb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `siasb`;

--
-- Table structure for table `tbadministrador`
--

DROP TABLE IF EXISTS `tbadministrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbadministrador` (
  `IDAdministrador` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `senha` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`IDAdministrador`),
  CONSTRAINT `tbadministrador_ibfk_1` FOREIGN KEY (`IDAdministrador`) REFERENCES `tbpessoa` (`IDPessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbadministrador`
--

LOCK TABLES `tbadministrador` WRITE;
/*!40000 ALTER TABLE `tbadministrador` DISABLE KEYS */;
INSERT INTO `tbadministrador` VALUES (1,'ROOT','TOOR');
/*!40000 ALTER TABLE `tbadministrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbchamados`
--

DROP TABLE IF EXISTS `tbchamados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbchamados` (
  `IDChamado` int(11) NOT NULL,
  `assunto` varchar(50) NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `dataAbertura` datetime NOT NULL,
  `status_chamado` int(11) DEFAULT NULL,
  `responsavel` int(11) DEFAULT NULL,
  `autor` int(11) DEFAULT NULL,
  `equipamento` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDChamado`),
  KEY `status_chamado` (`status_chamado`),
  KEY `responsavel` (`responsavel`),
  KEY `autor` (`autor`),
  KEY `equipamento` (`equipamento`),
  CONSTRAINT `tbchamados_ibfk_1` FOREIGN KEY (`status_chamado`) REFERENCES `tbstatus_chamado` (`IDStatus`),
  CONSTRAINT `tbchamados_ibfk_2` FOREIGN KEY (`responsavel`) REFERENCES `tbadministrador` (`IDAdministrador`),
  CONSTRAINT `tbchamados_ibfk_3` FOREIGN KEY (`autor`) REFERENCES `tbusuario` (`IDUsuario`),
  CONSTRAINT `tbchamados_ibfk_4` FOREIGN KEY (`equipamento`) REFERENCES `tbequipamentos` (`sti_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbchamados`
--

LOCK TABLES `tbchamados` WRITE;
/*!40000 ALTER TABLE `tbchamados` DISABLE KEYS */;
INSERT INTO `tbchamados` VALUES (1,'chamado teste','aqui entraria a descricao....','0000-00-00 00:00:00',1,1,2,100),(2,'chamado teste 2','aqui entraria a outra descricao....','0000-00-00 00:00:00',1,1,2,100),(3,'chamado teste 3','aqui entraria a outra descricao....','0000-00-00 00:00:00',1,1,2,100),(4,'chamado teste 4','aqui entraria a outra descricao....','0000-00-00 00:00:00',1,1,2,100),(5,'chamado teste 5','aqui entraria a outra descricao....','2023-06-22 12:15:00',1,1,2,100),(6,'chamado teste 6','impressora com defeito...','2023-06-23 10:33:00',1,1,2,105);
/*!40000 ALTER TABLE `tbchamados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbequipamentos`
--

DROP TABLE IF EXISTS `tbequipamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbequipamentos` (
  `sti_ID` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `setor` int(11) DEFAULT NULL,
  `secao` int(11) DEFAULT NULL,
  PRIMARY KEY (`sti_ID`),
  KEY `tipo` (`tipo`),
  KEY `usuario` (`usuario`),
  KEY `setor` (`setor`),
  KEY `secao` (`secao`),
  CONSTRAINT `tbequipamentos_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `tbtipo_equipamentos` (`IDTipo`),
  CONSTRAINT `tbequipamentos_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `tbpessoa` (`IDPessoa`),
  CONSTRAINT `tbequipamentos_ibfk_3` FOREIGN KEY (`setor`) REFERENCES `tbsetor` (`IDSetor`),
  CONSTRAINT `tbequipamentos_ibfk_4` FOREIGN KEY (`secao`) REFERENCES `tbsecao` (`IDSecao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbequipamentos`
--

LOCK TABLES `tbequipamentos` WRITE;
/*!40000 ALTER TABLE `tbequipamentos` DISABLE KEYS */;
INSERT INTO `tbequipamentos` VALUES (100,'eqpm. teste',NULL,1,2,1,1),(105,'Impressora teste',NULL,1,2,1,1);
/*!40000 ALTER TABLE `tbequipamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbligacaochamados_log`
--

DROP TABLE IF EXISTS `tbligacaochamados_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbligacaochamados_log` (
  `IDChamado` int(11) DEFAULT NULL,
  `CodChamado` int(11) DEFAULT NULL,
  KEY `IDChamado` (`IDChamado`),
  KEY `CodChamado` (`CodChamado`),
  CONSTRAINT `tbligacaochamados_log_ibfk_1` FOREIGN KEY (`IDChamado`) REFERENCES `tbchamados` (`IDChamado`),
  CONSTRAINT `tbligacaochamados_log_ibfk_2` FOREIGN KEY (`CodChamado`) REFERENCES `tblog_chamado` (`referencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbligacaochamados_log`
--

LOCK TABLES `tbligacaochamados_log` WRITE;
/*!40000 ALTER TABLE `tbligacaochamados_log` DISABLE KEYS */;
INSERT INTO `tbligacaochamados_log` VALUES (1,1),(1,1),(6,6);
/*!40000 ALTER TABLE `tbligacaochamados_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblog_chamado`
--

DROP TABLE IF EXISTS `tblog_chamado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblog_chamado` (
  `IDLog` int(11) NOT NULL,
  `mensagem` varchar(200) DEFAULT NULL,
  `dataAlteracao` datetime DEFAULT NULL,
  `responsavel` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `referencia` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDLog`),
  KEY `status` (`status`),
  KEY `responsavel` (`responsavel`),
  KEY `referencia` (`referencia`),
  CONSTRAINT `tblog_chamado_ibfk_1` FOREIGN KEY (`status`) REFERENCES `tbstatus_chamado` (`IDStatus`),
  CONSTRAINT `tblog_chamado_ibfk_2` FOREIGN KEY (`responsavel`) REFERENCES `tbadministrador` (`IDAdministrador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblog_chamado`
--

LOCK TABLES `tblog_chamado` WRITE;
/*!40000 ALTER TABLE `tblog_chamado` DISABLE KEYS */;
INSERT INTO `tblog_chamado` VALUES (2,'resolvendo','2023-06-22 12:15:00',1,1,1),(3,'fechado','2023-06-22 12:15:00',1,4,1),(4,'resolvendo','2023-06-23 10:36:00',1,1,6),(5,'resolvido','2023-06-23 10:45:00',1,4,6);
/*!40000 ALTER TABLE `tblog_chamado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbpessoa`
--

DROP TABLE IF EXISTS `tbpessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbpessoa` (
  `IDPessoa` int(11) NOT NULL AUTO_INCREMENT,
  `nomeCompleto` varchar(100) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `matricula` varchar(45) DEFAULT NULL,
  `setor` int(11) DEFAULT NULL,
  `secao` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gestor` bit(1) DEFAULT NULL,
  PRIMARY KEY (`IDPessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbpessoa`
--

LOCK TABLES `tbpessoa` WRITE;
/*!40000 ALTER TABLE `tbpessoa` DISABLE KEYS */;
INSERT INTO `tbpessoa` VALUES (1,'root','12345678900','9999',1,1,'root@test.com',''),(2,'user','12345678900','9999',1,1,'user@test.com','\0'),(3,'Gabriel','72828','7272',1,1,'teste@teste.com',''),(16,'Another','882828','882',2,1,'a@b.com','\0'),(17,'Lucas','777272727','88228',1,1,'a@b.com.br','\0');
/*!40000 ALTER TABLE `tbpessoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbsecao`
--

DROP TABLE IF EXISTS `tbsecao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbsecao` (
  `IDSecao` int(11) NOT NULL,
  `descricao_secao` varchar(45) DEFAULT NULL,
  `gerente` int(11) DEFAULT NULL,
  `setor` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDSecao`),
  KEY `setor` (`setor`),
  CONSTRAINT `tbsecao_ibfk_1` FOREIGN KEY (`IDSecao`) REFERENCES `tbpessoa` (`IDPessoa`),
  CONSTRAINT `tbsecao_ibfk_2` FOREIGN KEY (`setor`) REFERENCES `tbsetor` (`IDSetor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbsecao`
--

LOCK TABLES `tbsecao` WRITE;
/*!40000 ALTER TABLE `tbsecao` DISABLE KEYS */;
INSERT INTO `tbsecao` VALUES (1,'T.I',1,1),(2,'Teste',3,2),(3,'Teste outro',3,2);
/*!40000 ALTER TABLE `tbsecao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbsetor`
--

DROP TABLE IF EXISTS `tbsetor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbsetor` (
  `IDSetor` int(11) NOT NULL,
  `descricao_setor` varchar(45) DEFAULT NULL,
  `gerente` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDSetor`),
  KEY `tbsetor_ibfk_1` (`gerente`),
  CONSTRAINT `tbsetor_ibfk_1` FOREIGN KEY (`gerente`) REFERENCES `tbpessoa` (`IDPessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbsetor`
--

LOCK TABLES `tbsetor` WRITE;
/*!40000 ALTER TABLE `tbsetor` DISABLE KEYS */;
INSERT INTO `tbsetor` VALUES (1,'Tecnologia',1),(2,'Teste',3);
/*!40000 ALTER TABLE `tbsetor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbstatus_chamado`
--

DROP TABLE IF EXISTS `tbstatus_chamado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbstatus_chamado` (
  `IDStatus` int(11) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`IDStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbstatus_chamado`
--

LOCK TABLES `tbstatus_chamado` WRITE;
/*!40000 ALTER TABLE `tbstatus_chamado` DISABLE KEYS */;
INSERT INTO `tbstatus_chamado` VALUES (1,'Aberto'),(2,'Andamento'),(3,'Pendente'),(4,'Fechado');
/*!40000 ALTER TABLE `tbstatus_chamado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbtipo_equipamentos`
--

DROP TABLE IF EXISTS `tbtipo_equipamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbtipo_equipamentos` (
  `IDTipo` int(11) NOT NULL,
  `descricao` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`IDTipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbtipo_equipamentos`
--

LOCK TABLES `tbtipo_equipamentos` WRITE;
/*!40000 ALTER TABLE `tbtipo_equipamentos` DISABLE KEYS */;
INSERT INTO `tbtipo_equipamentos` VALUES (1,'teste');
/*!40000 ALTER TABLE `tbtipo_equipamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbusuario`
--

DROP TABLE IF EXISTS `tbusuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbusuario` (
  `IDUsuario` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`IDUsuario`),
  CONSTRAINT `tbusuario_ibfk_1` FOREIGN KEY (`IDUsuario`) REFERENCES `tbpessoa` (`IDPessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbusuario`
--

LOCK TABLES `tbusuario` WRITE;
/*!40000 ALTER TABLE `tbusuario` DISABLE KEYS */;
INSERT INTO `tbusuario` VALUES (2,'USER','123');
/*!40000 ALTER TABLE `tbusuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-04  8:57:49
