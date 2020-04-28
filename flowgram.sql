-- --------------------------------------------------------
-- Servidor:                     localhost
-- Versão do servidor:           5.7.24 - MySQL Community Server (GPL)
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              9.5.0.5332
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para flowgram
CREATE DATABASE IF NOT EXISTS `flowgram` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `flowgram`;

-- Copiando estrutura para tabela flowgram.chats
CREATE TABLE IF NOT EXISTS `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario1` varchar(32) NOT NULL,
  `usuario2` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela flowgram.mensagens
CREATE TABLE IF NOT EXISTS `mensagens` (
  `id` varchar(32) NOT NULL,
  `msg` json NOT NULL,
  `remetente` varchar(32) NOT NULL,
  `destinatario` varchar(32) NOT NULL,
  `privacidade` json NOT NULL,
  `hora` datetime NOT NULL,
  `nonce` varchar(200) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
-- Copiando estrutura para tabela flowgram.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_user` varchar(32) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `username` varchar(32) NOT NULL,
  `ultimo_acesso` datetime NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `chave_publica` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
