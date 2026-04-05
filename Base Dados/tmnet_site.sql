-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 03/04/2026 às 11:52
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
-- Banco de dados: `tmnet_site`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_access_levels`
--

DROP TABLE IF EXISTS `adms_access_levels`;
CREATE TABLE IF NOT EXISTS `adms_access_levels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_levels` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_access_levels`
--

INSERT INTO `adms_access_levels` (`id`, `name`, `order_levels`, `created`, `modified`) VALUES
(1, 'Super Administrado', 1, '2022-05-23 15:09:18', '2022-05-23 19:06:44'),
(2, 'Administrador', 2, '2022-05-23 15:09:18', '2023-08-14 10:53:03'),
(4, 'Administrador Clube', 4, '2022-05-23 19:34:35', '2026-03-16 17:18:30'),
(7, 'Suporte Técnico', 3, '2023-07-27 11:36:31', '2026-03-16 17:18:31'),
(12, 'Auxiliar Clube', 5, '2024-01-02 05:29:38', '2026-03-16 17:15:27'),
(14, 'Atleta', 7, '2025-02-22 09:23:46', '2026-03-16 17:14:22'),
(15, 'Arbitro', 8, '2026-03-27 11:05:04', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_atletas`
--

DROP TABLE IF EXISTS `adms_atletas`;
CREATE TABLE IF NOT EXISTS `adms_atletas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `nome` varchar(220) NOT NULL,
  `apelido` varchar(50) DEFAULT NULL,
  `imagem` varchar(220) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `estilo_jogo` varchar(50) DEFAULT NULL,
  `mao_dominante` varchar(20) DEFAULT NULL,
  `pontuacao_ranking` int DEFAULT '0',
  `adms_sit_id` int DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_atletas`
--

INSERT INTO `adms_atletas` (`id`, `empresa_id`, `nome`, `apelido`, `imagem`, `data_nascimento`, `estilo_jogo`, `mao_dominante`, `pontuacao_ranking`, `adms_sit_id`, `created`, `modified`) VALUES
(1, 331, 'DANIEL DE OLIVEIRA CANEDO', 'D. Canedo', 'atleta_1_1773429085.jpg', '1961-03-08', 'Caneteiro', 'Destro', 30, 1, '0000-00-00 00:00:00', '2026-03-16 19:51:29'),
(2, 331, 'Fabio Castanheira', 'Fabio C.', NULL, '1962-01-01', 'Classista', 'Canhoto', 10, 1, '2026-03-13 14:26:17', '2026-03-16 14:18:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_categorias`
--

DROP TABLE IF EXISTS `adms_categorias`;
CREATE TABLE IF NOT EXISTS `adms_categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ex: Sub-15, Veterano 40+',
  `idade_minima` int DEFAULT NULL COMMENT 'Idade mínima em anos',
  `idade_maxima` int DEFAULT NULL COMMENT 'Idade máxima em anos',
  `pontuacao_minima` int DEFAULT NULL COMMENT 'Pontuação mínima no ranking',
  `pontuacao_maxima` int DEFAULT NULL COMMENT 'Pontuação máxima no ranking',
  `empresa_id` int NOT NULL COMMENT 'ID do Clube/Empresa',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_categorias`
--

INSERT INTO `adms_categorias` (`id`, `nome`, `idade_minima`, `idade_maxima`, `pontuacao_minima`, `pontuacao_maxima`, `empresa_id`, `created`, `modified`) VALUES
(4, 'Iniciante', NULL, NULL, 0, 249, 331, '2026-04-01 13:07:19', NULL),
(5, 'Quarta Divisão', NULL, NULL, 250, 499, 331, '2026-04-01 13:08:03', '2026-04-01 13:08:44'),
(6, 'Terceira Divisão', NULL, NULL, 500, 749, 331, '2026-04-01 13:08:33', NULL),
(7, 'Segunda Divisão', NULL, NULL, 750, 999, 331, '2026-04-01 13:09:07', NULL),
(8, 'Primeira Divisão', NULL, NULL, 1000, NULL, 331, '2026-04-01 13:09:44', NULL),
(9, 'Pre Infantil', NULL, 10, NULL, NULL, 331, '2026-04-02 08:44:21', NULL),
(10, 'Infantil', NULL, 13, NULL, NULL, 331, '2026-04-02 08:44:55', NULL),
(11, 'Juvenil', NULL, 18, NULL, NULL, 331, '2026-04-02 08:45:39', NULL),
(12, 'Adulto', NULL, NULL, NULL, NULL, 331, '2026-04-02 08:46:02', '2026-04-02 08:50:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_colors`
--

DROP TABLE IF EXISTS `adms_colors`;
CREATE TABLE IF NOT EXISTS `adms_colors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(44) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(44) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_colors`
--

INSERT INTO `adms_colors` (`id`, `name`, `color`, `created`, `modified`) VALUES
(1, 'Azul', '#0275d8', '2022-03-23 15:26:59', NULL),
(2, 'Cinza', '#868e95', '2022-03-23 15:26:59', NULL),
(3, 'Verde', '#5cb85c', '2022-03-23 15:26:59', NULL),
(4, 'Vermelho', '#d9534f', '2022-03-23 15:26:59', '2024-02-20 13:27:44'),
(5, 'Laranjado', '#f0ad4e', '2022-03-23 15:26:59', NULL),
(6, 'Azul Claro', '#17a2b8', '2022-03-23 15:26:59', NULL),
(7, 'Cinza Claro', '#343a40', '2022-03-23 15:26:59', NULL),
(8, 'Branco', '#f8f9fa', '2022-03-23 15:26:59', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_competicoes`
--

DROP TABLE IF EXISTS `adms_competicoes`;
CREATE TABLE IF NOT EXISTS `adms_competicoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `nome_torneio` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `categorias_selecionadas` varchar(255) DEFAULT NULL COMMENT 'IDs das categorias (idades/ratings) ativas nesta competição',
  `data_evento` date NOT NULL,
  `horario_inicio` time DEFAULT '08:00:00',
  `local_evento` varchar(220) DEFAULT NULL,
  `observacoes` text NOT NULL,
  `status_inscricao` int NOT NULL DEFAULT '1',
  `sistema_disputa` int NOT NULL DEFAULT '1' COMMENT '1=Grupos e Mata-Mata, 2=Todos contra Todos',
  `tipo_competicao` int NOT NULL DEFAULT '1' COMMENT '1=Livre, 2=Por Divisão',
  `tipo_genero` int NOT NULL DEFAULT '1' COMMENT '1=Misto, 2=Separado',
  `modified` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `categoria_cbtm` varchar(100) DEFAULT 'TMB Estadual',
  `fator_multiplicador` decimal(3,2) DEFAULT '1.00',
  `status_id` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_competicoes`
--

INSERT INTO `adms_competicoes` (`id`, `empresa_id`, `nome_torneio`, `categorias_selecionadas`, `data_evento`, `horario_inicio`, `local_evento`, `observacoes`, `status_inscricao`, `sistema_disputa`, `tipo_competicao`, `tipo_genero`, `modified`, `created`, `categoria_cbtm`, `fator_multiplicador`, `status_id`) VALUES
(40, 331, 'Pio Challenge 2026', '4,8,5,7,6', '2026-04-01', '08:00:00', 'AAbb', 'teste', 0, 1, 1, 2, '2026-04-01 17:41:45', '2026-04-01 14:00:36', 'TMB Estadual', 1.00, 1),
(41, 331, 'Pio Challenger', '8', '2026-04-01', '08:00:00', 'aabb', 'teste', 0, 1, 1, 1, '2026-04-01 18:42:02', '2026-04-01 18:37:49', 'TMB Estadual', 1.00, 1),
(42, 331, 'Canedo - Berto 2026', '8,7', '2026-04-02', '08:00:00', 'AABB', 'teste', 0, 1, 1, 1, '2026-04-02 08:58:00', '2026-04-02 08:56:22', 'TMB Estadual', 1.00, 1),
(43, 331, 'Torneio Aberto Canedo 2026', '12,10,11', '2026-04-03', '08:00:00', 'AABB - Serrinha - Ba', 'Teste', 1, 1, 1, 1, NULL, '2026-04-03 09:38:24', 'TMB Estadual', 1.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_divisoes`
--

DROP TABLE IF EXISTS `adms_divisoes`;
CREATE TABLE IF NOT EXISTS `adms_divisoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `pontuacao_min` int NOT NULL DEFAULT '0',
  `pontuacao_max` int NOT NULL DEFAULT '99999',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_divisoes`
--

INSERT INTO `adms_divisoes` (`id`, `empresa_id`, `nome`, `pontuacao_min`, `pontuacao_max`, `created`, `modified`) VALUES
(1, 331, 'Primeira Divisão', 1000, 99999, '2026-03-24 08:49:55', NULL),
(2, 331, 'Segunda Divisão', 500, 999, '2026-03-24 08:49:55', NULL),
(3, 331, 'Terceira Divisão', 250, 499, '2026-03-24 08:52:01', NULL),
(4, 331, 'Quarta Divisão', 0, 249, '2026-03-24 08:52:31', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_empresa`
--

DROP TABLE IF EXISTS `adms_empresa`;
CREATE TABLE IF NOT EXISTS `adms_empresa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `razao_social` varchar(220) NOT NULL,
  `nome_fantasia` varchar(220) DEFAULT NULL,
  `cnpj` varchar(220) NOT NULL,
  `cep` int DEFAULT NULL,
  `logradouro` varchar(220) DEFAULT NULL,
  `bairro` varchar(220) DEFAULT NULL,
  `cidade` varchar(220) DEFAULT NULL,
  `uf` varchar(45) DEFAULT NULL,
  `situacao` int NOT NULL DEFAULT '1',
  `contrato` int NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `situacao` (`situacao`)
) ENGINE=InnoDB AUTO_INCREMENT=321 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_empresa`
--

INSERT INTO `adms_empresa` (`id`, `razao_social`, `nome_fantasia`, `cnpj`, `cep`, `logradouro`, `bairro`, `cidade`, `uf`, `situacao`, `contrato`, `created`, `modified`) VALUES
(1, 'DANIEL DE OLIVEIRA CANEDO EIRELI', 'REPBRASIL', '33080008/0001-64', 42723070, 'RUA DOIS DE JULHO, 2', 'AREIA BRANCA', 'LAURO DE FREITAS ', 'BA', 1, 1, '2023-08-21 14:41:25', '2025-02-19 17:55:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_emp_principal`
--

DROP TABLE IF EXISTS `adms_emp_principal`;
CREATE TABLE IF NOT EXISTS `adms_emp_principal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `razao_social` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nome_fantasia` varchar(220) DEFAULT NULL,
  `cnpj` varchar(220) NOT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `logradouro` varchar(220) DEFAULT NULL,
  `bairro` varchar(220) DEFAULT NULL,
  `cidade` varchar(220) DEFAULT NULL,
  `uf` varchar(45) DEFAULT NULL,
  `contato` varchar(220) DEFAULT NULL,
  `telefone` varchar(220) DEFAULT NULL,
  `email` varchar(220) DEFAULT NULL,
  `situacao` int NOT NULL DEFAULT '1',
  `logo` varchar(110) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `base_dados` varchar(220) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `situacao` (`situacao`)
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_emp_principal`
--

INSERT INTO `adms_emp_principal` (`id`, `razao_social`, `nome_fantasia`, `cnpj`, `cep`, `logradouro`, `bairro`, `cidade`, `uf`, `contato`, `telefone`, `email`, `situacao`, `logo`, `base_dados`, `created`, `modified`) VALUES
(1, 'EMPRESA PRINCIPAL', 'EMP PRINCIPAL', '49.173.405/0001-10', '42723070', 'Rua 2 de Julho, 2245', 'Areia Branca', 'Lauro de Freitas', 'BA', 'Contato', 'telefone', 'email@email.com', 1, 'logo.png', NULL, '2025-02-12 08:12:15', '2025-06-03 08:42:01'),
(331, 'Escola de Tenis de Mesa Angelo Neto', 'ETPAN', '49173405/0001-10', '41650020', 'Rua dep. Paulo Jackson,869', 'Piatã', 'SALVADOR', 'BA', 'Angelo Neto', '71999353004', 'etpan@gmail.com', 1, 'logo_etpam.png', NULL, '2025-06-04 13:25:31', '2026-03-31 05:32:02');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_feriados`
--

DROP TABLE IF EXISTS `adms_feriados`;
CREATE TABLE IF NOT EXISTS `adms_feriados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_feriado` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_feriados`
--

INSERT INTO `adms_feriados` (`id`, `empresa_id`, `nome`, `data_feriado`, `created`, `modified`) VALUES
(1, 1, 'Confraternização Universal', '2025-01-01', '2026-01-20 10:41:49', NULL),
(2, 1, 'Tiradentes', '2025-04-21', '2026-01-20 10:41:49', NULL),
(3, 1, 'Dia do Trabalho', '2025-05-01', '2026-01-20 10:41:49', NULL),
(4, 1, 'Independência do Brasil', '2025-09-07', '2026-01-20 10:41:49', NULL),
(5, 1, 'Nossa Sr.a Aparecida', '2025-10-12', '2026-01-20 10:41:49', NULL),
(6, 1, 'Finados', '2025-11-02', '2026-01-20 10:41:49', NULL),
(7, 1, 'Proclamação da República', '2025-11-15', '2026-01-20 10:41:49', NULL),
(8, 1, 'Natal', '2025-12-25', '2026-01-20 10:41:49', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_groups_pgs`
--

DROP TABLE IF EXISTS `adms_groups_pgs`;
CREATE TABLE IF NOT EXISTS `adms_groups_pgs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_group_pg` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_groups_pgs`
--

INSERT INTO `adms_groups_pgs` (`id`, `name`, `order_group_pg`, `created`, `modified`) VALUES
(1, 'Listar', 1, '2022-05-24 23:53:27', '2023-07-13 11:54:50'),
(2, 'Visualizar', 2, '2022-05-23 00:00:00', '2023-07-13 11:54:45'),
(3, 'Cadastrar', 3, '2022-05-23 00:00:00', '2023-07-13 11:54:38'),
(4, 'Editar', 4, '2022-05-23 00:00:00', '2023-07-13 11:55:17'),
(5, 'Apagar', 5, '2022-05-23 00:00:00', '2023-07-13 11:55:09'),
(6, 'Alterar Ordem', 6, '2022-05-23 00:00:00', '2023-07-13 11:45:28'),
(7, 'Acesso', 7, '2022-05-23 00:00:00', '2023-07-18 10:34:11'),
(8, 'Outras Páginas', 8, '2022-05-23 00:00:00', '2023-07-18 10:56:00'),
(9, 'Gerar Pdf', 9, '2025-01-11 08:52:12', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_info_login`
--

DROP TABLE IF EXISTS `adms_info_login`;
CREATE TABLE IF NOT EXISTS `adms_info_login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `logo` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `texto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tit_aviso` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `aviso` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empresa_id` (`empresa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `adms_info_login`
--

INSERT INTO `adms_info_login` (`id`, `empresa_id`, `logo`, `name`, `texto`, `tit_aviso`, `aviso`, `modified`) VALUES
(1, 331, NULL, 'INFORMAÇÕES IMPORTANTE', 'O horário de funcionamento do Suporte Técnico da Rep Brasil  e de segunda a sexta das 08:00 as 17:00 hs. e aos sábados das 08:00 as 12:00 hs.', '', '', '2026-02-28 07:06:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_inscricoes`
--

DROP TABLE IF EXISTS `adms_inscricoes`;
CREATE TABLE IF NOT EXISTS `adms_inscricoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adms_competicao_id` int NOT NULL,
  `adms_user_id` int NOT NULL,
  `grupo` varchar(5) DEFAULT NULL,
  `adms_categoria_id` int DEFAULT NULL COMMENT 'Categoria escolhida',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=646 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_inscricoes`
--

INSERT INTO `adms_inscricoes` (`id`, `adms_competicao_id`, `adms_user_id`, `grupo`, `adms_categoria_id`, `created`) VALUES
(604, 40, 466, NULL, NULL, '2026-04-01 14:16:30'),
(605, 40, 472, 'M-A', 7, '2026-04-01 14:39:21'),
(606, 40, 466, 'M-A', 4, '2026-04-01 14:40:03'),
(607, 40, 473, 'M-A', 7, '2026-04-01 17:28:42'),
(608, 40, 491, 'F-A', 7, '2026-04-01 17:29:06'),
(609, 40, 493, 'F-A', 4, '2026-04-01 17:29:19'),
(610, 40, 466, 'M-A', 5, '2026-04-01 17:30:02'),
(612, 40, 480, 'M-A', 5, '2026-04-01 17:30:24'),
(614, 40, 480, 'M-A', 4, '2026-04-01 17:40:21'),
(615, 40, 467, 'M-A', 4, '2026-04-01 17:40:31'),
(616, 40, 465, 'F-A', 4, '2026-04-01 17:40:55'),
(617, 40, 486, 'F-A', 7, '2026-04-01 17:41:20'),
(618, 40, 489, 'F-A', 7, '2026-04-01 17:41:40'),
(619, 41, 476, 'D', 8, '2026-04-01 18:38:04'),
(620, 41, 478, 'B', 8, '2026-04-01 18:38:10'),
(621, 41, 483, 'B', 8, '2026-04-01 18:38:46'),
(622, 41, 484, 'D', 8, '2026-04-01 18:38:53'),
(623, 41, 479, 'C', 8, '2026-04-01 18:38:58'),
(624, 41, 481, 'D', 8, '2026-04-01 18:39:13'),
(625, 41, 477, 'B', 8, '2026-04-01 18:39:25'),
(626, 41, 482, 'A', 8, '2026-04-01 18:39:38'),
(627, 41, 486, 'A', 8, '2026-04-01 18:40:45'),
(628, 41, 493, 'C', 8, '2026-04-01 18:40:54'),
(629, 41, 480, 'C', 8, '2026-04-01 18:41:00'),
(630, 41, 469, 'B', 8, '2026-04-01 18:41:08'),
(631, 41, 471, 'A', 8, '2026-04-01 18:41:20'),
(632, 41, 474, 'A', 8, '2026-04-01 18:41:26'),
(633, 41, 487, 'D', 8, '2026-04-01 18:41:37'),
(634, 41, 468, 'C', 8, '2026-04-01 18:41:47'),
(635, 42, 472, 'A', 8, '2026-04-02 08:57:05'),
(636, 42, 476, 'A', 8, '2026-04-02 08:57:16'),
(637, 42, 480, 'B', 8, '2026-04-02 08:57:41'),
(638, 42, 483, 'B', 8, '2026-04-02 08:57:50'),
(639, 43, 496, NULL, 10, '2026-04-03 10:57:40'),
(640, 43, 496, NULL, 11, '2026-04-03 10:57:40'),
(641, 43, 500, NULL, 10, '2026-04-03 10:59:20'),
(642, 43, 500, NULL, 11, '2026-04-03 10:59:20'),
(643, 43, 493, NULL, 12, '2026-04-03 11:00:17'),
(644, 43, 501, NULL, 11, '2026-04-03 11:28:34'),
(645, 43, 501, NULL, 12, '2026-04-03 11:28:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_items_menus`
--

DROP TABLE IF EXISTS `adms_items_menus`;
CREATE TABLE IF NOT EXISTS `adms_items_menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_item_menu` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_items_menus`
--

INSERT INTO `adms_items_menus` (`id`, `name`, `icon`, `order_item_menu`, `created`, `modified`) VALUES
(1, 'Default', 'fa-solid fa-arrows-rotate', 2, '2022-06-23 19:26:41', '2023-08-22 09:25:03'),
(2, 'Dashboard', 'fa-solid fa-house', 1, '2022-06-23 19:26:41', '2023-08-22 09:25:03'),
(3, 'Usuários', 'fa-solid fa-user', 11, '2022-06-23 15:11:17', '2025-08-19 14:29:12'),
(4, 'Configurações', 'fa-solid fa-gears', 13, '2022-06-23 15:12:08', '2025-09-28 04:31:35'),
(5, 'Perfil', 'fa-solid fa-user', 14, '2022-06-23 20:10:24', '2025-09-28 04:31:28'),
(6, 'Sair', 'fa-solid fa-right-from-bracket', 19, '2022-06-23 15:12:08', '2026-01-16 03:06:08'),
(11, 'Páginas', 'fa-solid fa-file', 15, '2023-08-11 11:20:17', '2025-09-28 04:31:22'),
(12, 'Clubes', 'fa-solid fa-city', 7, '2023-08-21 10:25:03', '2026-03-20 13:06:59'),
(18, 'Relatórios', 'fa-solid fa-chart-simple', 17, '2025-01-09 08:32:19', '2025-09-28 04:31:12'),
(19, 'Cadastros', 'fa-solid fa-building', 6, '2025-02-17 08:39:37', '2025-08-12 09:19:17'),
(20, 'Mensagens', 'fa-solid fa-envelope', 16, '2025-07-02 09:26:09', '2025-09-28 04:31:17'),
(23, 'Chat', 'fa-solid fa-comments', 18, '2026-01-16 03:05:01', '2026-01-16 03:06:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_levels_forms`
--

DROP TABLE IF EXISTS `adms_levels_forms`;
CREATE TABLE IF NOT EXISTS `adms_levels_forms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adms_access_level_id` int NOT NULL,
  `adms_sits_user_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adms_access_level_id` (`adms_access_level_id`),
  KEY `adms_sits_users_id` (`adms_sits_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_levels_forms`
--

INSERT INTO `adms_levels_forms` (`id`, `adms_access_level_id`, `adms_sits_user_id`, `created`, `modified`) VALUES
(1, 4, 1, '2023-08-06 11:07:53', '2023-09-03 10:38:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_levels_pages`
--

DROP TABLE IF EXISTS `adms_levels_pages`;
CREATE TABLE IF NOT EXISTS `adms_levels_pages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permission` int NOT NULL DEFAULT '2',
  `order_level_page` int NOT NULL,
  `print_menu` int NOT NULL DEFAULT '2' COMMENT '1 - Apresentar no menu, 2 - Não apresentar no menu',
  `dropdown` int NOT NULL DEFAULT '2' COMMENT '1 - Dropdow , 2 - não dropdow',
  `adms_access_level_id` int NOT NULL,
  `adms_page_id` int NOT NULL,
  `adms_items_menu_id` int NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_adms_levels_pages_adms_access_levels1_idx` (`adms_access_level_id`),
  KEY `fk_adms_levels_pages_adms_pages1_idx` (`adms_page_id`),
  KEY `adms_items_menu_id` (`adms_items_menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2673 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_levels_pages`
--

INSERT INTO `adms_levels_pages` (`id`, `permission`, `order_level_page`, `print_menu`, `dropdown`, `adms_access_level_id`, `adms_page_id`, `adms_items_menu_id`, `created`, `modified`) VALUES
(1, 1, 1, 1, 2, 4, 1, 1, '2022-05-23 12:28:10', '2023-08-06 11:52:52'),
(2, 1, 2, 1, 1, 4, 2, 3, '2022-05-23 12:28:10', '2023-09-15 09:17:51'),
(3, 2, 8, 1, 1, 4, 18, 4, '2022-05-23 12:29:21', '2025-04-01 04:02:01'),
(4, 1, 4, 1, 2, 1, 1, 2, '2022-05-23 13:10:15', '2023-08-12 20:28:45'),
(5, 1, 5, 1, 2, 2, 1, 1, '2022-05-23 13:10:15', '2023-08-05 10:48:50'),
(6, 1, 5, 1, 1, 1, 2, 3, '2023-07-23 11:55:52', NULL),
(7, 1, 6, 2, 2, 1, 3, 1, '2023-07-23 11:55:52', '2023-08-04 11:54:19'),
(8, 1, 7, 1, 1, 1, 4, 3, '2023-07-23 11:55:52', '2025-02-17 08:46:43'),
(9, 1, 8, 2, 2, 1, 5, 1, '2023-07-23 11:55:52', NULL),
(10, 1, 9, 2, 2, 1, 6, 1, '2023-07-23 11:55:52', NULL),
(11, 1, 10, 2, 2, 1, 7, 1, '2023-07-23 11:55:52', NULL),
(12, 1, 11, 2, 2, 1, 8, 1, '2023-07-23 11:55:52', NULL),
(13, 1, 12, 2, 2, 1, 9, 1, '2023-07-23 11:55:52', NULL),
(14, 1, 13, 2, 2, 1, 10, 1, '2023-07-23 11:55:52', NULL),
(15, 1, 14, 2, 2, 1, 11, 1, '2023-07-23 11:55:52', NULL),
(16, 1, 15, 2, 2, 1, 12, 1, '2023-07-23 11:55:52', NULL),
(17, 1, 16, 1, 1, 1, 13, 3, '2023-07-23 11:55:53', NULL),
(18, 1, 17, 2, 2, 1, 14, 1, '2023-07-23 11:55:53', NULL),
(19, 1, 18, 2, 2, 1, 15, 1, '2023-07-23 11:55:53', NULL),
(20, 1, 19, 2, 2, 1, 16, 1, '2023-07-23 11:55:53', '2023-08-03 11:19:49'),
(21, 1, 20, 2, 2, 1, 17, 1, '2023-07-23 11:55:53', NULL),
(22, 1, 21, 1, 1, 1, 18, 4, '2023-07-23 11:55:53', '2023-08-03 11:10:15'),
(23, 1, 22, 2, 2, 1, 19, 4, '2023-07-23 11:55:53', NULL),
(24, 1, 23, 2, 2, 1, 20, 1, '2023-07-23 11:55:53', NULL),
(25, 1, 24, 2, 2, 1, 21, 1, '2023-07-23 11:55:53', NULL),
(26, 1, 25, 2, 2, 1, 22, 1, '2023-07-23 11:55:53', NULL),
(27, 1, 27, 1, 1, 1, 23, 4, '2023-07-23 11:55:53', '2023-08-05 10:57:02'),
(28, 1, 28, 2, 2, 1, 24, 1, '2023-07-23 11:55:53', '2023-08-05 10:56:50'),
(29, 1, 29, 2, 2, 1, 25, 1, '2023-07-23 11:55:53', '2023-08-05 10:56:32'),
(30, 1, 30, 2, 2, 1, 26, 1, '2023-07-23 11:55:53', '2023-08-05 10:56:17'),
(31, 1, 31, 2, 2, 1, 27, 1, '2023-07-23 11:55:53', '2023-08-05 10:55:50'),
(32, 1, 32, 2, 2, 1, 28, 1, '2023-07-23 11:55:53', '2023-08-05 10:54:51'),
(33, 1, 26, 1, 1, 1, 29, 3, '2023-07-23 11:55:53', '2023-08-12 12:49:16'),
(34, 1, 33, 2, 2, 1, 30, 1, '2023-07-23 11:55:53', NULL),
(35, 1, 34, 2, 2, 1, 31, 1, '2023-07-23 11:55:53', NULL),
(36, 1, 35, 2, 2, 1, 32, 1, '2023-07-23 11:55:53', NULL),
(37, 1, 36, 2, 2, 1, 33, 1, '2023-07-23 11:55:53', NULL),
(38, 1, 37, 2, 2, 1, 34, 1, '2023-07-23 11:55:53', NULL),
(39, 1, 38, 2, 2, 1, 35, 1, '2023-07-23 11:55:53', NULL),
(40, 1, 39, 2, 2, 1, 36, 1, '2023-07-23 11:55:53', NULL),
(41, 1, 40, 1, 1, 1, 37, 11, '2023-07-23 11:55:53', '2023-08-03 11:20:27'),
(42, 1, 41, 2, 2, 1, 38, 1, '2023-07-23 11:55:53', NULL),
(43, 1, 42, 2, 2, 1, 39, 1, '2023-07-23 11:55:53', NULL),
(44, 1, 43, 2, 2, 1, 40, 1, '2023-07-23 11:55:53', NULL),
(45, 1, 44, 2, 2, 1, 41, 1, '2023-07-23 11:55:53', NULL),
(46, 1, 45, 2, 2, 1, 42, 1, '2023-07-23 11:55:53', NULL),
(47, 1, 46, 1, 1, 1, 43, 11, '2023-07-23 11:55:53', '2023-08-03 11:59:56'),
(48, 1, 47, 2, 2, 1, 44, 1, '2023-07-23 11:55:53', NULL),
(49, 1, 48, 2, 2, 1, 45, 1, '2023-07-23 11:55:53', NULL),
(50, 1, 49, 2, 2, 1, 46, 1, '2023-07-23 11:55:53', NULL),
(51, 1, 50, 2, 2, 1, 47, 1, '2023-07-23 11:55:53', NULL),
(52, 1, 51, 1, 1, 1, 48, 11, '2023-07-23 11:55:53', '2023-08-03 11:20:55'),
(53, 1, 52, 2, 2, 1, 49, 1, '2023-07-23 11:55:53', NULL),
(54, 1, 53, 2, 2, 1, 50, 1, '2023-07-23 11:55:53', NULL),
(55, 1, 54, 2, 2, 1, 51, 1, '2023-07-23 11:55:53', NULL),
(56, 1, 55, 2, 2, 1, 52, 1, '2023-07-23 11:55:53', NULL),
(57, 1, 57, 2, 2, 1, 53, 1, '2023-07-23 11:55:53', '2023-08-07 10:40:03'),
(58, 1, 56, 1, 1, 1, 54, 11, '2023-07-23 11:55:53', '2023-08-07 10:40:03'),
(59, 1, 58, 2, 2, 1, 55, 1, '2023-07-23 11:55:53', NULL),
(60, 1, 59, 2, 2, 1, 56, 1, '2023-07-23 11:55:53', NULL),
(61, 1, 60, 2, 2, 1, 57, 1, '2023-07-23 11:55:53', NULL),
(62, 1, 61, 2, 2, 1, 58, 1, '2023-07-23 11:55:53', NULL),
(63, 1, 65, 2, 2, 1, 59, 1, '2023-07-23 11:55:53', '2023-08-08 17:04:58'),
(64, 1, 64, 1, 2, 1, 60, 6, '2023-07-23 11:55:53', '2023-08-08 17:05:06'),
(65, 1, 66, 2, 2, 1, 61, 1, '2023-07-23 11:55:53', '2023-08-08 17:04:51'),
(66, 1, 67, 2, 2, 1, 62, 1, '2023-07-23 11:55:53', '2023-08-08 17:04:44'),
(67, 1, 68, 2, 2, 1, 63, 1, '2023-07-23 11:55:53', '2023-08-08 17:04:37'),
(68, 1, 69, 2, 2, 1, 64, 1, '2023-07-23 11:55:53', '2023-08-08 17:04:31'),
(69, 1, 70, 2, 2, 1, 65, 1, '2023-07-23 11:55:53', '2023-08-08 17:04:23'),
(70, 1, 71, 2, 2, 1, 66, 1, '2023-07-23 11:55:53', '2023-08-08 17:04:15'),
(71, 1, 6, 1, 1, 2, 2, 3, '2023-07-23 11:55:53', '2023-08-12 20:31:10'),
(72, 1, 7, 2, 2, 2, 3, 1, '2023-07-23 11:55:53', '2023-07-28 11:57:12'),
(73, 1, 8, 2, 2, 2, 4, 1, '2023-07-23 11:55:53', '2023-07-28 11:51:18'),
(74, 1, 9, 2, 2, 2, 5, 1, '2023-07-23 11:55:53', '2023-07-28 12:13:04'),
(75, 1, 10, 2, 2, 2, 6, 1, '2023-07-23 11:55:53', '2023-07-28 12:13:09'),
(76, 1, 11, 2, 2, 2, 7, 1, '2023-07-23 11:55:53', '2023-07-30 11:27:52'),
(77, 1, 12, 2, 2, 2, 8, 1, '2023-07-23 11:55:53', '2023-09-18 13:18:51'),
(78, 1, 13, 2, 2, 2, 9, 1, '2023-07-23 11:55:53', '2023-08-02 11:59:36'),
(79, 1, 14, 2, 2, 2, 10, 1, '2023-07-23 11:55:53', '2023-07-27 11:30:20'),
(80, 1, 15, 2, 2, 2, 11, 1, '2023-07-23 11:55:53', '2023-07-27 11:30:22'),
(81, 1, 16, 2, 2, 2, 12, 1, '2023-07-23 11:55:53', '2023-08-05 10:50:50'),
(82, 1, 17, 1, 1, 2, 13, 3, '2023-07-23 11:55:53', '2023-08-12 20:49:15'),
(83, 1, 18, 2, 2, 2, 14, 1, '2023-07-23 11:55:53', '2023-07-30 11:40:08'),
(84, 1, 19, 2, 2, 2, 15, 1, '2023-07-23 11:55:53', '2023-07-29 11:12:39'),
(85, 1, 20, 2, 2, 2, 16, 1, '2023-07-23 11:55:53', '2023-07-28 16:05:09'),
(86, 1, 21, 2, 2, 2, 17, 1, '2023-07-23 11:55:53', '2023-09-18 13:18:57'),
(87, 1, 22, 1, 1, 2, 18, 4, '2023-07-23 11:55:53', '2023-08-12 20:50:17'),
(88, 1, 23, 2, 2, 2, 19, 1, '2023-07-23 11:55:53', '2023-07-30 11:27:39'),
(89, 1, 24, 2, 2, 2, 20, 1, '2023-07-23 11:55:53', '2023-07-30 11:27:44'),
(90, 1, 25, 2, 2, 2, 21, 1, '2023-07-23 11:55:53', '2023-07-30 11:28:06'),
(91, 1, 26, 2, 2, 2, 22, 1, '2023-07-23 11:55:53', '2023-09-18 13:19:08'),
(92, 2, 27, 1, 1, 2, 23, 4, '2023-07-23 11:55:53', '2023-09-13 05:49:16'),
(93, 2, 28, 2, 2, 2, 24, 1, '2023-07-23 11:55:53', '2023-09-13 05:49:30'),
(94, 2, 29, 2, 2, 2, 25, 1, '2023-07-23 11:55:53', '2023-09-13 05:49:36'),
(95, 2, 30, 2, 2, 2, 26, 1, '2023-07-23 11:55:53', '2023-09-13 05:49:40'),
(96, 2, 31, 2, 2, 2, 27, 1, '2023-07-23 11:55:53', '2023-07-31 10:50:20'),
(97, 2, 32, 2, 2, 2, 28, 1, '2023-07-23 11:55:53', '2023-07-30 11:39:45'),
(98, 1, 33, 1, 1, 2, 29, 3, '2023-07-23 11:55:53', '2023-08-12 20:50:50'),
(99, 1, 34, 2, 2, 2, 30, 1, '2023-07-23 11:55:53', '2023-07-27 11:31:32'),
(100, 2, 35, 2, 2, 2, 31, 1, '2023-07-23 11:55:53', '2023-07-29 11:22:22'),
(101, 2, 36, 2, 2, 2, 32, 1, '2023-07-23 11:55:53', '2023-09-25 06:28:38'),
(102, 2, 37, 2, 2, 2, 33, 1, '2023-07-23 11:55:53', '2023-07-29 11:26:21'),
(103, 1, 38, 2, 2, 2, 34, 1, '2023-07-23 11:55:53', '2023-07-27 11:31:42'),
(104, 1, 39, 2, 2, 2, 35, 1, '2023-07-23 11:55:53', '2023-07-27 11:31:44'),
(105, 2, 40, 2, 2, 2, 36, 1, '2023-07-23 11:55:53', '2023-07-29 11:26:47'),
(106, 2, 41, 1, 1, 2, 37, 11, '2023-07-23 11:55:53', '2023-09-13 05:50:50'),
(107, 2, 42, 2, 2, 2, 38, 1, '2023-07-23 11:55:53', '2023-09-13 05:50:41'),
(108, 2, 43, 2, 2, 2, 39, 1, '2023-07-23 11:55:53', '2023-07-29 11:46:20'),
(109, 2, 44, 2, 2, 2, 40, 1, '2023-07-23 11:55:53', '2023-07-29 14:47:03'),
(110, 2, 45, 2, 2, 2, 41, 1, '2023-07-23 11:55:53', '2023-07-29 14:54:54'),
(111, 2, 46, 2, 2, 2, 42, 1, '2023-07-23 11:55:54', '2023-09-14 16:46:04'),
(112, 2, 47, 2, 2, 2, 43, 11, '2023-07-23 11:55:54', '2023-09-14 16:46:14'),
(113, 2, 48, 2, 2, 2, 44, 1, '2023-07-23 11:55:54', '2023-09-14 16:46:08'),
(114, 2, 49, 2, 2, 2, 45, 1, '2023-07-23 11:55:54', '2023-07-29 11:51:01'),
(115, 2, 50, 2, 2, 2, 46, 1, '2023-07-23 11:55:54', '2023-09-14 16:46:18'),
(116, 2, 51, 2, 2, 2, 47, 1, '2023-07-23 11:55:54', '2023-07-29 11:51:06'),
(117, 2, 52, 2, 2, 2, 48, 11, '2023-07-23 11:55:54', '2023-09-14 16:46:41'),
(118, 2, 53, 2, 2, 2, 49, 1, '2023-07-23 11:55:54', '2023-09-14 16:46:53'),
(119, 2, 54, 2, 2, 2, 50, 1, '2023-07-23 11:55:54', '2023-09-14 16:46:57'),
(120, 2, 55, 2, 2, 2, 51, 1, '2023-07-23 11:55:54', '2023-09-14 16:47:01'),
(121, 2, 56, 2, 2, 2, 52, 1, '2023-07-23 11:55:54', '2023-07-29 12:06:49'),
(122, 2, 57, 2, 2, 2, 53, 1, '2023-07-23 11:55:54', '2023-09-14 16:46:29'),
(123, 2, 58, 2, 2, 2, 54, 11, '2023-07-23 11:55:54', '2023-09-14 16:46:37'),
(124, 2, 59, 2, 2, 2, 55, 1, '2023-07-23 11:55:54', '2023-09-14 16:46:47'),
(125, 2, 60, 2, 2, 2, 56, 1, '2023-07-23 11:55:54', '2023-09-14 16:47:06'),
(126, 2, 61, 2, 2, 2, 57, 1, '2023-07-23 11:55:54', '2023-09-14 16:47:11'),
(127, 2, 62, 2, 2, 2, 58, 1, '2023-07-23 11:55:54', '2023-09-14 16:47:15'),
(128, 1, 63, 2, 2, 2, 59, 1, '2023-07-23 11:55:54', NULL),
(129, 1, 64, 1, 2, 2, 60, 6, '2023-07-23 11:55:54', '2023-08-12 20:31:46'),
(130, 1, 65, 2, 2, 2, 61, 1, '2023-07-23 11:55:54', NULL),
(131, 1, 66, 2, 2, 2, 62, 1, '2023-07-23 11:55:54', NULL),
(132, 1, 67, 2, 2, 2, 63, 1, '2023-07-23 11:55:54', NULL),
(133, 1, 68, 2, 2, 2, 64, 1, '2023-07-23 11:55:54', NULL),
(134, 1, 69, 2, 2, 2, 65, 1, '2023-07-23 11:55:54', NULL),
(135, 2, 70, 2, 2, 2, 66, 1, '2023-07-23 11:55:54', '2023-09-14 16:47:44'),
(202, 1, 3, 2, 2, 4, 3, 1, '2023-07-23 11:55:55', '2023-09-15 09:18:39'),
(203, 1, 4, 1, 1, 4, 4, 3, '2023-07-23 11:55:55', '2025-02-08 08:46:09'),
(204, 1, 5, 2, 2, 4, 5, 1, '2023-07-23 11:55:55', '2023-09-15 09:18:57'),
(205, 2, 6, 2, 2, 4, 6, 1, '2023-07-23 11:55:55', '2023-09-15 09:20:12'),
(206, 1, 7, 2, 2, 4, 7, 1, '2023-07-23 11:55:55', '2025-02-28 09:19:05'),
(207, 2, 9, 2, 2, 4, 8, 1, '2023-07-23 11:55:55', NULL),
(208, 1, 10, 2, 2, 4, 9, 1, '2023-07-23 11:55:55', '2023-07-27 11:35:00'),
(209, 1, 11, 2, 2, 4, 10, 1, '2023-07-23 11:55:55', '2023-07-27 11:35:02'),
(210, 1, 12, 2, 2, 4, 11, 1, '2023-07-23 11:55:55', '2023-07-27 11:35:04'),
(211, 1, 13, 2, 2, 4, 12, 1, '2023-07-23 11:55:55', '2023-07-27 11:35:05'),
(212, 1, 14, 1, 1, 4, 13, 3, '2023-07-23 11:55:55', '2025-02-08 09:13:36'),
(213, 1, 15, 2, 2, 4, 14, 1, '2023-07-23 11:55:55', '2025-02-08 09:12:38'),
(214, 1, 16, 2, 2, 4, 15, 1, '2023-07-23 11:55:55', '2025-02-08 09:10:45'),
(215, 1, 17, 2, 2, 4, 16, 1, '2023-07-23 11:55:55', '2025-02-08 09:09:05'),
(216, 1, 18, 2, 2, 4, 17, 1, '2023-07-23 11:55:55', '2025-02-08 09:13:03'),
(217, 1, 19, 2, 2, 4, 19, 1, '2023-07-23 11:55:55', '2023-09-15 09:21:05'),
(218, 1, 20, 2, 2, 4, 20, 1, '2023-07-23 11:55:55', '2023-09-15 09:21:12'),
(219, 1, 21, 2, 2, 4, 21, 1, '2023-07-23 11:55:55', '2023-09-15 10:16:12'),
(220, 2, 22, 2, 2, 4, 22, 1, '2023-07-23 11:55:55', NULL),
(221, 2, 23, 2, 2, 4, 23, 1, '2023-07-23 11:55:55', NULL),
(222, 2, 24, 2, 2, 4, 24, 1, '2023-07-23 11:55:55', NULL),
(223, 2, 25, 2, 2, 4, 25, 1, '2023-07-23 11:55:55', NULL),
(224, 2, 26, 2, 2, 4, 26, 1, '2023-07-23 11:55:55', NULL),
(225, 2, 27, 2, 2, 4, 27, 1, '2023-07-23 11:55:55', NULL),
(226, 2, 28, 2, 2, 4, 28, 1, '2023-07-23 11:55:55', NULL),
(227, 2, 29, 2, 2, 4, 29, 1, '2023-07-23 11:55:55', NULL),
(228, 2, 30, 2, 2, 4, 30, 1, '2023-07-23 11:55:55', NULL),
(229, 2, 31, 2, 2, 4, 31, 1, '2023-07-23 11:55:55', NULL),
(230, 2, 32, 2, 2, 4, 32, 1, '2023-07-23 11:55:55', NULL),
(231, 2, 33, 2, 2, 4, 33, 1, '2023-07-23 11:55:55', NULL),
(232, 2, 34, 2, 2, 4, 34, 1, '2023-07-23 11:55:55', NULL),
(233, 2, 35, 2, 2, 4, 35, 1, '2023-07-23 11:55:55', NULL),
(234, 2, 36, 2, 2, 4, 36, 1, '2023-07-23 11:55:55', NULL),
(235, 2, 37, 2, 2, 4, 37, 1, '2023-07-23 11:55:55', NULL),
(236, 2, 38, 2, 2, 4, 38, 1, '2023-07-23 11:55:55', NULL),
(237, 2, 39, 2, 2, 4, 39, 1, '2023-07-23 11:55:55', NULL),
(238, 2, 40, 2, 2, 4, 40, 1, '2023-07-23 11:55:55', NULL),
(239, 2, 41, 2, 2, 4, 41, 1, '2023-07-23 11:55:55', NULL),
(240, 2, 42, 2, 2, 4, 42, 1, '2023-07-23 11:55:55', NULL),
(241, 2, 43, 2, 2, 4, 43, 1, '2023-07-23 11:55:55', NULL),
(242, 2, 44, 2, 2, 4, 44, 1, '2023-07-23 11:55:55', NULL),
(243, 2, 45, 2, 2, 4, 45, 1, '2023-07-23 11:55:55', NULL),
(244, 2, 46, 2, 2, 4, 46, 1, '2023-07-23 11:55:55', NULL),
(245, 2, 47, 2, 2, 4, 47, 1, '2023-07-23 11:55:55', NULL),
(246, 2, 48, 2, 2, 4, 48, 1, '2023-07-23 11:55:55', NULL),
(247, 2, 49, 2, 2, 4, 49, 1, '2023-07-23 11:55:55', NULL),
(248, 2, 50, 2, 2, 4, 50, 1, '2023-07-23 11:55:55', NULL),
(249, 2, 51, 2, 2, 4, 51, 1, '2023-07-23 11:55:55', NULL),
(250, 2, 52, 2, 2, 4, 52, 1, '2023-07-23 11:55:55', NULL),
(251, 2, 53, 2, 2, 4, 53, 1, '2023-07-23 11:55:55', NULL),
(252, 2, 54, 2, 2, 4, 54, 1, '2023-07-23 11:55:55', NULL),
(253, 2, 55, 2, 2, 4, 55, 1, '2023-07-23 11:55:55', NULL),
(254, 2, 56, 2, 2, 4, 56, 1, '2023-07-23 11:55:55', NULL),
(255, 2, 57, 2, 2, 4, 57, 1, '2023-07-23 11:55:55', NULL),
(256, 2, 58, 2, 2, 4, 58, 1, '2023-07-23 11:55:55', NULL),
(257, 1, 59, 2, 2, 4, 59, 1, '2023-07-23 11:55:55', NULL),
(258, 1, 60, 1, 2, 4, 60, 6, '2023-07-23 11:55:55', '2023-09-04 11:18:10'),
(259, 1, 61, 2, 2, 4, 61, 1, '2023-07-23 11:55:55', NULL),
(260, 1, 62, 2, 2, 4, 62, 1, '2023-07-23 11:55:55', NULL),
(261, 1, 63, 2, 2, 4, 63, 1, '2023-07-23 11:55:55', NULL),
(262, 1, 64, 2, 2, 4, 64, 1, '2023-07-23 11:55:55', NULL),
(263, 1, 65, 2, 2, 4, 65, 1, '2023-07-23 11:55:55', NULL),
(264, 2, 66, 2, 2, 4, 66, 1, '2023-07-23 11:55:55', NULL),
(397, 1, 72, 2, 2, 1, 67, 1, '2023-07-25 11:33:56', '2023-08-08 17:04:06'),
(398, 2, 71, 2, 2, 2, 67, 1, '2023-07-25 11:33:56', '2023-09-14 16:47:49'),
(400, 2, 67, 2, 2, 4, 67, 1, '2023-07-25 11:33:57', NULL),
(403, 1, 73, 2, 2, 1, 68, 1, '2023-07-25 11:38:21', '2023-08-08 17:03:55'),
(404, 2, 72, 2, 2, 2, 68, 1, '2023-07-25 11:38:21', '2023-09-14 16:47:58'),
(406, 2, 68, 2, 2, 4, 68, 1, '2023-07-25 11:38:22', NULL),
(409, 1, 1, 1, 2, 7, 1, 1, '2023-07-27 11:39:39', '2023-08-12 20:59:03'),
(410, 1, 2, 1, 1, 7, 2, 3, '2023-07-27 11:39:39', '2023-08-12 20:59:39'),
(411, 1, 3, 2, 2, 7, 3, 1, '2023-07-27 11:39:40', '2023-09-12 15:59:56'),
(412, 1, 4, 1, 1, 7, 4, 3, '2023-07-27 11:39:40', '2024-07-04 05:53:55'),
(413, 1, 5, 2, 2, 7, 5, 1, '2023-07-27 11:39:40', '2023-09-12 16:00:06'),
(414, 1, 6, 2, 2, 7, 6, 1, '2023-07-27 11:39:40', '2023-10-03 13:17:48'),
(415, 2, 7, 2, 2, 7, 7, 1, '2023-07-27 11:39:40', NULL),
(416, 2, 8, 2, 2, 7, 8, 1, '2023-07-27 11:39:40', NULL),
(417, 1, 9, 2, 2, 7, 9, 1, '2023-07-27 11:39:40', '2023-09-04 11:14:06'),
(418, 1, 10, 2, 2, 7, 10, 1, '2023-07-27 11:39:40', '2023-09-04 11:14:15'),
(419, 1, 11, 2, 2, 7, 11, 1, '2023-07-27 11:39:40', '2023-09-04 11:14:22'),
(420, 1, 12, 2, 2, 7, 12, 1, '2023-07-27 11:39:40', '2023-09-04 11:14:28'),
(421, 1, 13, 1, 1, 7, 13, 3, '2023-07-27 11:39:40', '2024-07-04 05:52:24'),
(422, 2, 14, 2, 2, 7, 14, 1, '2023-07-27 11:39:40', '2023-09-15 09:20:36'),
(423, 2, 15, 2, 2, 7, 15, 1, '2023-07-27 11:39:40', NULL),
(424, 2, 16, 2, 2, 7, 16, 1, '2023-07-27 11:39:40', NULL),
(425, 2, 17, 2, 2, 7, 17, 1, '2023-07-27 11:39:40', NULL),
(426, 2, 18, 1, 1, 7, 18, 4, '2023-07-27 11:39:40', '2023-09-18 13:16:32'),
(427, 1, 19, 2, 2, 7, 19, 1, '2023-07-27 11:39:40', '2023-09-08 10:35:04'),
(428, 1, 20, 2, 2, 7, 20, 1, '2023-07-27 11:39:40', '2023-09-08 10:35:05'),
(429, 2, 21, 2, 2, 7, 21, 1, '2023-07-27 11:39:40', NULL),
(430, 2, 22, 2, 2, 7, 22, 1, '2023-07-27 11:39:40', NULL),
(431, 2, 23, 2, 2, 7, 23, 1, '2023-07-27 11:39:40', NULL),
(432, 2, 24, 2, 2, 7, 24, 1, '2023-07-27 11:39:40', NULL),
(433, 2, 25, 2, 2, 7, 25, 1, '2023-07-27 11:39:40', NULL),
(434, 2, 26, 2, 2, 7, 26, 1, '2023-07-27 11:39:40', NULL),
(435, 2, 27, 2, 2, 7, 27, 1, '2023-07-27 11:39:40', NULL),
(436, 2, 28, 2, 2, 7, 28, 1, '2023-07-27 11:39:40', NULL),
(437, 2, 29, 1, 1, 7, 29, 3, '2023-07-27 11:39:40', '2023-09-18 13:16:47'),
(438, 2, 30, 2, 2, 7, 30, 1, '2023-07-27 11:39:40', NULL),
(439, 2, 31, 2, 2, 7, 31, 1, '2023-07-27 11:39:40', NULL),
(440, 2, 32, 2, 2, 7, 32, 1, '2023-07-27 11:39:40', NULL),
(441, 2, 33, 2, 2, 7, 33, 1, '2023-07-27 11:39:40', NULL),
(442, 2, 34, 2, 2, 7, 34, 1, '2023-07-27 11:39:40', NULL),
(443, 2, 35, 2, 2, 7, 35, 1, '2023-07-27 11:39:40', NULL),
(444, 2, 36, 2, 2, 7, 36, 1, '2023-07-27 11:39:40', NULL),
(445, 2, 37, 2, 2, 7, 37, 1, '2023-07-27 11:39:40', NULL),
(446, 2, 38, 2, 2, 7, 38, 1, '2023-07-27 11:39:40', NULL),
(447, 2, 39, 2, 2, 7, 39, 1, '2023-07-27 11:39:40', NULL),
(448, 2, 40, 2, 2, 7, 40, 1, '2023-07-27 11:39:40', NULL),
(449, 2, 41, 2, 2, 7, 41, 1, '2023-07-27 11:39:40', NULL),
(450, 2, 42, 2, 2, 7, 42, 1, '2023-07-27 11:39:40', NULL),
(451, 2, 43, 2, 2, 7, 43, 1, '2023-07-27 11:39:40', NULL),
(452, 2, 44, 2, 2, 7, 44, 1, '2023-07-27 11:39:40', NULL),
(453, 2, 45, 2, 2, 7, 45, 1, '2023-07-27 11:39:40', NULL),
(454, 2, 46, 2, 2, 7, 46, 1, '2023-07-27 11:39:40', NULL),
(455, 2, 47, 2, 2, 7, 47, 1, '2023-07-27 11:39:40', NULL),
(456, 2, 48, 2, 2, 7, 48, 1, '2023-07-27 11:39:40', NULL),
(457, 2, 49, 2, 2, 7, 49, 1, '2023-07-27 11:39:40', NULL),
(458, 2, 50, 2, 2, 7, 50, 1, '2023-07-27 11:39:40', NULL),
(459, 2, 51, 2, 2, 7, 51, 1, '2023-07-27 11:39:40', NULL),
(460, 2, 52, 2, 2, 7, 52, 1, '2023-07-27 11:39:40', NULL),
(461, 2, 53, 2, 2, 7, 53, 1, '2023-07-27 11:39:40', NULL),
(462, 2, 54, 2, 2, 7, 54, 1, '2023-07-27 11:39:40', NULL),
(463, 2, 55, 2, 2, 7, 55, 1, '2023-07-27 11:39:40', NULL),
(464, 2, 56, 2, 2, 7, 56, 1, '2023-07-27 11:39:40', NULL),
(465, 2, 57, 2, 2, 7, 57, 1, '2023-07-27 11:39:40', NULL),
(466, 2, 58, 2, 2, 7, 58, 1, '2023-07-27 11:39:40', NULL),
(467, 1, 59, 2, 2, 7, 59, 1, '2023-07-27 11:39:40', NULL),
(468, 1, 60, 1, 2, 7, 60, 6, '2023-07-27 11:39:40', '2023-09-04 11:17:14'),
(469, 1, 61, 2, 2, 7, 61, 1, '2023-07-27 11:39:40', NULL),
(470, 1, 62, 2, 2, 7, 62, 1, '2023-07-27 11:39:40', NULL),
(471, 1, 63, 2, 2, 7, 63, 1, '2023-07-27 11:39:40', NULL),
(472, 1, 64, 2, 2, 7, 64, 1, '2023-07-27 11:39:40', NULL),
(473, 1, 65, 2, 2, 7, 65, 1, '2023-07-27 11:39:40', NULL),
(474, 2, 66, 2, 2, 7, 66, 1, '2023-07-27 11:39:40', NULL),
(475, 2, 67, 2, 2, 7, 67, 1, '2023-07-27 11:39:40', NULL),
(476, 2, 68, 2, 2, 7, 68, 1, '2023-07-27 11:39:40', NULL),
(477, 1, 74, 2, 2, 1, 69, 1, '2023-08-02 11:55:35', '2023-08-08 17:03:47'),
(478, 2, 73, 2, 2, 2, 69, 1, '2023-08-02 11:55:35', NULL),
(480, 2, 69, 2, 2, 4, 69, 1, '2023-08-02 11:55:36', NULL),
(483, 2, 69, 2, 2, 7, 69, 1, '2023-08-02 11:55:36', NULL),
(484, 1, 75, 2, 2, 1, 70, 1, '2023-08-05 10:40:47', '2023-08-08 17:03:34'),
(485, 2, 74, 2, 2, 2, 70, 1, '2023-08-05 10:40:47', '2023-09-14 16:47:30'),
(487, 2, 70, 2, 2, 4, 70, 1, '2023-08-05 10:40:48', NULL),
(490, 2, 70, 2, 2, 7, 70, 1, '2023-08-05 10:40:48', NULL),
(491, 1, 62, 1, 1, 1, 71, 4, '2023-08-07 10:38:26', '2023-08-08 17:05:29'),
(492, 2, 75, 2, 2, 2, 71, 4, '2023-08-07 10:38:27', '2023-09-14 16:50:04'),
(494, 2, 71, 2, 2, 4, 71, 1, '2023-08-07 10:38:27', '2023-08-07 10:39:00'),
(497, 2, 71, 2, 2, 7, 71, 1, '2023-08-07 10:38:28', NULL),
(498, 1, 76, 2, 2, 1, 72, 1, '2023-08-07 10:45:43', '2023-08-08 17:03:25'),
(499, 2, 76, 2, 2, 2, 72, 1, '2023-08-07 10:45:43', NULL),
(501, 2, 72, 2, 2, 4, 72, 1, '2023-08-07 10:45:44', NULL),
(504, 2, 72, 2, 2, 7, 72, 1, '2023-08-07 10:45:45', NULL),
(505, 1, 63, 1, 1, 1, 73, 4, '2023-08-07 11:25:16', '2023-08-08 17:05:29'),
(506, 2, 77, 2, 2, 2, 73, 4, '2023-08-07 11:25:17', '2023-09-14 16:50:10'),
(508, 2, 73, 2, 2, 4, 73, 1, '2023-08-07 11:25:17', NULL),
(511, 2, 73, 2, 2, 7, 73, 1, '2023-08-07 11:25:18', NULL),
(512, 1, 77, 2, 2, 1, 74, 1, '2023-08-07 11:32:01', '2023-08-07 11:38:01'),
(513, 2, 78, 2, 2, 2, 74, 1, '2023-08-07 11:32:01', NULL),
(515, 2, 74, 2, 2, 4, 74, 1, '2023-08-07 11:32:01', NULL),
(518, 2, 74, 2, 2, 7, 74, 1, '2023-08-07 11:32:02', NULL),
(519, 1, 78, 2, 2, 1, 75, 1, '2023-08-07 12:04:58', NULL),
(520, 2, 79, 2, 2, 2, 75, 1, '2023-08-07 12:04:58', NULL),
(522, 2, 75, 2, 2, 4, 75, 1, '2023-08-07 12:04:58', NULL),
(525, 2, 75, 2, 2, 7, 75, 1, '2023-08-07 12:04:59', NULL),
(526, 1, 79, 2, 2, 1, 76, 1, '2023-08-07 12:06:13', NULL),
(527, 2, 80, 2, 2, 2, 76, 1, '2023-08-07 12:06:13', NULL),
(529, 2, 76, 2, 2, 4, 76, 1, '2023-08-07 12:06:14', NULL),
(532, 2, 76, 2, 2, 7, 76, 1, '2023-08-07 12:06:14', NULL),
(533, 1, 80, 2, 2, 1, 77, 1, '2023-08-07 12:08:02', NULL),
(534, 2, 81, 2, 2, 2, 77, 1, '2023-08-07 12:08:02', NULL),
(536, 2, 77, 2, 2, 4, 77, 1, '2023-08-07 12:08:02', NULL),
(539, 2, 77, 2, 2, 7, 77, 1, '2023-08-07 12:08:03', NULL),
(540, 1, 81, 2, 2, 1, 78, 1, '2023-08-07 12:09:57', NULL),
(541, 2, 82, 2, 2, 2, 78, 1, '2023-08-07 12:09:57', NULL),
(543, 2, 78, 2, 2, 4, 78, 1, '2023-08-07 12:09:57', NULL),
(546, 2, 78, 2, 2, 7, 78, 1, '2023-08-07 12:09:58', NULL),
(547, 1, 82, 2, 2, 1, 79, 1, '2023-08-12 11:54:36', NULL),
(548, 2, 83, 2, 2, 2, 79, 1, '2023-08-12 11:54:36', NULL),
(550, 2, 79, 2, 2, 4, 79, 1, '2023-08-12 11:54:37', NULL),
(553, 2, 79, 2, 2, 7, 79, 1, '2023-08-12 11:54:38', NULL),
(554, 1, 83, 2, 2, 1, 80, 1, '2023-08-12 20:23:53', NULL),
(555, 2, 84, 2, 2, 2, 80, 1, '2023-08-12 20:23:54', NULL),
(557, 2, 80, 2, 2, 4, 80, 1, '2023-08-12 20:23:54', NULL),
(560, 2, 80, 2, 2, 7, 80, 1, '2023-08-12 20:23:55', NULL),
(721, 1, 84, 2, 2, 1, 81, 1, '2023-08-20 12:54:35', '2023-08-21 16:48:14'),
(722, 1, 85, 2, 2, 2, 81, 1, '2023-08-20 12:54:35', '2023-09-14 16:50:26'),
(724, 1, 81, 2, 2, 4, 81, 1, '2023-08-20 12:54:36', '2023-09-15 10:17:18'),
(727, 2, 81, 2, 2, 7, 81, 1, '2023-08-20 12:54:36', NULL),
(1345, 1, 128, 2, 2, 1, 128, 1, '2023-12-23 07:15:51', NULL),
(1346, 1, 129, 2, 2, 2, 128, 1, '2023-12-23 07:15:51', '2023-12-23 07:33:00'),
(1348, 1, 125, 2, 2, 4, 128, 1, '2023-12-23 07:15:52', '2024-12-28 08:39:21'),
(1351, 2, 125, 2, 2, 7, 128, 1, '2023-12-23 07:15:52', NULL),
(1355, 1, 1, 1, 2, 12, 1, 1, '2024-01-02 05:30:20', NULL),
(1356, 2, 2, 1, 1, 12, 2, 3, '2024-01-02 05:30:20', '2025-02-28 20:19:38'),
(1357, 1, 3, 2, 2, 12, 3, 1, '2024-01-02 05:30:20', NULL),
(1358, 1, 4, 2, 2, 12, 4, 1, '2024-01-02 05:30:20', NULL),
(1359, 1, 5, 2, 2, 12, 5, 1, '2024-01-02 05:30:20', NULL),
(1360, 1, 6, 2, 2, 12, 6, 1, '2024-01-02 05:30:20', NULL),
(1361, 2, 7, 2, 2, 12, 7, 1, '2024-01-02 05:30:20', NULL),
(1362, 2, 8, 2, 2, 12, 8, 1, '2024-01-02 05:30:20', NULL),
(1363, 1, 9, 2, 2, 12, 9, 1, '2024-01-02 05:30:20', NULL),
(1364, 1, 10, 2, 2, 12, 10, 1, '2024-01-02 05:30:20', NULL),
(1365, 1, 11, 2, 2, 12, 11, 1, '2024-01-02 05:30:20', NULL),
(1366, 1, 12, 2, 2, 12, 12, 1, '2024-01-02 05:30:20', NULL),
(1367, 2, 13, 2, 2, 12, 13, 1, '2024-01-02 05:30:20', NULL),
(1368, 2, 14, 2, 2, 12, 14, 1, '2024-01-02 05:30:20', NULL),
(1369, 2, 15, 2, 2, 12, 15, 1, '2024-01-02 05:30:20', NULL),
(1370, 2, 16, 2, 2, 12, 16, 1, '2024-01-02 05:30:20', NULL),
(1371, 2, 17, 2, 2, 12, 17, 1, '2024-01-02 05:30:20', NULL),
(1372, 2, 18, 1, 1, 12, 18, 4, '2024-01-02 05:30:20', NULL),
(1373, 1, 19, 2, 2, 12, 19, 1, '2024-01-02 05:30:20', NULL),
(1374, 1, 20, 2, 2, 12, 20, 1, '2024-01-02 05:30:20', NULL),
(1375, 2, 21, 2, 2, 12, 21, 1, '2024-01-02 05:30:20', NULL),
(1376, 2, 22, 2, 2, 12, 22, 1, '2024-01-02 05:30:20', NULL),
(1377, 2, 23, 2, 2, 12, 23, 1, '2024-01-02 05:30:20', NULL),
(1378, 2, 24, 2, 2, 12, 24, 1, '2024-01-02 05:30:20', NULL),
(1379, 2, 25, 2, 2, 12, 25, 1, '2024-01-02 05:30:20', NULL),
(1380, 2, 26, 2, 2, 12, 26, 1, '2024-01-02 05:30:20', NULL),
(1381, 2, 27, 2, 2, 12, 27, 1, '2024-01-02 05:30:20', NULL),
(1382, 2, 28, 2, 2, 12, 28, 1, '2024-01-02 05:30:20', NULL),
(1383, 2, 29, 1, 1, 12, 29, 3, '2024-01-02 05:30:20', NULL),
(1384, 2, 30, 2, 2, 12, 30, 1, '2024-01-02 05:30:20', NULL),
(1385, 2, 31, 2, 2, 12, 31, 1, '2024-01-02 05:30:20', NULL),
(1386, 2, 32, 2, 2, 12, 32, 1, '2024-01-02 05:30:20', NULL),
(1387, 2, 33, 2, 2, 12, 33, 1, '2024-01-02 05:30:20', NULL),
(1388, 2, 34, 2, 2, 12, 34, 1, '2024-01-02 05:30:20', NULL),
(1389, 2, 35, 2, 2, 12, 35, 1, '2024-01-02 05:30:20', NULL),
(1390, 2, 36, 2, 2, 12, 36, 1, '2024-01-02 05:30:20', NULL),
(1391, 2, 37, 2, 2, 12, 37, 1, '2024-01-02 05:30:20', NULL),
(1392, 2, 38, 2, 2, 12, 38, 1, '2024-01-02 05:30:20', NULL),
(1393, 2, 39, 2, 2, 12, 39, 1, '2024-01-02 05:30:20', NULL),
(1394, 2, 40, 2, 2, 12, 40, 1, '2024-01-02 05:30:20', NULL),
(1395, 2, 41, 2, 2, 12, 41, 1, '2024-01-02 05:30:20', NULL),
(1396, 2, 42, 2, 2, 12, 42, 1, '2024-01-02 05:30:20', NULL),
(1397, 2, 43, 2, 2, 12, 43, 1, '2024-01-02 05:30:20', NULL),
(1398, 2, 44, 2, 2, 12, 44, 1, '2024-01-02 05:30:20', NULL),
(1399, 2, 45, 2, 2, 12, 45, 1, '2024-01-02 05:30:20', NULL),
(1400, 2, 46, 2, 2, 12, 46, 1, '2024-01-02 05:30:20', NULL),
(1401, 2, 47, 2, 2, 12, 47, 1, '2024-01-02 05:30:20', NULL),
(1402, 2, 48, 2, 2, 12, 48, 1, '2024-01-02 05:30:20', NULL),
(1403, 2, 49, 2, 2, 12, 49, 1, '2024-01-02 05:30:20', NULL),
(1404, 2, 50, 2, 2, 12, 50, 1, '2024-01-02 05:30:20', NULL),
(1405, 2, 51, 2, 2, 12, 51, 1, '2024-01-02 05:30:20', NULL),
(1406, 2, 52, 2, 2, 12, 52, 1, '2024-01-02 05:30:20', NULL),
(1407, 2, 53, 2, 2, 12, 53, 1, '2024-01-02 05:30:20', NULL),
(1408, 2, 54, 2, 2, 12, 54, 1, '2024-01-02 05:30:20', NULL),
(1409, 2, 55, 2, 2, 12, 55, 1, '2024-01-02 05:30:20', NULL),
(1410, 2, 56, 2, 2, 12, 56, 1, '2024-01-02 05:30:20', NULL),
(1411, 2, 57, 2, 2, 12, 57, 1, '2024-01-02 05:30:21', NULL),
(1412, 2, 58, 2, 2, 12, 58, 1, '2024-01-02 05:30:21', NULL),
(1413, 1, 59, 2, 2, 12, 59, 1, '2024-01-02 05:30:21', NULL),
(1414, 1, 60, 1, 2, 12, 60, 6, '2024-01-02 05:30:21', NULL),
(1415, 1, 61, 2, 2, 12, 61, 1, '2024-01-02 05:30:21', NULL),
(1416, 1, 62, 2, 2, 12, 62, 1, '2024-01-02 05:30:21', NULL),
(1417, 1, 63, 2, 2, 12, 63, 1, '2024-01-02 05:30:21', NULL),
(1418, 1, 64, 2, 2, 12, 64, 1, '2024-01-02 05:30:21', NULL),
(1419, 1, 65, 2, 2, 12, 65, 1, '2024-01-02 05:30:21', NULL),
(1420, 2, 66, 2, 2, 12, 66, 1, '2024-01-02 05:30:21', NULL),
(1421, 2, 67, 2, 2, 12, 67, 1, '2024-01-02 05:30:21', NULL),
(1422, 2, 68, 2, 2, 12, 68, 1, '2024-01-02 05:30:21', NULL),
(1423, 2, 69, 2, 2, 12, 69, 1, '2024-01-02 05:30:21', NULL),
(1424, 2, 70, 2, 2, 12, 70, 1, '2024-01-02 05:30:21', NULL),
(1425, 2, 71, 2, 2, 12, 71, 1, '2024-01-02 05:30:21', NULL),
(1426, 2, 72, 2, 2, 12, 72, 1, '2024-01-02 05:30:21', NULL),
(1427, 2, 73, 2, 2, 12, 73, 1, '2024-01-02 05:30:21', NULL),
(1428, 2, 74, 2, 2, 12, 74, 1, '2024-01-02 05:30:21', NULL),
(1429, 2, 75, 2, 2, 12, 75, 1, '2024-01-02 05:30:21', NULL),
(1430, 2, 76, 2, 2, 12, 76, 1, '2024-01-02 05:30:21', NULL),
(1431, 2, 77, 2, 2, 12, 77, 1, '2024-01-02 05:30:21', NULL),
(1432, 2, 78, 2, 2, 12, 78, 1, '2024-01-02 05:30:21', NULL),
(1433, 2, 79, 2, 2, 12, 79, 1, '2024-01-02 05:30:21', NULL),
(1434, 2, 80, 2, 2, 12, 80, 1, '2024-01-02 05:30:21', NULL),
(1435, 2, 81, 2, 2, 12, 81, 1, '2024-01-02 05:30:21', '2024-01-02 05:47:08'),
(1479, 2, 125, 2, 2, 12, 128, 1, '2024-01-02 05:30:21', NULL),
(1627, 1, 150, 1, 1, 1, 150, 18, '2025-01-13 07:51:13', '2025-01-13 07:55:03'),
(1628, 1, 151, 1, 1, 2, 150, 18, '2025-01-13 07:51:15', '2025-01-20 08:34:31'),
(1629, 2, 147, 1, 1, 4, 150, 18, '2025-01-13 07:51:17', '2026-03-31 08:28:51'),
(1630, 1, 147, 1, 1, 7, 150, 18, '2025-01-13 07:51:17', '2025-01-20 08:31:06'),
(1633, 2, 147, 2, 2, 12, 150, 1, '2025-01-13 07:51:22', NULL),
(1775, 1, 152, 2, 2, 1, 154, 4, '2025-02-13 07:17:24', '2025-02-18 08:40:54'),
(1776, 1, 153, 2, 2, 2, 154, 1, '2025-02-13 07:17:24', '2025-08-14 03:57:34'),
(1777, 1, 149, 2, 2, 4, 154, 4, '2025-02-13 07:17:26', '2025-11-04 04:39:28'),
(1778, 2, 149, 2, 2, 7, 154, 1, '2025-02-13 07:17:27', NULL),
(1779, 2, 149, 2, 2, 12, 154, 1, '2025-02-13 07:17:28', NULL),
(1781, 1, 153, 2, 2, 1, 155, 1, '2025-02-13 07:50:15', NULL),
(1782, 1, 154, 2, 2, 2, 155, 1, '2025-02-13 07:50:16', '2025-08-14 03:57:41'),
(1783, 1, 150, 2, 2, 4, 155, 1, '2025-02-13 07:50:17', '2025-11-04 04:39:04'),
(1784, 2, 150, 2, 2, 7, 155, 1, '2025-02-13 07:50:18', NULL),
(1785, 2, 150, 2, 2, 12, 155, 1, '2025-02-13 07:50:19', NULL),
(1787, 1, 154, 1, 1, 1, 156, 4, '2025-02-18 08:33:27', '2025-02-18 08:43:37'),
(1788, 1, 155, 1, 1, 2, 156, 4, '2025-02-18 08:33:28', '2025-08-14 03:58:31'),
(1789, 1, 151, 1, 1, 4, 156, 4, '2025-02-18 08:33:29', '2025-11-04 04:38:52'),
(1790, 2, 151, 2, 2, 7, 156, 1, '2025-02-18 08:33:31', NULL),
(1791, 2, 151, 2, 2, 12, 156, 1, '2025-02-18 08:33:32', NULL),
(1793, 1, 155, 2, 2, 1, 157, 1, '2025-02-18 11:54:37', NULL),
(1794, 1, 156, 2, 2, 2, 157, 1, '2025-02-18 11:54:39', '2025-08-14 04:03:26'),
(1795, 2, 152, 2, 2, 4, 157, 1, '2025-02-18 11:54:41', NULL),
(1796, 2, 152, 2, 2, 7, 157, 1, '2025-02-18 11:54:42', NULL),
(1797, 2, 152, 2, 2, 12, 157, 1, '2025-02-18 11:54:44', NULL),
(1805, 1, 1, 1, 2, 14, 1, 1, '2025-02-22 09:53:57', NULL),
(1806, 2, 2, 2, 2, 14, 2, 3, '2025-02-22 09:53:57', '2025-02-23 10:41:56'),
(1807, 2, 3, 2, 2, 14, 3, 1, '2025-02-22 09:53:57', '2025-02-23 10:42:00'),
(1808, 2, 4, 2, 2, 14, 4, 3, '2025-02-22 09:53:57', '2025-02-23 10:41:59'),
(1809, 2, 5, 2, 2, 14, 5, 1, '2025-02-22 09:53:57', '2025-02-23 10:42:03'),
(1810, 2, 6, 2, 2, 14, 6, 1, '2025-02-22 09:53:58', '2025-02-23 10:42:06'),
(1811, 2, 7, 2, 2, 14, 7, 1, '2025-02-22 09:53:58', NULL),
(1812, 2, 8, 2, 2, 14, 8, 1, '2025-02-22 09:53:58', NULL),
(1813, 1, 9, 2, 2, 14, 9, 1, '2025-02-22 09:53:58', NULL),
(1814, 1, 10, 2, 2, 14, 10, 1, '2025-02-22 09:53:58', NULL),
(1815, 1, 11, 2, 2, 14, 11, 1, '2025-02-22 09:53:58', NULL),
(1816, 1, 12, 2, 2, 14, 12, 1, '2025-02-22 09:53:58', NULL),
(1817, 1, 13, 2, 2, 14, 13, 3, '2025-02-22 09:53:58', '2025-02-23 10:42:36'),
(1818, 2, 14, 2, 2, 14, 14, 1, '2025-02-22 09:53:58', NULL),
(1819, 2, 15, 2, 2, 14, 15, 1, '2025-02-22 09:53:58', NULL),
(1820, 2, 16, 2, 2, 14, 16, 1, '2025-02-22 09:53:58', NULL),
(1821, 2, 17, 2, 2, 14, 17, 1, '2025-02-22 09:53:58', NULL),
(1822, 2, 18, 2, 1, 14, 18, 4, '2025-02-22 09:53:58', '2025-02-23 10:42:13'),
(1823, 2, 19, 2, 2, 14, 19, 1, '2025-02-22 09:53:58', '2025-02-23 10:42:54'),
(1824, 2, 20, 2, 2, 14, 20, 1, '2025-02-22 09:53:58', '2025-02-23 10:42:56'),
(1825, 2, 21, 2, 2, 14, 21, 1, '2025-02-22 09:53:58', NULL),
(1826, 2, 22, 2, 2, 14, 22, 1, '2025-02-22 09:53:58', NULL),
(1827, 2, 23, 2, 2, 14, 23, 1, '2025-02-22 09:53:58', NULL),
(1828, 2, 24, 2, 2, 14, 24, 1, '2025-02-22 09:53:58', NULL),
(1829, 2, 25, 2, 2, 14, 25, 1, '2025-02-22 09:53:58', NULL),
(1830, 2, 26, 2, 2, 14, 26, 1, '2025-02-22 09:53:58', NULL),
(1831, 2, 27, 2, 2, 14, 27, 1, '2025-02-22 09:53:58', NULL),
(1832, 2, 28, 2, 2, 14, 28, 1, '2025-02-22 09:53:58', NULL),
(1833, 2, 29, 1, 1, 14, 29, 3, '2025-02-22 09:53:58', NULL),
(1834, 2, 30, 2, 2, 14, 30, 1, '2025-02-22 09:53:58', NULL),
(1835, 2, 31, 2, 2, 14, 31, 1, '2025-02-22 09:53:58', NULL),
(1836, 2, 32, 2, 2, 14, 32, 1, '2025-02-22 09:53:58', NULL),
(1837, 2, 33, 2, 2, 14, 33, 1, '2025-02-22 09:53:59', NULL),
(1838, 2, 34, 2, 2, 14, 34, 1, '2025-02-22 09:53:59', NULL),
(1839, 2, 35, 2, 2, 14, 35, 1, '2025-02-22 09:53:59', NULL),
(1840, 2, 36, 2, 2, 14, 36, 1, '2025-02-22 09:53:59', NULL),
(1841, 2, 37, 2, 2, 14, 37, 1, '2025-02-22 09:53:59', NULL),
(1842, 2, 38, 2, 2, 14, 38, 1, '2025-02-22 09:53:59', NULL),
(1843, 2, 39, 2, 2, 14, 39, 1, '2025-02-22 09:53:59', NULL),
(1844, 2, 40, 2, 2, 14, 40, 1, '2025-02-22 09:53:59', NULL),
(1845, 2, 41, 2, 2, 14, 41, 1, '2025-02-22 09:53:59', NULL),
(1846, 2, 42, 2, 2, 14, 42, 1, '2025-02-22 09:53:59', NULL),
(1847, 2, 43, 2, 2, 14, 43, 1, '2025-02-22 09:53:59', NULL),
(1848, 2, 44, 2, 2, 14, 44, 1, '2025-02-22 09:53:59', NULL),
(1849, 2, 45, 2, 2, 14, 45, 1, '2025-02-22 09:53:59', NULL),
(1850, 2, 46, 2, 2, 14, 46, 1, '2025-02-22 09:53:59', NULL),
(1851, 2, 47, 2, 2, 14, 47, 1, '2025-02-22 09:53:59', NULL),
(1852, 2, 48, 2, 2, 14, 48, 1, '2025-02-22 09:53:59', NULL),
(1853, 2, 49, 2, 2, 14, 49, 1, '2025-02-22 09:53:59', NULL),
(1854, 2, 50, 2, 2, 14, 50, 1, '2025-02-22 09:53:59', NULL),
(1855, 2, 51, 2, 2, 14, 51, 1, '2025-02-22 09:53:59', NULL),
(1856, 2, 52, 2, 2, 14, 52, 1, '2025-02-22 09:53:59', NULL),
(1857, 2, 53, 2, 2, 14, 53, 1, '2025-02-22 09:53:59', NULL),
(1858, 2, 54, 2, 2, 14, 54, 1, '2025-02-22 09:53:59', NULL),
(1859, 2, 55, 2, 2, 14, 55, 1, '2025-02-22 09:53:59', NULL),
(1860, 2, 56, 2, 2, 14, 56, 1, '2025-02-22 09:53:59', NULL),
(1861, 2, 57, 2, 2, 14, 57, 1, '2025-02-22 09:53:59', NULL),
(1862, 2, 58, 2, 2, 14, 58, 1, '2025-02-22 09:53:59', NULL),
(1863, 1, 59, 2, 2, 14, 59, 1, '2025-02-22 09:53:59', NULL),
(1864, 1, 60, 1, 2, 14, 60, 6, '2025-02-22 09:53:59', NULL),
(1865, 1, 61, 2, 2, 14, 61, 1, '2025-02-22 09:53:59', NULL),
(1866, 1, 62, 2, 2, 14, 62, 1, '2025-02-22 09:53:59', NULL),
(1867, 1, 63, 2, 2, 14, 63, 1, '2025-02-22 09:53:59', NULL),
(1868, 1, 64, 2, 2, 14, 64, 1, '2025-02-22 09:53:59', NULL),
(1869, 1, 65, 2, 2, 14, 65, 1, '2025-02-22 09:53:59', NULL),
(1870, 2, 66, 2, 2, 14, 66, 1, '2025-02-22 09:53:59', NULL),
(1871, 2, 67, 2, 2, 14, 67, 1, '2025-02-22 09:53:59', NULL),
(1872, 2, 68, 2, 2, 14, 68, 1, '2025-02-22 09:53:59', NULL),
(1873, 2, 69, 2, 2, 14, 69, 1, '2025-02-22 09:53:59', NULL),
(1874, 2, 70, 2, 2, 14, 70, 1, '2025-02-22 09:53:59', NULL),
(1875, 2, 71, 2, 2, 14, 71, 1, '2025-02-22 09:53:59', NULL),
(1876, 2, 72, 2, 2, 14, 72, 1, '2025-02-22 09:53:59', NULL),
(1877, 2, 73, 2, 2, 14, 73, 1, '2025-02-22 09:53:59', NULL),
(1878, 2, 74, 2, 2, 14, 74, 1, '2025-02-22 09:53:59', NULL),
(1879, 2, 75, 2, 2, 14, 75, 1, '2025-02-22 09:53:59', NULL),
(1880, 2, 76, 2, 2, 14, 76, 1, '2025-02-22 09:53:59', NULL),
(1881, 2, 77, 2, 2, 14, 77, 1, '2025-02-22 09:53:59', NULL),
(1882, 2, 78, 2, 2, 14, 78, 1, '2025-02-22 09:53:59', NULL),
(1883, 2, 79, 2, 2, 14, 79, 1, '2025-02-22 09:53:59', NULL),
(1884, 2, 80, 2, 2, 14, 80, 1, '2025-02-22 09:53:59', NULL),
(1885, 2, 81, 2, 2, 14, 81, 1, '2025-02-22 09:54:00', NULL),
(1924, 2, 120, 2, 2, 14, 128, 1, '2025-02-22 09:54:01', NULL),
(1925, 2, 121, 1, 1, 14, 150, 18, '2025-02-22 09:54:01', '2025-02-23 10:46:02'),
(1927, 2, 123, 2, 2, 14, 154, 1, '2025-02-22 09:54:01', NULL),
(1928, 2, 124, 2, 2, 14, 155, 1, '2025-02-22 09:54:01', NULL),
(1929, 2, 125, 2, 2, 14, 156, 1, '2025-02-22 09:54:01', NULL),
(1930, 2, 126, 2, 2, 14, 157, 1, '2025-02-22 09:54:01', NULL),
(2009, 1, 167, 1, 1, 1, 170, 20, '2025-07-02 09:28:08', '2025-07-02 09:31:35'),
(2010, 2, 168, 2, 2, 2, 170, 1, '2025-07-02 09:28:08', NULL),
(2011, 2, 164, 1, 1, 4, 170, 20, '2025-07-02 09:28:08', '2026-02-14 07:00:02'),
(2012, 2, 164, 2, 2, 7, 170, 1, '2025-07-02 09:28:08', NULL),
(2013, 2, 164, 1, 1, 12, 170, 20, '2025-07-02 09:28:08', '2026-02-14 07:01:02'),
(2015, 2, 138, 2, 2, 14, 170, 1, '2025-07-02 09:28:08', NULL),
(2023, 1, 169, 2, 2, 1, 172, 1, '2025-07-02 09:31:10', NULL),
(2024, 2, 170, 2, 2, 2, 172, 1, '2025-07-02 09:31:10', NULL),
(2025, 1, 166, 2, 2, 4, 172, 1, '2025-07-02 09:31:11', '2025-07-02 09:32:24'),
(2026, 2, 166, 2, 2, 7, 172, 1, '2025-07-02 09:31:11', NULL),
(2027, 1, 166, 2, 2, 12, 172, 1, '2025-07-02 09:31:11', '2025-07-02 09:44:34'),
(2029, 2, 140, 2, 2, 14, 172, 1, '2025-07-02 09:31:11', NULL),
(2030, 1, 170, 2, 2, 1, 173, 1, '2025-07-03 14:10:38', NULL),
(2031, 2, 171, 2, 2, 2, 173, 1, '2025-07-03 14:10:38', NULL),
(2032, 1, 167, 2, 2, 4, 173, 1, '2025-07-03 14:10:38', '2025-07-03 14:10:48'),
(2033, 2, 167, 2, 2, 7, 173, 1, '2025-07-03 14:10:38', NULL),
(2034, 2, 167, 2, 2, 12, 173, 1, '2025-07-03 14:10:38', NULL),
(2036, 2, 141, 2, 2, 14, 173, 1, '2025-07-03 14:10:38', NULL),
(2044, 1, 172, 2, 2, 1, 175, 1, '2025-08-04 09:52:36', NULL),
(2045, 2, 173, 2, 2, 2, 175, 1, '2025-08-04 09:52:36', NULL),
(2046, 1, 169, 2, 2, 4, 175, 1, '2025-08-04 09:52:37', '2025-08-04 09:52:51'),
(2047, 2, 169, 2, 2, 7, 175, 1, '2025-08-04 09:52:37', NULL),
(2048, 1, 169, 2, 2, 12, 175, 1, '2025-08-04 09:52:37', '2025-08-16 05:00:32'),
(2050, 2, 143, 2, 2, 14, 175, 1, '2025-08-04 09:52:37', NULL),
(2072, 1, 176, 2, 2, 1, 179, 1, '2025-08-12 09:53:10', NULL),
(2073, 2, 177, 2, 2, 2, 179, 1, '2025-08-12 09:53:10', NULL),
(2074, 1, 173, 2, 2, 4, 179, 1, '2025-08-12 09:53:10', '2025-08-12 10:00:07'),
(2075, 2, 173, 2, 2, 7, 179, 1, '2025-08-12 09:53:10', NULL),
(2076, 2, 173, 2, 2, 12, 179, 1, '2025-08-12 09:53:10', NULL),
(2078, 2, 147, 2, 2, 14, 179, 1, '2025-08-12 09:53:10', NULL),
(2170, 1, 190, 2, 2, 1, 193, 1, '2025-10-19 07:18:16', NULL),
(2171, 2, 191, 2, 2, 2, 193, 1, '2025-10-19 07:18:16', NULL),
(2172, 1, 187, 2, 2, 4, 193, 1, '2025-10-19 07:18:17', '2025-10-19 07:18:34'),
(2173, 2, 187, 2, 2, 7, 193, 1, '2025-10-19 07:18:17', NULL),
(2174, 2, 187, 2, 2, 12, 193, 1, '2025-10-19 07:18:17', NULL),
(2176, 2, 161, 2, 2, 14, 193, 1, '2025-10-19 07:18:17', NULL),
(2177, 1, 191, 1, 1, 1, 194, 20, '2025-10-19 07:19:37', '2025-10-19 07:20:00'),
(2178, 2, 192, 2, 2, 2, 194, 1, '2025-10-19 07:19:37', NULL),
(2179, 2, 188, 1, 1, 4, 194, 20, '2025-10-19 07:19:37', '2026-03-31 08:26:04'),
(2180, 2, 188, 2, 2, 7, 194, 1, '2025-10-19 07:19:38', NULL),
(2181, 2, 188, 2, 2, 12, 194, 1, '2025-10-19 07:19:38', NULL),
(2183, 2, 162, 2, 2, 14, 194, 1, '2025-10-19 07:19:38', NULL),
(2233, 1, 199, 2, 2, 1, 202, 1, '2026-01-13 06:32:03', NULL),
(2234, 2, 200, 2, 2, 2, 202, 1, '2026-01-13 06:32:03', NULL),
(2235, 1, 196, 2, 2, 4, 202, 1, '2026-01-13 06:32:03', '2026-01-13 06:32:17'),
(2236, 2, 196, 2, 2, 7, 202, 1, '2026-01-13 06:32:03', NULL),
(2237, 2, 196, 2, 2, 12, 202, 1, '2026-01-13 06:32:03', NULL),
(2239, 2, 170, 2, 2, 14, 202, 1, '2026-01-13 06:32:03', NULL),
(2240, 1, 200, 2, 2, 1, 203, 1, '2026-01-16 03:02:28', NULL),
(2241, 2, 201, 2, 2, 2, 203, 1, '2026-01-16 03:02:28', NULL),
(2242, 2, 197, 1, 1, 4, 203, 23, '2026-01-16 03:02:28', '2026-03-31 08:26:32'),
(2243, 2, 197, 2, 2, 7, 203, 1, '2026-01-16 03:02:29', NULL),
(2244, 1, 197, 1, 1, 12, 203, 23, '2026-01-16 03:02:29', '2026-01-16 03:05:28'),
(2246, 2, 171, 2, 2, 14, 203, 1, '2026-01-16 03:02:29', NULL),
(2247, 1, 201, 2, 2, 1, 204, 1, '2026-01-16 03:08:14', NULL),
(2248, 2, 202, 2, 2, 2, 204, 1, '2026-01-16 03:08:15', NULL),
(2249, 1, 198, 2, 2, 4, 204, 1, '2026-01-16 03:08:15', '2026-01-16 03:08:27'),
(2250, 2, 198, 2, 2, 7, 204, 1, '2026-01-16 03:08:15', NULL),
(2251, 1, 198, 2, 2, 12, 204, 1, '2026-01-16 03:08:15', '2026-01-16 03:08:53'),
(2253, 2, 172, 2, 2, 14, 204, 1, '2026-01-16 03:08:15', NULL),
(2254, 1, 202, 2, 2, 1, 205, 1, '2026-01-16 03:10:24', NULL),
(2255, 2, 203, 2, 2, 2, 205, 1, '2026-01-16 03:10:25', NULL),
(2256, 1, 199, 2, 2, 4, 205, 1, '2026-01-16 03:10:25', '2026-01-16 03:10:39'),
(2257, 2, 199, 2, 2, 7, 205, 1, '2026-01-16 03:10:25', NULL),
(2258, 1, 199, 2, 2, 12, 205, 1, '2026-01-16 03:10:25', '2026-01-16 03:10:54'),
(2260, 2, 173, 2, 2, 14, 205, 1, '2026-01-16 03:10:25', NULL),
(2261, 1, 203, 2, 2, 1, 206, 1, '2026-01-16 03:12:16', NULL),
(2262, 2, 204, 2, 2, 2, 206, 1, '2026-01-16 03:12:16', NULL),
(2263, 1, 200, 2, 2, 4, 206, 1, '2026-01-16 03:12:16', '2026-01-16 03:12:30'),
(2264, 2, 200, 2, 2, 7, 206, 1, '2026-01-16 03:12:16', NULL),
(2265, 1, 200, 2, 2, 12, 206, 1, '2026-01-16 03:12:16', '2026-01-16 03:12:43'),
(2267, 2, 174, 2, 2, 14, 206, 1, '2026-01-16 03:12:16', NULL),
(2268, 1, 204, 2, 2, 1, 207, 1, '2026-01-16 03:14:11', NULL),
(2269, 2, 205, 2, 2, 2, 207, 1, '2026-01-16 03:14:12', NULL),
(2270, 1, 201, 2, 2, 4, 207, 1, '2026-01-16 03:14:12', '2026-01-16 03:14:27'),
(2271, 2, 201, 2, 2, 7, 207, 1, '2026-01-16 03:14:12', NULL),
(2272, 1, 201, 2, 2, 12, 207, 1, '2026-01-16 03:14:12', '2026-01-16 03:14:44'),
(2274, 2, 175, 2, 2, 14, 207, 1, '2026-01-16 03:14:12', NULL),
(2275, 1, 205, 2, 2, 1, 208, 1, '2026-01-16 03:15:46', NULL),
(2276, 2, 206, 2, 2, 2, 208, 1, '2026-01-16 03:15:46', NULL),
(2277, 1, 202, 2, 2, 4, 208, 1, '2026-01-16 03:15:46', '2026-01-16 03:15:57'),
(2278, 2, 202, 2, 2, 7, 208, 1, '2026-01-16 03:15:46', NULL),
(2279, 1, 202, 2, 2, 12, 208, 1, '2026-01-16 03:15:46', '2026-01-16 03:16:11'),
(2281, 2, 176, 2, 2, 14, 208, 1, '2026-01-16 03:15:46', NULL),
(2282, 1, 206, 2, 2, 1, 209, 1, '2026-01-16 03:17:32', NULL),
(2283, 2, 207, 2, 2, 2, 209, 1, '2026-01-16 03:17:32', NULL),
(2284, 1, 203, 2, 2, 4, 209, 1, '2026-01-16 03:17:32', '2026-01-16 03:17:45'),
(2285, 2, 203, 2, 2, 7, 209, 1, '2026-01-16 03:17:32', NULL),
(2286, 1, 203, 2, 2, 12, 209, 1, '2026-01-16 03:17:32', '2026-01-16 03:18:00'),
(2288, 2, 177, 2, 2, 14, 209, 1, '2026-01-16 03:17:33', NULL),
(2289, 1, 207, 2, 2, 1, 210, 1, '2026-01-16 03:19:17', NULL),
(2290, 2, 208, 2, 2, 2, 210, 1, '2026-01-16 03:19:17', NULL),
(2291, 1, 204, 2, 2, 4, 210, 1, '2026-01-16 03:19:17', '2026-01-16 03:19:32'),
(2292, 2, 204, 2, 2, 7, 210, 1, '2026-01-16 03:19:17', NULL),
(2293, 1, 204, 2, 2, 12, 210, 1, '2026-01-16 03:19:17', '2026-01-16 03:19:46'),
(2295, 2, 178, 2, 2, 14, 210, 1, '2026-01-16 03:19:18', NULL),
(2373, 1, 208, 2, 2, 1, 222, 12, '2026-03-13 14:10:42', '2026-03-13 19:09:54'),
(2374, 2, 209, 2, 2, 2, 222, 1, '2026-03-13 14:10:43', NULL),
(2375, 1, 205, 2, 2, 4, 222, 12, '2026-03-13 14:10:43', '2026-03-13 14:31:27'),
(2376, 2, 205, 2, 2, 7, 222, 1, '2026-03-13 14:10:44', NULL),
(2377, 2, 205, 2, 2, 12, 222, 1, '2026-03-13 14:10:44', NULL),
(2379, 2, 179, 2, 2, 14, 222, 1, '2026-03-13 14:10:46', NULL),
(2380, 1, 209, 1, 1, 1, 223, 12, '2026-03-13 14:22:14', '2026-03-13 19:01:31'),
(2381, 2, 210, 2, 2, 2, 223, 1, '2026-03-13 14:22:16', NULL),
(2382, 1, 206, 1, 1, 4, 223, 12, '2026-03-13 14:22:16', '2026-03-20 18:21:18'),
(2383, 2, 206, 2, 2, 7, 223, 1, '2026-03-13 14:22:18', NULL),
(2384, 2, 206, 2, 2, 12, 223, 1, '2026-03-13 14:22:19', NULL),
(2386, 2, 180, 2, 2, 14, 223, 1, '2026-03-13 14:22:20', NULL),
(2387, 1, 210, 2, 2, 1, 224, 1, '2026-03-13 14:30:14', NULL),
(2388, 2, 211, 2, 2, 2, 224, 1, '2026-03-13 14:30:15', NULL),
(2389, 1, 207, 2, 2, 4, 224, 12, '2026-03-13 14:30:17', '2026-03-20 18:20:46'),
(2390, 2, 207, 2, 2, 7, 224, 1, '2026-03-13 14:30:18', NULL),
(2391, 2, 207, 2, 2, 12, 224, 1, '2026-03-13 14:30:20', NULL),
(2393, 2, 181, 2, 2, 14, 224, 1, '2026-03-13 14:30:23', NULL),
(2394, 1, 211, 2, 2, 1, 225, 1, '2026-03-13 17:58:18', NULL),
(2395, 2, 212, 2, 2, 2, 225, 1, '2026-03-13 17:58:20', NULL),
(2396, 2, 208, 2, 2, 4, 225, 1, '2026-03-13 17:58:21', NULL),
(2397, 2, 208, 2, 2, 7, 225, 1, '2026-03-13 17:58:22', NULL),
(2398, 2, 208, 2, 2, 12, 225, 1, '2026-03-13 17:58:24', NULL),
(2400, 2, 182, 2, 2, 14, 225, 1, '2026-03-13 17:58:27', NULL),
(2401, 1, 212, 1, 1, 1, 226, 12, '2026-03-13 19:57:40', '2026-03-13 19:58:38'),
(2402, 2, 213, 2, 2, 2, 226, 1, '2026-03-13 19:57:42', NULL),
(2403, 1, 209, 1, 1, 4, 226, 12, '2026-03-13 19:57:44', '2026-03-13 20:01:33'),
(2404, 2, 209, 2, 2, 7, 226, 1, '2026-03-13 19:57:45', NULL),
(2405, 2, 209, 2, 2, 12, 226, 1, '2026-03-13 19:57:47', NULL),
(2407, 2, 183, 2, 2, 14, 226, 1, '2026-03-13 19:57:51', NULL),
(2408, 1, 213, 2, 2, 1, 227, 1, '2026-03-13 20:00:02', NULL),
(2409, 2, 214, 2, 2, 2, 227, 1, '2026-03-13 20:00:04', NULL),
(2410, 1, 210, 2, 2, 4, 227, 1, '2026-03-13 20:00:05', '2026-03-13 20:01:00'),
(2411, 2, 210, 2, 2, 7, 227, 1, '2026-03-13 20:00:07', NULL),
(2412, 2, 210, 2, 2, 12, 227, 1, '2026-03-13 20:00:08', NULL),
(2414, 2, 184, 2, 2, 14, 227, 1, '2026-03-13 20:00:12', NULL),
(2415, 1, 214, 2, 2, 1, 228, 1, '2026-03-16 13:38:25', NULL),
(2416, 2, 215, 2, 2, 2, 228, 1, '2026-03-16 13:38:27', NULL),
(2417, 1, 211, 2, 2, 4, 228, 1, '2026-03-16 13:38:28', '2026-03-16 13:38:48'),
(2418, 2, 211, 2, 2, 7, 228, 1, '2026-03-16 13:38:29', NULL),
(2419, 2, 211, 2, 2, 12, 228, 1, '2026-03-16 13:38:31', NULL),
(2421, 2, 185, 2, 2, 14, 228, 1, '2026-03-16 13:38:35', NULL),
(2422, 1, 215, 1, 1, 1, 229, 12, '2026-03-16 14:25:29', '2026-03-16 14:25:58'),
(2423, 2, 216, 2, 2, 2, 229, 1, '2026-03-16 14:25:31', NULL),
(2424, 1, 212, 1, 1, 4, 229, 12, '2026-03-16 14:25:32', '2026-03-16 14:26:24'),
(2425, 2, 212, 2, 2, 7, 229, 1, '2026-03-16 14:25:33', NULL),
(2426, 2, 212, 2, 2, 12, 229, 1, '2026-03-16 14:25:34', NULL),
(2428, 1, 186, 1, 1, 14, 229, 12, '2026-03-16 14:25:36', '2026-03-21 14:48:05'),
(2429, 1, 216, 2, 2, 1, 230, 1, '2026-03-21 10:08:02', NULL),
(2430, 2, 217, 2, 2, 2, 230, 1, '2026-03-21 10:08:02', NULL),
(2431, 1, 213, 2, 2, 4, 230, 1, '2026-03-21 10:08:03', '2026-03-21 10:08:17'),
(2432, 2, 213, 2, 2, 7, 230, 1, '2026-03-21 10:08:04', NULL),
(2433, 2, 213, 2, 2, 12, 230, 1, '2026-03-21 10:08:05', NULL),
(2434, 2, 187, 2, 2, 14, 230, 1, '2026-03-21 10:08:06', NULL),
(2435, 1, 217, 2, 2, 1, 231, 1, '2026-03-21 10:19:27', NULL),
(2436, 2, 218, 2, 2, 2, 231, 1, '2026-03-21 10:19:27', NULL),
(2437, 1, 214, 2, 2, 4, 231, 1, '2026-03-21 10:19:27', '2026-03-21 10:26:29'),
(2438, 2, 214, 2, 2, 7, 231, 1, '2026-03-21 10:19:28', NULL),
(2439, 2, 214, 2, 2, 12, 231, 1, '2026-03-21 10:19:28', NULL),
(2440, 2, 188, 2, 2, 14, 231, 1, '2026-03-21 10:19:29', NULL),
(2441, 1, 218, 2, 2, 1, 232, 1, '2026-03-21 10:58:35', NULL),
(2442, 2, 219, 2, 2, 2, 232, 1, '2026-03-21 10:58:36', NULL),
(2443, 1, 215, 2, 2, 4, 232, 1, '2026-03-21 10:58:37', '2026-03-21 10:58:49'),
(2444, 2, 215, 2, 2, 7, 232, 1, '2026-03-21 10:58:38', NULL),
(2445, 2, 215, 2, 2, 12, 232, 1, '2026-03-21 10:58:39', NULL),
(2446, 2, 189, 2, 2, 14, 232, 1, '2026-03-21 10:58:39', NULL),
(2447, 1, 219, 2, 2, 1, 233, 1, '2026-03-21 11:08:54', NULL),
(2448, 2, 220, 2, 2, 2, 233, 1, '2026-03-21 11:08:54', NULL),
(2449, 1, 216, 2, 2, 4, 233, 1, '2026-03-21 11:08:56', '2026-03-21 11:09:08'),
(2450, 2, 216, 2, 2, 7, 233, 1, '2026-03-21 11:08:57', NULL),
(2451, 2, 216, 2, 2, 12, 233, 1, '2026-03-21 11:08:58', NULL),
(2452, 2, 190, 2, 2, 14, 233, 1, '2026-03-21 11:08:59', NULL),
(2453, 1, 220, 2, 2, 1, 234, 1, '2026-03-21 11:18:38', NULL),
(2454, 2, 221, 2, 2, 2, 234, 1, '2026-03-21 11:18:38', NULL),
(2455, 1, 217, 2, 2, 4, 234, 1, '2026-03-21 11:18:39', '2026-03-21 11:18:53'),
(2456, 2, 217, 2, 2, 7, 234, 1, '2026-03-21 11:18:40', NULL),
(2457, 2, 217, 2, 2, 12, 234, 1, '2026-03-21 11:18:41', NULL),
(2458, 2, 191, 2, 2, 14, 234, 1, '2026-03-21 11:18:42', NULL),
(2459, 1, 221, 2, 2, 1, 235, 1, '2026-03-21 11:36:18', NULL),
(2460, 2, 222, 2, 2, 2, 235, 1, '2026-03-21 11:36:20', NULL),
(2461, 1, 218, 2, 2, 4, 235, 1, '2026-03-21 11:36:20', '2026-03-21 11:36:35'),
(2462, 2, 218, 2, 2, 7, 235, 1, '2026-03-21 11:36:22', NULL),
(2463, 2, 218, 2, 2, 12, 235, 1, '2026-03-21 11:36:23', NULL),
(2464, 2, 192, 2, 2, 14, 235, 1, '2026-03-21 11:36:24', NULL),
(2465, 1, 222, 2, 2, 1, 236, 1, '2026-03-21 11:58:29', NULL),
(2466, 2, 223, 2, 2, 2, 236, 1, '2026-03-21 11:58:29', NULL),
(2467, 1, 219, 2, 2, 4, 236, 1, '2026-03-21 11:58:31', '2026-03-21 11:58:47'),
(2468, 2, 219, 2, 2, 7, 236, 1, '2026-03-21 11:58:32', NULL),
(2469, 2, 219, 2, 2, 12, 236, 1, '2026-03-21 11:58:34', NULL),
(2470, 2, 193, 2, 2, 14, 236, 1, '2026-03-21 11:58:35', NULL),
(2471, 1, 223, 2, 2, 1, 237, 1, '2026-03-21 13:20:48', NULL),
(2472, 2, 224, 2, 2, 2, 237, 1, '2026-03-21 13:20:50', NULL),
(2473, 1, 220, 2, 2, 4, 237, 1, '2026-03-21 13:20:52', '2026-03-21 13:21:21'),
(2474, 2, 220, 2, 2, 7, 237, 1, '2026-03-21 13:20:54', NULL),
(2475, 2, 220, 2, 2, 12, 237, 1, '2026-03-21 13:20:55', NULL),
(2476, 2, 194, 2, 2, 14, 237, 1, '2026-03-21 13:20:57', NULL),
(2477, 1, 224, 2, 2, 1, 238, 1, '2026-03-21 13:55:54', NULL),
(2478, 2, 225, 2, 2, 2, 238, 1, '2026-03-21 13:55:56', NULL),
(2479, 1, 221, 2, 2, 4, 238, 1, '2026-03-21 13:55:58', '2026-03-21 13:56:17'),
(2480, 2, 221, 2, 2, 7, 238, 1, '2026-03-21 13:56:00', NULL),
(2481, 2, 221, 2, 2, 12, 238, 1, '2026-03-21 13:56:02', NULL),
(2482, 2, 195, 2, 2, 14, 238, 1, '2026-03-21 13:56:04', NULL),
(2483, 1, 225, 2, 2, 1, 239, 1, '2026-03-21 14:14:37', NULL),
(2484, 2, 226, 2, 2, 2, 239, 1, '2026-03-21 14:14:39', NULL),
(2485, 1, 222, 2, 2, 4, 239, 1, '2026-03-21 14:14:41', '2026-03-21 14:14:58'),
(2486, 2, 222, 2, 2, 7, 239, 1, '2026-03-21 14:14:43', NULL),
(2487, 2, 222, 2, 2, 12, 239, 1, '2026-03-21 14:14:45', NULL),
(2488, 1, 196, 2, 2, 14, 239, 1, '2026-03-21 14:14:47', '2026-03-21 14:15:13'),
(2489, 1, 226, 1, 1, 1, 240, 12, '2026-03-21 14:53:08', '2026-03-21 14:54:45'),
(2490, 2, 227, 2, 2, 2, 240, 1, '2026-03-21 14:53:10', NULL),
(2491, 1, 223, 1, 1, 4, 240, 12, '2026-03-21 14:53:11', '2026-03-21 14:54:09'),
(2492, 2, 223, 2, 2, 7, 240, 1, '2026-03-21 14:53:13', NULL),
(2493, 2, 223, 2, 2, 12, 240, 1, '2026-03-21 14:53:15', NULL),
(2494, 1, 197, 1, 1, 14, 240, 12, '2026-03-21 14:53:17', '2026-03-21 14:55:12'),
(2495, 1, 227, 2, 2, 1, 241, 1, '2026-03-21 15:10:42', NULL),
(2496, 2, 228, 2, 2, 2, 241, 1, '2026-03-21 15:10:44', NULL),
(2497, 1, 224, 2, 2, 4, 241, 1, '2026-03-21 15:10:46', '2026-03-21 15:11:02'),
(2498, 2, 224, 2, 2, 7, 241, 1, '2026-03-21 15:10:48', NULL),
(2499, 2, 224, 2, 2, 12, 241, 1, '2026-03-21 15:10:50', NULL),
(2500, 2, 198, 2, 2, 14, 241, 1, '2026-03-21 15:10:52', NULL),
(2501, 1, 228, 2, 2, 1, 242, 1, '2026-03-21 16:20:35', NULL),
(2502, 2, 229, 2, 2, 2, 242, 1, '2026-03-21 16:20:37', NULL),
(2503, 1, 225, 2, 2, 4, 242, 1, '2026-03-21 16:20:39', '2026-03-21 16:20:55'),
(2504, 2, 225, 2, 2, 7, 242, 1, '2026-03-21 16:20:41', NULL),
(2505, 2, 225, 2, 2, 12, 242, 1, '2026-03-21 16:20:43', NULL),
(2506, 2, 199, 2, 2, 14, 242, 1, '2026-03-21 16:20:45', NULL),
(2507, 1, 1, 1, 2, 15, 1, 1, '2026-03-27 11:43:28', NULL),
(2508, 2, 2, 1, 1, 15, 2, 3, '2026-03-27 11:43:28', '2026-03-27 18:12:08'),
(2509, 2, 3, 2, 2, 15, 3, 1, '2026-03-27 11:43:28', '2026-03-27 18:12:12'),
(2510, 2, 4, 1, 1, 15, 4, 3, '2026-03-27 11:43:28', '2026-03-27 18:12:21'),
(2511, 2, 5, 2, 2, 15, 5, 1, '2026-03-27 11:43:29', '2026-03-27 18:12:29'),
(2512, 2, 6, 2, 2, 15, 6, 1, '2026-03-27 11:43:29', '2026-03-27 18:14:30'),
(2513, 2, 7, 2, 2, 15, 7, 1, '2026-03-27 11:43:29', NULL),
(2514, 2, 8, 2, 2, 15, 8, 1, '2026-03-27 11:43:29', NULL),
(2515, 1, 9, 2, 2, 15, 9, 1, '2026-03-27 11:43:29', NULL),
(2516, 1, 10, 2, 2, 15, 10, 1, '2026-03-27 11:43:29', NULL),
(2517, 1, 11, 2, 2, 15, 11, 1, '2026-03-27 11:43:29', NULL),
(2518, 1, 12, 2, 2, 15, 12, 1, '2026-03-27 11:43:29', NULL),
(2519, 2, 13, 1, 1, 15, 13, 3, '2026-03-27 11:43:29', '2026-03-27 18:13:53'),
(2520, 2, 14, 2, 2, 15, 14, 1, '2026-03-27 11:43:29', NULL),
(2521, 2, 15, 2, 2, 15, 15, 1, '2026-03-27 11:43:29', NULL),
(2522, 2, 16, 2, 2, 15, 16, 1, '2026-03-27 11:43:29', NULL),
(2523, 2, 17, 2, 2, 15, 17, 1, '2026-03-27 11:43:29', NULL),
(2524, 2, 18, 1, 1, 15, 18, 4, '2026-03-27 11:43:29', NULL),
(2525, 2, 19, 2, 2, 15, 19, 1, '2026-03-27 11:43:29', '2026-03-27 18:11:56'),
(2526, 2, 20, 2, 2, 15, 20, 1, '2026-03-27 11:43:29', '2026-03-27 18:12:33'),
(2527, 2, 21, 2, 2, 15, 21, 1, '2026-03-27 11:43:29', NULL),
(2528, 2, 22, 2, 2, 15, 22, 1, '2026-03-27 11:43:29', NULL),
(2529, 2, 23, 2, 2, 15, 23, 1, '2026-03-27 11:43:29', NULL),
(2530, 2, 24, 2, 2, 15, 24, 1, '2026-03-27 11:43:29', NULL),
(2531, 2, 25, 2, 2, 15, 25, 1, '2026-03-27 11:43:29', NULL),
(2532, 2, 26, 2, 2, 15, 26, 1, '2026-03-27 11:43:29', NULL),
(2533, 2, 27, 2, 2, 15, 27, 1, '2026-03-27 11:43:29', NULL),
(2534, 2, 28, 2, 2, 15, 28, 1, '2026-03-27 11:43:29', NULL),
(2535, 2, 29, 1, 1, 15, 29, 3, '2026-03-27 11:43:29', NULL),
(2536, 2, 30, 2, 2, 15, 30, 1, '2026-03-27 11:43:29', NULL),
(2537, 2, 31, 2, 2, 15, 31, 1, '2026-03-27 11:43:30', NULL),
(2538, 2, 32, 2, 2, 15, 32, 1, '2026-03-27 11:43:30', NULL),
(2539, 2, 33, 2, 2, 15, 33, 1, '2026-03-27 11:43:30', NULL),
(2540, 2, 34, 2, 2, 15, 34, 1, '2026-03-27 11:43:30', NULL),
(2541, 2, 35, 2, 2, 15, 35, 1, '2026-03-27 11:43:30', NULL),
(2542, 2, 36, 2, 2, 15, 36, 1, '2026-03-27 11:43:30', NULL),
(2543, 2, 37, 2, 2, 15, 37, 1, '2026-03-27 11:43:30', NULL),
(2544, 2, 38, 2, 2, 15, 38, 1, '2026-03-27 11:43:30', NULL),
(2545, 2, 39, 2, 2, 15, 39, 1, '2026-03-27 11:43:30', NULL),
(2546, 2, 40, 2, 2, 15, 40, 1, '2026-03-27 11:43:30', NULL),
(2547, 2, 41, 2, 2, 15, 41, 1, '2026-03-27 11:43:30', NULL);
INSERT INTO `adms_levels_pages` (`id`, `permission`, `order_level_page`, `print_menu`, `dropdown`, `adms_access_level_id`, `adms_page_id`, `adms_items_menu_id`, `created`, `modified`) VALUES
(2548, 2, 42, 2, 2, 15, 42, 1, '2026-03-27 11:43:30', NULL),
(2549, 2, 43, 2, 2, 15, 43, 1, '2026-03-27 11:43:30', NULL),
(2550, 2, 44, 2, 2, 15, 44, 1, '2026-03-27 11:43:30', NULL),
(2551, 2, 45, 2, 2, 15, 45, 1, '2026-03-27 11:43:30', NULL),
(2552, 2, 46, 2, 2, 15, 46, 1, '2026-03-27 11:43:30', NULL),
(2553, 2, 47, 2, 2, 15, 47, 1, '2026-03-27 11:43:30', NULL),
(2554, 2, 48, 2, 2, 15, 48, 1, '2026-03-27 11:43:30', NULL),
(2555, 2, 49, 2, 2, 15, 49, 1, '2026-03-27 11:43:30', NULL),
(2556, 2, 50, 2, 2, 15, 50, 1, '2026-03-27 11:43:31', NULL),
(2557, 2, 51, 2, 2, 15, 51, 1, '2026-03-27 11:43:31', NULL),
(2558, 2, 52, 2, 2, 15, 52, 1, '2026-03-27 11:43:31', NULL),
(2559, 2, 53, 2, 2, 15, 53, 1, '2026-03-27 11:43:31', NULL),
(2560, 2, 54, 2, 2, 15, 54, 1, '2026-03-27 11:43:31', NULL),
(2561, 2, 55, 2, 2, 15, 55, 1, '2026-03-27 11:43:31', NULL),
(2562, 2, 56, 2, 2, 15, 56, 1, '2026-03-27 11:43:31', NULL),
(2563, 2, 57, 2, 2, 15, 57, 1, '2026-03-27 11:43:31', NULL),
(2564, 2, 58, 2, 2, 15, 58, 1, '2026-03-27 11:43:31', NULL),
(2565, 1, 59, 2, 2, 15, 59, 1, '2026-03-27 11:43:31', NULL),
(2566, 1, 60, 1, 2, 15, 60, 6, '2026-03-27 11:43:31', NULL),
(2567, 1, 61, 2, 2, 15, 61, 1, '2026-03-27 11:43:31', NULL),
(2568, 1, 62, 2, 2, 15, 62, 1, '2026-03-27 11:43:31', NULL),
(2569, 1, 63, 2, 2, 15, 63, 1, '2026-03-27 11:43:31', NULL),
(2570, 1, 64, 2, 2, 15, 64, 1, '2026-03-27 11:43:31', NULL),
(2571, 1, 65, 2, 2, 15, 65, 1, '2026-03-27 11:43:31', NULL),
(2572, 2, 66, 2, 2, 15, 66, 1, '2026-03-27 11:43:32', NULL),
(2573, 2, 67, 2, 2, 15, 67, 1, '2026-03-27 11:43:32', NULL),
(2574, 2, 68, 2, 2, 15, 68, 1, '2026-03-27 11:43:32', NULL),
(2575, 2, 69, 2, 2, 15, 69, 1, '2026-03-27 11:43:32', NULL),
(2576, 2, 70, 2, 2, 15, 70, 1, '2026-03-27 11:43:32', NULL),
(2577, 2, 71, 2, 2, 15, 71, 1, '2026-03-27 11:43:32', NULL),
(2578, 2, 72, 2, 2, 15, 72, 1, '2026-03-27 11:43:32', NULL),
(2579, 2, 73, 2, 2, 15, 73, 1, '2026-03-27 11:43:32', NULL),
(2580, 2, 74, 2, 2, 15, 74, 1, '2026-03-27 11:43:32', NULL),
(2581, 2, 75, 2, 2, 15, 75, 1, '2026-03-27 11:43:32', NULL),
(2582, 2, 76, 2, 2, 15, 76, 1, '2026-03-27 11:43:32', NULL),
(2583, 2, 77, 2, 2, 15, 77, 1, '2026-03-27 11:43:32', NULL),
(2584, 2, 78, 2, 2, 15, 78, 1, '2026-03-27 11:43:32', NULL),
(2585, 2, 79, 2, 2, 15, 79, 1, '2026-03-27 11:43:32', NULL),
(2586, 2, 80, 2, 2, 15, 80, 1, '2026-03-27 11:43:32', NULL),
(2587, 2, 81, 2, 2, 15, 81, 1, '2026-03-27 11:43:32', NULL),
(2588, 2, 82, 2, 2, 15, 128, 1, '2026-03-27 11:43:32', NULL),
(2589, 2, 83, 1, 1, 15, 150, 12, '2026-03-27 11:43:32', '2026-03-27 18:10:06'),
(2590, 2, 84, 2, 2, 15, 154, 1, '2026-03-27 11:43:32', NULL),
(2591, 2, 85, 2, 2, 15, 155, 1, '2026-03-27 11:43:32', NULL),
(2592, 2, 86, 2, 2, 15, 156, 1, '2026-03-27 11:43:33', NULL),
(2593, 2, 87, 2, 2, 15, 157, 1, '2026-03-27 11:43:33', NULL),
(2594, 2, 88, 2, 2, 15, 170, 1, '2026-03-27 11:43:33', NULL),
(2595, 2, 89, 2, 2, 15, 172, 1, '2026-03-27 11:43:33', NULL),
(2596, 2, 90, 2, 2, 15, 173, 1, '2026-03-27 11:43:33', NULL),
(2597, 2, 91, 2, 2, 15, 175, 1, '2026-03-27 11:43:33', NULL),
(2598, 2, 92, 2, 2, 15, 179, 1, '2026-03-27 11:43:33', NULL),
(2599, 2, 93, 2, 2, 15, 193, 1, '2026-03-27 11:43:33', NULL),
(2600, 2, 94, 2, 2, 15, 194, 1, '2026-03-27 11:43:33', NULL),
(2601, 2, 95, 2, 2, 15, 202, 1, '2026-03-27 11:43:33', NULL),
(2602, 2, 96, 2, 2, 15, 203, 1, '2026-03-27 11:43:33', NULL),
(2603, 2, 97, 2, 2, 15, 204, 1, '2026-03-27 11:43:33', NULL),
(2604, 2, 98, 2, 2, 15, 205, 1, '2026-03-27 11:43:33', NULL),
(2605, 2, 99, 2, 2, 15, 206, 1, '2026-03-27 11:43:33', NULL),
(2606, 2, 100, 2, 2, 15, 207, 1, '2026-03-27 11:43:33', NULL),
(2607, 2, 101, 2, 2, 15, 208, 1, '2026-03-27 11:43:33', NULL),
(2608, 2, 102, 2, 2, 15, 209, 1, '2026-03-27 11:43:33', NULL),
(2609, 2, 103, 2, 2, 15, 210, 1, '2026-03-27 11:43:33', NULL),
(2610, 2, 104, 2, 2, 15, 222, 1, '2026-03-27 11:43:33', NULL),
(2611, 2, 105, 2, 2, 15, 223, 1, '2026-03-27 11:43:33', NULL),
(2612, 2, 106, 2, 2, 15, 224, 1, '2026-03-27 11:43:33', NULL),
(2613, 2, 107, 2, 2, 15, 225, 1, '2026-03-27 11:43:33', NULL),
(2614, 2, 108, 2, 2, 15, 226, 1, '2026-03-27 11:43:33', NULL),
(2615, 2, 109, 2, 2, 15, 227, 1, '2026-03-27 11:43:33', NULL),
(2616, 2, 110, 2, 2, 15, 228, 1, '2026-03-27 11:43:33', NULL),
(2617, 2, 111, 2, 2, 15, 229, 1, '2026-03-27 11:43:33', NULL),
(2618, 1, 112, 2, 2, 15, 230, 1, '2026-03-27 11:43:33', '2026-03-27 19:08:31'),
(2619, 2, 113, 2, 2, 15, 231, 1, '2026-03-27 11:43:33', NULL),
(2620, 2, 114, 2, 2, 15, 232, 1, '2026-03-27 11:43:33', NULL),
(2621, 1, 115, 2, 2, 15, 233, 12, '2026-03-27 11:43:33', '2026-03-27 18:18:14'),
(2622, 2, 116, 2, 2, 15, 234, 1, '2026-03-27 11:43:34', NULL),
(2623, 2, 117, 2, 2, 15, 235, 1, '2026-03-27 11:43:34', NULL),
(2624, 2, 118, 2, 2, 15, 236, 1, '2026-03-27 11:43:34', NULL),
(2625, 2, 119, 2, 2, 15, 237, 1, '2026-03-27 11:43:34', NULL),
(2626, 2, 120, 2, 2, 15, 238, 1, '2026-03-27 11:43:34', NULL),
(2627, 2, 121, 2, 2, 15, 239, 1, '2026-03-27 11:43:34', NULL),
(2628, 2, 122, 2, 2, 15, 240, 1, '2026-03-27 11:43:34', NULL),
(2629, 2, 123, 2, 2, 15, 241, 1, '2026-03-27 11:43:34', NULL),
(2630, 2, 124, 2, 2, 15, 242, 1, '2026-03-27 11:43:34', NULL),
(2631, 1, 229, 2, 2, 1, 243, 1, '2026-03-27 12:39:21', NULL),
(2632, 2, 230, 2, 2, 2, 243, 1, '2026-03-27 12:39:22', NULL),
(2633, 2, 226, 2, 2, 4, 243, 1, '2026-03-27 12:39:24', NULL),
(2634, 2, 226, 2, 2, 7, 243, 1, '2026-03-27 12:39:26', NULL),
(2635, 2, 226, 2, 2, 12, 243, 1, '2026-03-27 12:39:27', NULL),
(2636, 2, 200, 2, 2, 14, 243, 1, '2026-03-27 12:39:28', NULL),
(2637, 1, 125, 1, 1, 15, 243, 12, '2026-03-27 12:39:29', '2026-03-27 12:39:57'),
(2638, 1, 230, 2, 2, 1, 244, 1, '2026-03-29 11:20:23', NULL),
(2639, 2, 231, 2, 2, 2, 244, 1, '2026-03-29 11:20:25', NULL),
(2640, 1, 227, 2, 2, 4, 244, 1, '2026-03-29 11:20:28', '2026-03-29 11:21:07'),
(2641, 2, 227, 2, 2, 7, 244, 1, '2026-03-29 11:20:30', NULL),
(2642, 2, 227, 2, 2, 12, 244, 1, '2026-03-29 11:20:33', NULL),
(2643, 2, 201, 2, 2, 14, 244, 1, '2026-03-29 11:20:35', NULL),
(2644, 2, 126, 2, 2, 15, 244, 1, '2026-03-29 11:20:37', NULL),
(2645, 1, 231, 2, 2, 1, 245, 12, '2026-04-01 10:54:43', '2026-04-01 11:40:16'),
(2646, 2, 232, 2, 2, 2, 245, 1, '2026-04-01 10:54:45', NULL),
(2647, 1, 228, 2, 2, 4, 245, 12, '2026-04-01 10:54:48', '2026-04-01 11:40:49'),
(2648, 2, 228, 2, 2, 7, 245, 1, '2026-04-01 10:54:51', NULL),
(2649, 2, 228, 2, 2, 12, 245, 1, '2026-04-01 10:54:53', NULL),
(2650, 2, 202, 2, 2, 14, 245, 1, '2026-04-01 10:54:56', NULL),
(2651, 2, 127, 2, 2, 15, 245, 1, '2026-04-01 10:54:58', NULL),
(2652, 1, 232, 1, 1, 1, 246, 12, '2026-04-01 11:35:39', '2026-04-01 11:40:36'),
(2653, 2, 233, 2, 2, 2, 246, 1, '2026-04-01 11:35:42', NULL),
(2654, 1, 229, 1, 1, 4, 246, 12, '2026-04-01 11:35:44', '2026-04-01 11:41:10'),
(2655, 2, 229, 2, 2, 7, 246, 1, '2026-04-01 11:35:46', NULL),
(2656, 2, 229, 2, 2, 12, 246, 1, '2026-04-01 11:35:48', NULL),
(2657, 2, 203, 2, 2, 14, 246, 1, '2026-04-01 11:35:51', NULL),
(2658, 2, 128, 2, 2, 15, 246, 1, '2026-04-01 11:35:53', NULL),
(2659, 1, 233, 2, 2, 1, 247, 1, '2026-04-01 11:37:20', NULL),
(2660, 2, 234, 2, 2, 2, 247, 1, '2026-04-01 11:37:22', NULL),
(2661, 1, 230, 2, 2, 4, 247, 1, '2026-04-01 11:37:24', '2026-04-01 11:37:44'),
(2662, 2, 230, 2, 2, 7, 247, 1, '2026-04-01 11:37:27', NULL),
(2663, 2, 230, 2, 2, 12, 247, 1, '2026-04-01 11:37:29', NULL),
(2664, 2, 204, 2, 2, 14, 247, 1, '2026-04-01 11:37:31', NULL),
(2665, 2, 129, 2, 2, 15, 247, 1, '2026-04-01 11:37:34', NULL),
(2666, 1, 234, 2, 2, 1, 248, 1, '2026-04-02 08:40:07', NULL),
(2667, 2, 235, 2, 2, 2, 248, 1, '2026-04-02 08:40:09', NULL),
(2668, 1, 231, 2, 2, 4, 248, 1, '2026-04-02 08:40:12', '2026-04-02 08:40:32'),
(2669, 2, 231, 2, 2, 7, 248, 1, '2026-04-02 08:40:14', NULL),
(2670, 2, 231, 2, 2, 12, 248, 1, '2026-04-02 08:40:16', NULL),
(2671, 2, 205, 2, 2, 14, 248, 1, '2026-04-02 08:40:18', NULL),
(2672, 2, 130, 2, 2, 15, 248, 1, '2026-04-02 08:40:20', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_pages`
--

DROP TABLE IF EXISTS `adms_pages`;
CREATE TABLE IF NOT EXISTS `adms_pages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `controller` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `metodo` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_controller` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_metodo` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_page` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish` int NOT NULL DEFAULT '2',
  `icon` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `adms_sits_pgs_id` int NOT NULL DEFAULT '2',
  `adms_types_pgs_id` int NOT NULL,
  `adms_groups_pgs_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adms_groups_pgs_id` (`adms_groups_pgs_id`),
  KEY `adms_sits_pgs_id` (`adms_sits_pgs_id`),
  KEY `adms_types_pgs_id` (`adms_types_pgs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_pages`
--

INSERT INTO `adms_pages` (`id`, `controller`, `metodo`, `menu_controller`, `menu_metodo`, `name_page`, `publish`, `icon`, `obs`, `adms_sits_pgs_id`, `adms_types_pgs_id`, `adms_groups_pgs_id`, `created`, `modified`) VALUES
(1, 'Dashboard', 'index', 'dashboard', 'index', 'Dashboard', 2, 'fa-solid fa-chart-line', '', 1, 1, 8, '2022-05-23 12:23:25', '2023-09-19 10:16:42'),
(2, 'ListUsers', 'index', 'list-users', 'index', 'Listar', 2, '', 'Visualizar Usuarios', 1, 1, 1, '2022-05-23 20:54:57', '2025-08-16 06:50:58'),
(3, 'ViewUsers', 'index', 'view-users', 'index', 'Visualizar Usuário', 2, '', '', 1, 1, 2, '2022-05-23 20:57:08', '2022-05-23 21:11:44'),
(4, 'AddUsers', 'index', 'add-users', 'index', 'Cadastrar', 2, '', 'Cadastrar Usuarios', 1, 1, 3, '2022-05-23 21:44:34', '2025-08-16 06:51:41'),
(5, 'EditUsers', 'index', 'edit-users', 'index', 'Editar Usuário', 2, '', '', 1, 1, 4, '2022-05-23 21:46:37', '2022-05-23 21:49:45'),
(6, 'EditUsersPassword', 'index', 'edit-users-password', 'index', 'Editar Senha do Usuário', 2, '', '', 1, 1, 4, '2022-05-23 22:21:43', NULL),
(7, 'EditUsersImage', 'index', 'edit-users-image', 'index', 'Editar Foto do Usuário', 2, '', '', 1, 1, 4, '2022-05-23 22:22:40', NULL),
(8, 'DeleteUsers', 'index', 'delete-users', 'index', 'Apagar Usuário', 2, '', '', 1, 1, 5, '2022-05-23 22:23:15', NULL),
(9, 'ViewProfile', 'index', 'view-profile', 'index', 'Visualizar Perfil', 2, '', '', 1, 1, 2, '2022-05-23 22:23:52', NULL),
(10, 'EditProfile', 'index', 'edit-profile', 'index', 'Editar Perfil', 2, '', '', 1, 1, 4, '2022-05-23 22:24:23', NULL),
(11, 'EditProfilePassword', 'index', 'edit-profile-password', 'index', 'Editar Senha no Perfil', 2, '', '', 1, 1, 4, '2022-05-23 22:25:09', NULL),
(12, 'EditProfileImage', 'index', 'edit-profile-image', 'index', 'Editar Foto no Perfil', 2, '', '', 1, 1, 4, '2022-05-23 22:28:47', NULL),
(13, 'ListSitsUsers', 'index', 'list-sits-users', 'index', 'Situação', 2, '', 'Cadastrar a situação do usuario', 1, 1, 1, '2022-05-23 22:29:32', '2025-08-16 06:53:13'),
(14, 'AddSitsUsers', 'index', 'add-sits-users', 'index', 'Cadastrar Situação para Usuários', 2, '', '', 1, 1, 3, '2022-05-23 22:31:51', NULL),
(15, 'EditSitsUsers', 'index', 'edit-sits-users', 'index', 'Editar Situação para Usuários', 2, '', '', 1, 1, 4, '2022-05-23 22:38:19', NULL),
(16, 'ViewSitsUsers', 'index', 'view-sits-users', 'index', 'Visualizar Situação para Usuários', 2, '', '', 1, 1, 2, '2022-05-23 22:39:16', NULL),
(17, 'DeleteSitsUsers', 'index', 'delete-sits-users', 'index', 'Apagar Situação para Usuários', 2, '', '', 1, 1, 5, '2022-05-23 22:39:55', NULL),
(18, 'ListColors', 'index', 'list-colors', 'index', 'Cores', 2, 'fa-solid fa-palette', '', 1, 1, 1, '2022-05-23 22:40:46', '2023-08-03 14:40:08'),
(19, 'ViewColors', 'index', 'view-colors', 'index', 'Visualizar Cores', 2, '', '', 1, 1, 2, '2022-05-23 22:41:48', NULL),
(20, 'AddColors', 'index', 'add-colors', 'index', 'Cadastrar Cores', 2, '', '', 1, 1, 3, '2022-05-23 22:42:19', NULL),
(21, 'EditColors', 'index', 'edit-colors', 'index', 'Editar Cores', 2, '', '', 1, 1, 4, '2022-05-23 22:42:49', NULL),
(22, 'DeleteColors', 'index', 'delete-colors', 'index', 'Apagar Cores', 2, '', '', 1, 1, 5, '2022-05-23 22:43:45', NULL),
(23, 'ListConfEmails', 'index', 'list-conf-emails', 'index', 'E-mail', 2, 'fa-solid fa-envelope', '', 1, 1, 1, '2022-05-23 22:45:51', '2023-08-11 11:41:56'),
(24, 'AddConfEmails', 'index', 'add-conf-emails', 'index', 'Cadastrar Configurações de E-mail', 2, '', '', 1, 1, 3, '2022-05-23 22:46:36', NULL),
(25, 'ViewConfEmails', 'index', 'view-conf-emails', 'index', 'Visualizar Configurações de E-mail', 2, '', '', 1, 1, 2, '2022-05-23 22:47:28', NULL),
(26, 'EditConfEmails', 'index', 'edit-conf-emails', 'index', 'Editar Configurações de E-mail', 2, '', '', 1, 1, 4, '2022-05-23 22:48:06', NULL),
(27, 'EditConfEmailsPassword', 'index', 'edit-conf-emails-password', 'index', 'Editar Senha da Configurações de E-mail', 2, '', '', 1, 1, 4, '2022-05-23 22:49:31', '2023-07-30 11:50:15'),
(28, 'DeleteConfEmails', 'index', 'delete-conf-emails', 'index', 'Apagar Configurações de E-mail', 2, '', '', 1, 1, 5, '2022-05-23 22:50:16', NULL),
(29, 'ListAccessLevels', 'index', 'list-access-levels', 'index', 'Nível de Acesso', 2, '', 'Nível de Acesso do usurário', 1, 1, 1, '2022-05-23 22:52:24', '2025-08-16 06:54:22'),
(30, 'ViewAccessLevels', 'index', 'view-access-levels', 'index', 'Visualizar Nível de Acesso', 2, '', '', 1, 1, 2, '2022-05-23 22:53:17', NULL),
(31, 'AddAccessLevels', 'index', 'add-access-levels', 'index', 'Cadastrar Nível de Acesso', 2, '', '', 1, 1, 3, '2022-05-23 22:53:57', NULL),
(32, 'EditAccessLevels', 'index', 'edit-access-levels', 'index', 'Editar Nível de Acesso', 2, '', '', 1, 1, 4, '2022-05-23 22:55:00', NULL),
(33, 'DeleteAccessLevels', 'index', 'delete-access-levels', 'index', 'Apagar Nível de Acesso', 2, '', '', 1, 1, 5, '2022-05-23 22:55:53', NULL),
(34, 'ViewLevelsForms', 'index', 'view-levels-forms', 'index', 'Cadastrar Nível de Acesso Novo Usuário na Página de Login', 2, 'fas fa-user-cog', '', 1, 1, 2, '2022-05-23 22:58:45', '2022-05-23 22:58:56'),
(35, 'EditLevelsForms', 'index', 'edit-levels-forms', 'index', 'Editar Nível de Acesso Novo Usuário na Página de Login', 2, '', '', 1, 1, 4, '2022-05-23 23:00:27', NULL),
(36, 'OrderAccessLevels', 'index', 'order-access-levels', 'index', 'Alterar Ordem Nível de Acesso', 2, '', '', 1, 1, 6, '2022-05-23 23:15:28', NULL),
(37, 'ListTypesPages', 'index', 'list-types-pages', 'index', 'Tipo de Página', 2, 'fa-regular fa-file-zipper', '', 1, 1, 1, '2022-05-23 23:16:45', '2023-08-03 14:44:26'),
(38, 'ViewTypesPages', 'index', 'view-types-pages', 'index', 'Visualizar Tipo de Página', 2, '', '', 1, 1, 2, '2022-05-23 23:17:41', NULL),
(39, 'AddTypesPages', 'index', 'add-types-pages', 'index', 'Cadastrar Tipo de Página', 2, '', '', 1, 1, 3, '2022-05-23 23:18:35', NULL),
(40, 'EditTypesPages', 'index', 'edit-types-pages', 'index', 'Tipos de Páginas', 2, 'fa-regular fa-file-zipper', '', 1, 1, 4, '2022-05-23 23:19:12', '2023-08-03 14:41:18'),
(41, 'DeleteTypesPages', 'index', 'delete-types-pages', 'index', 'Apagar Tipo de Página', 2, '', '', 1, 1, 5, '2022-05-23 23:20:23', NULL),
(42, 'OrderTypesPages', 'index', 'order-types-pages', 'index', 'Alterar Ordem Tipo de Página', 2, '', '', 1, 1, 6, '2022-05-23 23:21:25', NULL),
(43, 'ListSitsPages', 'index', 'list-sits-pages', 'index', 'Situações das Páginas', 2, 'fa-solid fa-file-circle-question', '', 1, 1, 1, '2022-05-23 23:22:44', '2023-08-03 12:01:35'),
(44, 'ViewSitsPages', 'index', 'view-sits-pages', 'index', 'Visualizar Situação para Páginas', 2, '', '', 1, 1, 2, '2022-05-23 23:24:02', NULL),
(45, 'AddSitsPages', 'index', 'add-sits-pages', 'index', 'Cadastrar Situação para Páginas', 2, '', '', 1, 1, 3, '2022-05-23 23:25:28', NULL),
(46, 'EditSitsPages', 'index', 'edit-sits-pages', 'index', 'Editar Situação para Páginas', 2, '', '', 1, 1, 4, '2022-05-23 23:26:08', NULL),
(47, 'DeleteSitsPages', 'index', 'delete-sits-pages', 'index', 'Apagar Situação para Páginas', 2, '', '', 1, 1, 5, '2022-05-23 23:26:53', NULL),
(48, 'ListGroupsPages', 'index', 'list-groups-pages', 'index', 'Grupo de Páginas', 2, 'fa-solid fa-file-lines', '', 1, 1, 1, '2022-05-23 23:27:35', '2023-08-16 12:12:34'),
(49, 'ViewGroupsPages', 'index', 'view-groups-pages', 'index', 'Visualizar Grupo de Páginas', 2, '', '', 1, 1, 2, '2022-05-23 23:28:36', NULL),
(50, 'AddGroupsPages', 'index', 'add-groups-pages', 'index', 'Cadastrar Grupo de Páginas', 2, '', '', 1, 1, 3, '2022-05-23 23:29:48', NULL),
(51, 'EditGroupsPages', 'index', 'edit-groups-pages', 'index', 'Editar Grupo de Páginas', 2, '', '', 1, 1, 4, '2022-05-23 23:30:32', NULL),
(52, 'DeleteGroupsPages', 'index', 'delete-groups-pages', 'index', 'Apagar Grupo de Páginas', 2, '', '', 1, 1, 5, '2022-05-23 23:31:10', NULL),
(53, 'OrderGroupsPages', 'index', 'order-groups-pages', 'index', 'Alterar Ordem Grupo de Páginas', 2, '', '', 1, 1, 6, '2022-05-23 23:32:37', NULL),
(54, 'ListPages', 'index', 'list-pages', 'index', 'Páginas', 2, 'fa-solid fa-file', '', 1, 1, 1, '2022-05-23 23:33:48', '2023-08-03 14:39:05'),
(55, 'ViewPages', 'index', 'view-pages', 'index', 'Visualizar Páginas', 2, '', '', 1, 1, 2, '2022-05-23 23:36:59', NULL),
(56, 'AddPages', 'index', 'add-pages', 'index', 'Cadastrar Páginas', 2, '', '', 1, 1, 3, '2022-05-23 23:38:14', NULL),
(57, 'EditPages', 'index', 'edit-pages', 'index', 'Editar Páginas', 2, '', '', 1, 1, 4, '2022-05-23 23:38:47', '2023-07-30 10:59:17'),
(58, 'DeletePages', 'index', 'delete-pages', 'index', 'Apagar Páginas', 2, '', '', 1, 1, 5, '2022-05-23 23:39:31', '2023-07-30 10:58:14'),
(59, 'Login', 'index', 'login', 'index', 'Login', 1, '', '', 1, 1, 7, '2022-05-23 23:41:13', '2022-05-26 14:56:14'),
(60, 'Logout', 'index', 'logout', 'index', 'Sair', 2, 'fa-solid fa-arrow-right-from-bracket', '', 1, 1, 7, '2022-05-23 23:41:41', '2023-08-01 10:33:00'),
(61, 'NewUser', 'index', 'new-user', 'index', 'Cadastrar Usuário na Página de Login', 1, '', '', 1, 1, 7, '2022-05-23 23:42:30', NULL),
(62, 'ConfEmail', 'index', 'conf-email', 'index', 'Confirmar E-mail', 1, '', '', 1, 1, 7, '2022-05-23 23:43:32', NULL),
(63, 'NewConfEmail', 'index', 'new-conf-email', 'index', 'Novo Link para Confirmar E-mail', 1, '', '', 1, 1, 7, '2022-05-23 23:44:27', NULL),
(64, 'RecoverPassword', 'index', 'recover-password', 'index', 'Recuperar Senha', 1, '', '', 1, 1, 7, '2022-05-23 23:45:25', NULL),
(65, 'UpdatePassword', 'index', 'index', 'index', 'Editar Senha na Página de Login', 1, 'assss', 'teste', 1, 1, 8, '2022-05-23 23:50:15', '2023-07-18 09:09:02'),
(66, 'SyncPagesLevels', 'index', 'sync-pages-levels', 'index', 'Sincronizar nivel de acesso e página', 2, '', '', 1, 1, 8, '2023-07-22 11:54:17', NULL),
(67, 'EditPermission', 'index', 'edit-permission', 'index', 'Editar Permissão', 2, '', '', 1, 1, 4, '2023-07-25 11:33:51', NULL),
(68, 'ListPermission', 'index', 'list-permission', 'index', 'Listar Permisões', 2, 'fa-solid fa-house-lock', '', 1, 1, 1, '2023-07-25 11:38:19', NULL),
(69, 'EditPrintMenu', 'index', 'edit-print-menu', 'index', 'Liberar item de menu', 2, '', '', 1, 1, 4, '2023-08-01 16:15:25', '2023-08-02 11:55:15'),
(70, 'OrderPageMenu', 'index', 'order-page-menu', 'index', 'Alterar ordem do item de menu', 2, '', '', 1, 1, 6, '2023-08-05 10:40:45', '2023-08-05 10:42:01'),
(71, 'ViewLevelsForms', 'index', 'view-levels-forms', 'index', 'Configuração ', 2, 'fa-solid fa-user-gear', '', 1, 1, 4, '2023-08-07 10:37:42', '2023-08-07 10:38:19'),
(72, 'EditLevelsForms', 'index', 'edit-levels-forms', 'index', 'Editar Configurações', 2, '', '', 1, 1, 4, '2023-08-07 10:45:40', NULL),
(73, 'ListItemMenu', 'index', 'list-item-menu', 'index', 'Itens de Menu', 2, 'fa-solid fa-bars', '', 1, 1, 1, '2023-08-07 11:25:14', '2023-08-07 11:48:19'),
(74, 'AddItemMenu', 'index', 'add-item-menu', 'index', 'Cadastrar Item de Menu', 2, '', '', 1, 1, 3, '2023-08-07 11:31:58', '2023-08-07 12:02:26'),
(75, 'DeleteItemMenu', 'index', 'delete-item-menu', 'index', 'Apagar item de menu', 2, '', '', 1, 1, 5, '2023-08-07 12:04:51', NULL),
(76, 'EditItemMenu', 'index', 'edit-item-menu', 'index', 'Editar item de menu', 2, '', '', 1, 1, 4, '2023-08-07 12:06:09', NULL),
(77, 'OrderItemMenu', 'index', 'order-item-menu', 'index', 'Alterar ordem do menu', 2, '', '', 1, 1, 6, '2023-08-07 12:07:57', NULL),
(78, 'ViewItemMenu', 'index', 'view-item-menu', 'index', 'Visualizar item de menu', 2, '', '', 1, 1, 2, '2023-08-07 12:09:53', NULL),
(79, 'EditPageMenu', 'index', 'edit-page-menu', 'index', 'Editar página no item de menu', 2, '', '', 1, 1, 4, '2023-08-12 11:54:33', NULL),
(80, 'EditDropdownMenu', 'index', 'edit-dropdown-menu', 'index', 'Editar página dropdown', 2, '', '', 1, 1, 4, '2023-08-12 20:23:51', '2023-08-12 20:29:41'),
(81, 'EditProfileImage', 'index', 'edit-profile-image', 'index', 'Salvar Imagem no BD', 2, '', '', 1, 1, 3, '2023-08-20 12:54:33', NULL),
(128, 'EditProfileLogo', 'index', 'edit-profile-logo', 'index', 'Editar Logo do Contrato', 2, '', '', 1, 1, 4, '2023-12-23 07:15:47', NULL),
(150, 'RelatListCham', 'index', 'relat-list-cham', 'index', 'Tickets', 2, '', 'Relatório de tickets', 1, 3, 1, '2025-01-13 07:51:08', '2025-08-16 06:58:07'),
(154, 'ViewEmpPrincipal', 'index', 'view-emp-principal', 'index', 'Visualizar Empresa', 2, 'fa-solid fa-industry', 'Dados da empresa', 1, 1, 2, '2025-02-13 07:17:19', '2025-02-25 16:40:43'),
(155, 'EditEmpPrincipal', 'index', 'edit-emp-principal', 'index', 'Editar Dados da Empresa', 2, '', 'Edita os ados da empresa principal', 1, 1, 4, '2025-02-13 07:50:11', '2025-02-13 08:14:49'),
(156, 'ListEmpPrincipal', 'index', 'list-emp-principal', 'index', 'Listar Empresas', 2, '', 'Cadastro das empresas ', 1, 1, 1, '2025-02-18 08:33:23', '2025-08-16 06:45:28'),
(157, 'AddEmpPrincipal', 'index', 'add-emp-principal', 'index', 'Cadastro Empresas', 2, '', 'Cadastro de empresas', 1, 1, 3, '2025-02-18 11:54:33', NULL),
(170, 'ListContato', 'index', 'list-contato', 'index', 'Listar Mensagens', 2, '', 'Recuperação de senha e usuário do sistema', 1, 1, 1, '2025-07-02 10:28:05', '2025-10-19 08:20:45'),
(172, 'ViewContato', 'index', 'view-contato', 'index', 'Visualizar Mensagens', 2, '', 'Visualização de mensagens', 1, 1, 2, '2025-07-02 10:31:07', NULL),
(173, 'DeleteMensagem', 'index', 'delete-mensagem', 'index', 'Apagar Mensagem', 2, '', 'Apagar mensagens recebidas', 1, 1, 5, '2025-07-03 15:10:35', NULL),
(175, 'EditContato', 'index', 'edit-contato', 'index', 'Editar Mensagem', 2, '', 'Editar mensagem recebida para alterar senha', 1, 1, 4, '2025-08-04 10:52:34', NULL),
(179, 'EditContato', 'index', 'edit-contato', 'index', 'Cadastrar', 2, '', 'Editar a mensagem recebida', 1, 1, 4, '2025-08-12 10:53:07', NULL),
(193, 'EditAviso', 'index', 'edit-aviso', 'index', 'Editar Aviso', 2, '', 'Editar o aviso da tela de login', 1, 1, 4, '2025-10-19 08:18:15', NULL),
(194, 'ListAviso', 'index', 'list-aviso', 'index', 'Listar Aviso', 2, '', 'Listar aviso cadastrado para a tela de login', 1, 1, 1, '2025-10-19 08:19:35', NULL),
(202, 'CronVencimento', 'index', 'cron-vencimento', 'index', 'Exportar Relatorios', 2, '', 'Envio de Relatórios Semanais automaticamente', 1, 1, 1, '2026-01-13 08:32:00', NULL),
(203, 'Chat', 'index', 'chat', 'index', 'Chat', 2, '', 'Chat para troca de mensagens on-line entre colaboradores da mesma empresa principal', 1, 1, 4, '2026-01-16 05:02:26', NULL),
(204, 'Chat', 'carregarMensagens', 'chat', 'carregarMensagens', 'Carregar Mensagen', 2, '', '', 1, 1, 7, '2026-01-16 05:08:12', NULL),
(205, 'Chat', 'enviar', 'chat', 'index', 'Enviar Mensagens', 2, '', '', 1, 1, 7, '2026-01-16 05:10:22', NULL),
(206, 'Chat', 'verificarNovas', 'chat', 'index', 'Verificar Novas Mensagens', 2, '', '', 1, 1, 7, '2026-01-16 05:12:13', NULL),
(207, 'Chat', 'verificarNovas', 'chat', 'index', 'Verificar Notificações Chat', 2, '', '', 1, 1, 7, '2026-01-16 05:14:09', NULL),
(208, 'Chat', 'limparHistorico', 'chat', 'index', 'Limpar Histórico Chat', 2, '', '', 1, 1, 7, '2026-01-16 05:15:44', NULL),
(209, 'Chat', 'editar', 'chat', 'index', 'Editar Mensagem Chat', 2, '', '', 1, 1, 7, '2026-01-16 05:17:29', NULL),
(210, 'Chat', 'apagarMensagem', 'chat', 'index', 'Apagar Mensagem Chat', 2, '', '', 1, 1, 7, '2026-01-16 05:19:11', NULL),
(222, 'AddAtletas', 'index', 'add-atletas', 'index', 'Cadastrar Atleta', 2, '', 'Cadastramento de atletas', 1, 1, 3, '2026-03-13 11:10:38', NULL),
(223, 'ListAtletas', 'index', 'list-atletas', 'index', 'Atletas', 2, 'fa-solid fa-table-tennis-paddle-ball', 'Listagem de Atletas', 1, 1, 1, '2026-03-13 11:22:11', '2026-03-20 08:12:16'),
(224, 'EditAtleta', 'index', 'edit-atleta', 'index', 'Editar Atleta', 2, '', 'Editar os dados do atleta', 1, 1, 4, '2026-03-13 11:30:11', NULL),
(225, 'ViewAtleta', 'index', 'view-atleta', 'index', 'Visualizar atletas', 2, '', 'Visualização do atleta', 1, 1, 2, '2026-03-13 14:58:15', NULL),
(226, 'ListCompeticoes', 'index', 'list-competicoes', 'index', 'Competições', 2, 'fa-solid fa-trophy', 'Listagem de competições', 1, 1, 1, '2026-03-13 16:57:36', '2026-03-20 08:52:35'),
(227, 'AddCompeticoes', 'index', 'add-competicoes', 'index', 'Cadastramento de competições', 2, '', 'Cadastramento de competições', 1, 1, 3, '2026-03-13 16:59:59', NULL),
(228, 'AddPartidas', 'index', 'add.partidas', 'index', 'Partidas', 2, '', 'Cadastramento das partidas', 1, 1, 3, '2026-03-16 10:38:21', NULL),
(229, 'Ranking', 'index', 'ranking', 'index', 'Ranking', 2, 'fa-solid fa-ranking-star', 'Ranking Geral', 1, 1, 2, '2026-03-16 11:25:26', NULL),
(230, 'ViewCompeticao', 'index', 'view-competicao', 'index', 'Visualizar Competições', 2, '', 'Visualizar os dados da copmpetição', 1, 1, 2, '2026-03-21 07:07:57', NULL),
(231, 'DeletePartida', 'index', 'delete-partida', 'index', 'Deletar Partida', 2, '', 'Apagar a partida e recalcular as pontuações', 1, 1, 5, '2026-03-21 07:19:24', NULL),
(232, 'GerarPdfSumula', 'index', 'gerar-pdf-sumula', 'index', 'Gerar Sumula', 2, '', 'Gerar sumula da partida', 1, 1, 9, '2026-03-21 07:58:32', NULL),
(233, 'EditPartida', 'index', 'edit-partida', 'index', 'Editar Partida', 2, '', 'Editar Partidas', 1, 1, 4, '2026-03-21 08:08:50', NULL),
(234, 'SorteioGrupos', 'index', 'sorteio-grupos', 'index', 'Sorteio de Grupos', 2, '', 'Sorteio do grupo do torneio', 1, 1, 3, '2026-03-21 08:18:34', '2026-03-21 08:20:38'),
(235, 'GerarAgenda', 'index', 'gerar-agenda', 'index', 'Gerar Agenda Jogos', 2, '', 'Gerar a agenda dos jogos', 1, 1, 3, '2026-03-21 08:36:16', NULL),
(236, 'GerarMataMata', 'index', 'gerar-mata-mata', 'index', 'Gerar M<ata Mata', 2, '', 'Gerar o Mata Mata do torneio', 1, 1, 3, '2026-03-21 08:58:25', NULL),
(237, 'AvancarMataMata', 'index', 'avancar-mata-mata', 'index', 'Avança Mata Mata', 2, '', 'Avançar o mata mata', 1, 1, 3, '2026-03-21 10:20:43', NULL),
(238, 'GerarFichasPdf', 'index', 'gerar-fichas-pdf', 'index', 'Gerar Sumula Individual', 2, '', 'Gerar sumula individual para lançamento dos placares dos jogos', 1, 1, 3, '2026-03-21 10:55:51', NULL),
(239, 'PerfilAtleta', 'index', 'perfil-atleta', 'index', 'Perfil Atleta', 2, '', 'Perfil do atleta', 1, 1, 2, '2026-03-21 11:14:34', NULL),
(240, 'InscricaoAtleta', 'index', 'inscricao-atleta', 'index', 'Inscrição torneio', 2, 'fa-solid fa-address-card', 'Inscrição dos atletas na competição', 1, 1, 3, '2026-03-21 11:53:04', '2026-03-22 05:55:27'),
(241, 'AltStatusInscricao', 'index', 'alt-status-inscricao', 'index', 'Cancelar Inscrição', 2, '', 'Bloquear ou liberar inscriçoes na competição', 1, 1, 4, '2026-03-21 12:10:38', NULL),
(242, 'GerenciarInscricoes', 'index', 'gerenciar-inscricoes', 'index', 'Gerenciar Inscrições', 2, '', 'Gerenciador de inscrições', 1, 1, 4, '2026-03-21 13:20:32', '2026-03-22 05:55:37'),
(243, 'MeusJogos', 'index', 'meus-jogos', 'index', 'Meus Jogos', 2, '', 'Apresentação dos jogos para arbitragem', 1, 1, 4, '2026-03-27 09:39:17', NULL),
(244, 'ViewChave', 'index', 'view-chave', 'index', 'Visualizar Chaveamento', 2, '', 'Visualizar Chaveamento', 1, 1, 2, '2026-03-29 08:20:19', NULL),
(245, 'AddCategoria', 'index', 'add-categoria', 'index', 'Categorias', 2, '', 'Cadstro de categorias de competições', 1, 1, 3, '2026-04-01 07:54:38', '2026-04-01 08:39:40'),
(246, 'ListCategorias', 'index', 'list-categorias', 'index', 'Listagem de Categorias', 2, 'fa-solid fa-cubes-stacked', 'Listagem das categorias de competições', 1, 1, 1, '2026-04-01 08:35:34', '2026-04-01 08:39:53'),
(247, 'EditCategoria', 'index', 'edit-categoria', 'index', 'Editar Categorias', 2, '', 'Edição das categorias', 1, 1, 4, '2026-04-01 08:37:16', NULL),
(248, 'PainelJogos', 'index', 'painel-jogos', 'index', 'Painel Jogos', 2, '', 'Painel de visualização ao vivo dos jogos', 1, 1, 2, '2026-04-02 05:40:02', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_partidas`
--

DROP TABLE IF EXISTS `adms_partidas`;
CREATE TABLE IF NOT EXISTS `adms_partidas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adms_competicao_id` int NOT NULL,
  `adms_categoria_id` int DEFAULT NULL,
  `genero_partida` varchar(1) DEFAULT 'X' COMMENT 'M, F ou X',
  `atleta_a_id` int NOT NULL,
  `atleta_b_id` int NOT NULL,
  `sets_atleta_a` int DEFAULT NULL,
  `sets_atleta_b` int DEFAULT NULL,
  `vencedor_id` int DEFAULT NULL,
  `arbitro_id` int DEFAULT NULL COMMENT 'ID do usuário (Árbitro) responsável pela partida',
  `fase` varchar(50) DEFAULT NULL,
  `mesa` int DEFAULT NULL,
  `status_partida` varchar(20) DEFAULT 'Agendado',
  `primeiro_saque` varchar(5) DEFAULT NULL COMMENT 'Indica quem iniciou o saque (A ou B)',
  `is_wo` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Vitória por W.O.',
  `horario_previsto` time DEFAULT NULL,
  `created` datetime NOT NULL,
  `pontos_ganhos` int DEFAULT NULL,
  `pts_set1_a` int DEFAULT NULL,
  `pts_set1_b` int DEFAULT NULL,
  `pts_set2_a` int DEFAULT NULL,
  `pts_set2_b` int DEFAULT NULL,
  `pts_set3_a` int DEFAULT NULL,
  `pts_set3_b` int DEFAULT NULL,
  `pts_set4_a` int DEFAULT NULL,
  `pts_set4_b` int DEFAULT NULL,
  `pts_set5_a` int DEFAULT NULL,
  `pts_set5_b` int DEFAULT NULL,
  `cartao_amarelo_a` int DEFAULT '0',
  `cartao_vermelho_a` int DEFAULT '0',
  `cartao_amarelo_b` int DEFAULT '0',
  `cartao_vermelho_b` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `adms_competicao_id` (`adms_competicao_id`),
  KEY `adms_partidas_ibfk_1` (`atleta_a_id`),
  KEY `adms_partidas_ibfk_2` (`atleta_b_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1179 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_partidas`
--

INSERT INTO `adms_partidas` (`id`, `adms_competicao_id`, `adms_categoria_id`, `genero_partida`, `atleta_a_id`, `atleta_b_id`, `sets_atleta_a`, `sets_atleta_b`, `vencedor_id`, `arbitro_id`, `fase`, `mesa`, `status_partida`, `primeiro_saque`, `is_wo`, `horario_previsto`, `created`, `pontos_ganhos`, `pts_set1_a`, `pts_set1_b`, `pts_set2_a`, `pts_set2_b`, `pts_set3_a`, `pts_set3_b`, `pts_set4_a`, `pts_set4_b`, `pts_set5_a`, `pts_set5_b`, `cartao_amarelo_a`, `cartao_vermelho_a`, `cartao_amarelo_b`, `cartao_vermelho_b`) VALUES
(1129, 40, 7, 'F', 486, 491, 3, 0, 486, 494, 'Grupo A', 1, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1130, 40, 7, 'F', 486, 489, 3, 0, 486, 494, 'Grupo A', 5, 'Finalizado', 'A', 0, '08:30:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1131, 40, 7, 'F', 491, 489, 3, 0, 491, NULL, 'Grupo A', 7, 'Finalizado', 'A', 0, '09:00:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1132, 40, 7, 'M', 472, 473, 3, 0, 472, NULL, 'Grupo A', 2, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1133, 40, 5, 'M', 480, 466, 3, 0, 480, NULL, 'Grupo A', 3, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 9, 11, 5, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1134, 40, 4, 'M', 480, 467, 3, 0, 480, NULL, 'Grupo A', 6, 'Finalizado', 'B', 0, '08:30:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1135, 40, 4, 'M', 480, 466, 3, 0, 480, NULL, 'Grupo A', 8, 'Finalizado', 'A', 0, '09:00:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1136, 40, 4, 'M', 467, 466, 3, 0, 467, 494, 'Grupo A', 1, 'Finalizado', 'A', 0, '09:30:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1137, 40, 4, 'F', 493, 465, 3, 0, 493, NULL, 'Grupo A', 4, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 17:51:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1138, 40, 7, 'F', 486, 491, 3, 0, 486, NULL, 'Final', 1, 'Finalizado', 'A', 0, '10:00:00', '2026-04-01 18:33:28', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1139, 40, 7, 'M', 472, 473, 3, 0, 472, NULL, 'Final', 2, 'Finalizado', 'A', 0, '10:00:00', '2026-04-01 18:33:28', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1140, 40, 5, 'M', 480, 466, 3, 0, 480, NULL, 'Final', 3, 'Finalizado', 'A', 0, '10:00:00', '2026-04-01 18:33:28', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1141, 40, 4, 'F', 493, 465, 0, 3, 465, NULL, 'Final', 4, 'Finalizado', 'A', 0, '10:00:00', '2026-04-01 18:33:28', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1142, 40, 4, 'M', 480, 467, 3, 0, 480, NULL, 'Final', 1, 'Finalizado', 'A', 0, '10:30:00', '2026-04-01 18:33:28', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1143, 41, 8, 'X', 474, 486, 3, 0, 474, NULL, 'Grupo A', 1, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1144, 41, 8, 'X', 474, 482, 3, 0, 474, NULL, 'Grupo A', 1, 'Finalizado', NULL, 1, '08:30:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1145, 41, 8, 'X', 474, 471, 3, 0, 474, NULL, 'Grupo A', 1, 'Finalizado', NULL, 1, '09:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1146, 41, 8, 'X', 486, 482, 3, 0, 486, NULL, 'Grupo A', 2, 'Finalizado', NULL, 1, '09:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1147, 41, 8, 'X', 486, 471, 0, 3, 471, NULL, 'Grupo A', 2, 'Finalizado', NULL, 1, '08:30:00', '2026-04-01 18:42:22', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1148, 41, 8, 'X', 482, 471, 3, 0, 482, NULL, 'Grupo A', 2, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1149, 41, 8, 'X', 469, 478, 3, 0, 469, NULL, 'Grupo B', 3, 'Finalizado', 'B', 0, '08:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1150, 41, 8, 'X', 469, 483, 3, 0, 469, NULL, 'Grupo B', 3, 'Finalizado', NULL, 1, '08:30:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1151, 41, 8, 'X', 469, 477, 3, 0, 469, NULL, 'Grupo B', 3, 'Finalizado', NULL, 1, '09:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1152, 41, 8, 'X', 478, 483, 0, 3, 483, NULL, 'Grupo B', 4, 'Finalizado', NULL, 1, '09:00:00', '2026-04-01 18:42:22', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1153, 41, 8, 'X', 478, 477, 3, 0, 478, NULL, 'Grupo B', 4, 'Finalizado', NULL, 1, '08:30:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1154, 41, 8, 'X', 483, 477, 3, 0, 483, NULL, 'Grupo B', 4, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1155, 41, 8, 'X', 479, 468, 3, 0, 479, NULL, 'Grupo C', 5, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1156, 41, 8, 'X', 479, 480, 0, 3, 480, NULL, 'Grupo C', 5, 'Finalizado', NULL, 1, '08:30:00', '2026-04-01 18:42:22', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1157, 41, 8, 'X', 479, 493, 3, 0, 479, NULL, 'Grupo C', 5, 'Finalizado', NULL, 1, '09:00:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1158, 41, 8, 'X', 468, 480, 0, 3, 480, NULL, 'Grupo C', 6, 'Finalizado', NULL, 1, '09:00:00', '2026-04-01 18:42:22', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1159, 41, 8, 'X', 468, 493, 3, 0, 468, NULL, 'Grupo C', 6, 'Finalizado', NULL, 1, '08:30:00', '2026-04-01 18:42:22', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1160, 41, 8, 'X', 480, 493, 3, 0, 480, NULL, 'Grupo C', 6, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 18:42:23', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1161, 41, 8, 'X', 481, 476, 3, 0, 481, NULL, 'Grupo D', 7, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 18:42:23', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1162, 41, 8, 'X', 481, 487, 3, 0, 481, NULL, 'Grupo D', 7, 'Finalizado', NULL, 1, '08:30:00', '2026-04-01 18:42:23', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1163, 41, 8, 'X', 481, 484, 0, 3, 484, NULL, 'Grupo D', 7, 'Finalizado', NULL, 1, '09:00:00', '2026-04-01 18:42:23', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1164, 41, 8, 'X', 476, 487, 0, 3, 487, NULL, 'Grupo D', 8, 'Finalizado', NULL, 1, '09:00:00', '2026-04-01 18:42:23', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1165, 41, 8, 'X', 476, 484, 0, 3, 484, NULL, 'Grupo D', 8, 'Finalizado', NULL, 1, '08:30:00', '2026-04-01 18:42:23', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1166, 41, 8, 'X', 487, 484, 3, 0, 487, NULL, 'Grupo D', 8, 'Finalizado', 'A', 0, '08:00:00', '2026-04-01 18:42:23', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1167, 41, 8, 'X', 474, 483, 3, 0, 474, NULL, 'Quartas de Final', 1, 'Finalizado', NULL, 1, '09:30:00', '2026-04-01 18:55:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1168, 41, 8, 'X', 469, 482, 3, 0, 469, NULL, 'Quartas de Final', 2, 'Finalizado', NULL, 1, '09:30:00', '2026-04-01 18:55:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1169, 41, 8, 'X', 480, 481, 3, 0, 480, NULL, 'Quartas de Final', 3, 'Finalizado', NULL, 1, '09:30:00', '2026-04-01 18:55:35', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1170, 41, 8, 'X', 484, 479, 0, 3, 479, NULL, 'Quartas de Final', 4, 'Finalizado', NULL, 1, '09:30:00', '2026-04-01 18:55:35', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1171, 41, 8, 'X', 474, 469, 3, 0, 474, NULL, 'Semifinal', 1, 'Finalizado', NULL, 1, '10:00:00', '2026-04-01 19:02:12', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1172, 41, 8, 'X', 480, 479, 0, 3, 479, NULL, 'Semifinal', 2, 'Finalizado', NULL, 1, '10:00:00', '2026-04-01 19:02:12', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1173, 41, 8, 'X', 474, 479, 3, 0, 474, NULL, 'Final', 1, 'Finalizado', NULL, 1, '10:30:00', '2026-04-01 19:03:00', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1174, 42, 8, 'X', 472, 476, 3, 0, 472, NULL, 'Grupo A', 1, 'Finalizado', NULL, 1, '08:00:00', '2026-04-02 08:58:24', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1175, 42, 8, 'X', 483, 480, 0, 3, 480, 495, 'Grupo B', 2, 'Finalizado', 'A', 0, '08:00:00', '2026-04-02 08:58:24', 10, 11, 13, 5, 11, 7, 11, NULL, NULL, NULL, NULL, 1, 0, 0, 0),
(1176, 42, 8, 'X', 472, 483, 0, 3, 483, NULL, 'Semifinal', 1, 'Finalizado', NULL, 1, '08:30:00', '2026-04-02 09:04:29', 10, 0, 11, 0, 11, 0, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1177, 42, 8, 'X', 480, 476, 3, 0, 480, NULL, 'Semifinal', 2, 'Finalizado', NULL, 1, '08:30:00', '2026-04-02 09:04:29', 10, 11, 0, 11, 0, 11, 0, NULL, NULL, NULL, NULL, 0, 0, 0, 0),
(1178, 42, 8, 'X', 483, 480, 0, 3, 480, NULL, 'Final', 1, 'Finalizado', 'A', 0, '09:00:00', '2026-04-02 09:13:29', 10, 9, 11, 9, 11, 7, 11, NULL, NULL, NULL, NULL, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_prioridade`
--

DROP TABLE IF EXISTS `adms_prioridade`;
CREATE TABLE IF NOT EXISTS `adms_prioridade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `name` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `empresa_id` (`empresa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `adms_prioridade`
--

INSERT INTO `adms_prioridade` (`id`, `empresa_id`, `name`, `modified`, `created`) VALUES
(1, 331, 'Muito Alta', NULL, '2025-09-01 20:20:41'),
(2, 331, 'Alta', NULL, '2025-09-01 20:21:13'),
(3, 331, 'Média', NULL, '2025-09-01 20:21:13'),
(4, 331, 'Baixa', NULL, '2025-09-01 20:21:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_sits_empr_unid`
--

DROP TABLE IF EXISTS `adms_sits_empr_unid`;
CREATE TABLE IF NOT EXISTS `adms_sits_empr_unid` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(220) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `adms_sits_empr_unid`
--

INSERT INTO `adms_sits_empr_unid` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Ativo(a)', '2023-08-21 14:51:05', NULL),
(2, 'Inativo(a)', '2023-08-21 14:51:05', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_sits_pgs`
--

DROP TABLE IF EXISTS `adms_sits_pgs`;
CREATE TABLE IF NOT EXISTS `adms_sits_pgs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adms_color_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adms_color_id` (`adms_color_id`),
  KEY `adms_color_id_2` (`adms_color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_sits_pgs`
--

INSERT INTO `adms_sits_pgs` (`id`, `name`, `adms_color_id`, `created`, `modified`) VALUES
(1, 'Ativa', 3, '2022-05-23 00:00:00', '2023-07-18 09:52:08'),
(2, 'Inativa', 4, '2022-05-23 00:00:00', NULL),
(3, 'Análise', 1, '2022-05-23 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_sits_users`
--

DROP TABLE IF EXISTS `adms_sits_users`;
CREATE TABLE IF NOT EXISTS `adms_sits_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(44) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adms_color_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adms_color_id` (`adms_color_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_sits_users`
--

INSERT INTO `adms_sits_users` (`id`, `name`, `adms_color_id`, `created`, `modified`) VALUES
(1, 'Ativo', 3, '2022-03-23 15:28:25', NULL),
(2, 'Inativo', 5, '2022-03-23 15:26:59', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_types_pgs`
--

DROP TABLE IF EXISTS `adms_types_pgs`;
CREATE TABLE IF NOT EXISTS `adms_types_pgs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type_pg` int NOT NULL,
  `obs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_types_pgs`
--

INSERT INTO `adms_types_pgs` (`id`, `type`, `name`, `order_type_pg`, `obs`, `created`, `modified`) VALUES
(1, 'adms', 'Administrativo', 1, 'Gerenciamento Administrativo', '2022-05-23 00:00:00', '2023-07-18 11:21:21'),
(2, 'sts', 'Administrativo do Site', 2, 'Site', '2022-05-25 13:53:55', '2023-07-18 11:21:26'),
(3, 'cpms', 'Complemento', 3, 'Assuntos Complementares', '2025-01-09 08:25:08', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms_users`
--

DROP TABLE IF EXISTS `adms_users`;
CREATE TABLE IF NOT EXISTS `adms_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apelido` varchar(44) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'M' COMMENT 'M=Masculino, F=Feminino',
  `email` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recover_password` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conf_email` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagem` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adms_sits_user_id` int NOT NULL DEFAULT '1',
  `adms_access_level_id` int DEFAULT NULL,
  `cpf` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `mao_dominante` varchar(220) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pontuacao_ranking` int DEFAULT '0',
  `ranking` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `estilo_jogo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexo` int DEFAULT '1' COMMENT '1 - Masculino\r\n2 - Feminino\r\n3 - Paral. Masc\r\n4 - Paral Fem',
  `tel_1` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel_2` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel_3` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rede_social_1` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logradouro` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_logr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empresa_id` int NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `adms_sits_user_id` (`adms_sits_user_id`),
  KEY `adms_access_levels` (`adms_access_level_id`),
  KEY `empresa_id` (`empresa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=505 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `adms_users`
--

INSERT INTO `adms_users` (`id`, `name`, `apelido`, `genero`, `email`, `user`, `password`, `recover_password`, `conf_email`, `imagem`, `adms_sits_user_id`, `adms_access_level_id`, `cpf`, `rg`, `data_nascimento`, `mao_dominante`, `pontuacao_ranking`, `ranking`, `rating`, `estilo_jogo`, `sexo`, `tel_1`, `tel_2`, `tel_3`, `rede_social_1`, `logradouro`, `numero_logr`, `bairro`, `cidade`, `uf`, `empresa_id`, `created`, `modified`) VALUES
(1, 'Daniel de Oliveira Canedo', 'Daniel Canedo', 'M', 'docan2006@gmail.com', 'docan2006@gmail.com', '$2y$10$OJSOAy3BTIsgb6W2Zx7fUungUffewwuX4DwQ4SCvVbcvJmDf/Otk2', '3da4ee0a35f2e0c8b882743bf537e83488f5248137eb5103d202d6eeac958bd3', '$2y$10$6FLU9QQS0jMuXRr2OzfknOtGxvZZAekDTKGXg1TzuMOvf/5s1V7DW', 'foto-daniel-2.jpg', 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '71992844030  ', '71996244812', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-07-10 18:53:57', '2026-02-15 04:57:26'),
(460, 'Daniel de OLiveira Canedo', 'Comercial Daniel', 'M', 'comercial.daniel@repbrasil.salvador.br', 'comercial.daniel@repbrasil.salvador.br', '$2y$10$0xux/VkK7wG.mjWc.JFeWuN.i2i2NlroGJA8bV.9sO/WXm58s3UGy', '13cc509701aef09dbd2e49dcb41842a6fe132da67ef87eebc64edf96a2c4e901', '$2y$10$mbJSuVPvVVq66ZfB7HZhYu54Zs2puDsTnXCOX8DwzBkNpAsIng/0a', 'foto-daniel-2.jpg', 1, 4, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, '71992844030  ', '71996244812', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2025-09-28 05:44:20', '2026-03-17 14:30:40'),
(461, 'Daniel de OLiveira Canedo', 'Suporte Daniel', 'M', 'suporte.daniel@repbrasil.salvador.br', 'suporte.daniel@repbrasil.salvador.br', '$2y$10$NrDcnL0ol8wtHpheh67W3enR.OBxH.0rsvW3aB3CSq/85oc4tl88a', NULL, '$2y$10$SEPcEai60UkzE6Ren8KR2Oy2VBX1TjgVvhcSuSPxWWRIsVCeIJvhq', 'foto-daniel-2.jpg', 1, 12, NULL, NULL, NULL, 'Destro', NULL, 0, 0, 'Semiclassista', 1, '71992844030   ', '71996244812', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2025-09-28 05:45:07', '2026-03-19 16:20:28'),
(464, 'Marcelle Pereira Canedo', 'M. Canedo', 'F', 'marcelle@gmail.com', 'marcelle@gmail.com', '$2y$10$eiTBS/2LDHD6FSr/Dt/mPec7dWkmSf5cBOSFLhFdtA9QVD98hf5OK', NULL, '$2y$10$YHLBRcOizfmArBQSHoBml.V7e36tJhJqKQKNKwjadMY8nQxwSpfgG', 'foto-monique.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 20, 0, 0, 'Classista', 2, '(71) 98821-0168      ', '(71) 99284-4030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-17 11:31:43', '2026-03-29 10:42:32'),
(465, 'Gleysielle Pereira Canedo', 'G. Canedo', 'F', 'gleisyelle@gmail.com', 'gleisyelle@gmail.com', '$2y$10$YPd8eo8TC724CneQSYGVOuA7iMfN5pXZFMjg/M7dFz7wjEM1iXQYK', NULL, '$2y$10$gMYo.D1v3P6svpLb2ht49uCmyDk6arl3kjLzKREufQn5W1D53ERp2', 'fotos-gabi.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 30, 0, 0, 'Classista', 2, '(71) 98821-0168        ', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-17 11:34:47', '2026-04-01 18:35:22'),
(466, 'Ariel Santana', 'A. Santana', 'M', 'suporte.ariel@repbrasil.net.br', 'suporte.ariel@repbrasil.net.br', '$2y$10$//vB7wIaybGbkDmcrENtSOecLhi.WCli0Jaa3BZVGocQCKhZNWR1m', NULL, '$2y$10$/7IhmjT4nHmsYqViUTXvueB4ozHQ82dslHrugwwbeWqtgnVh5KLqG', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 10, 0, 0, 'Classista', 1, '(71) 99284-4030', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-20 15:23:22', '2026-03-27 18:34:42'),
(467, 'Diego de Jesus', 'D. Jesus', 'M', 'suporte.diego@repbrasil.salvador.br', 'suporte.diego@repbrasil.salvador.br', '$2y$10$I48.BhESkm6FQMl1XhQHFuS6tuHmNEulN5fBpzYXqlCd5W6pLFxN6', NULL, '$2y$10$SRW0c.BIkS4C64mMVyxbeur9tRdqkGg/WvVKva6RFqC8RbtyWeQtq', NULL, 1, 14, NULL, NULL, '1980-04-01', 'Canhoto', 20, 0, 0, 'Caneteiro', 1, '(71) 98173-4240', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-20 15:24:25', '2026-04-01 18:22:19'),
(468, 'Silvio Santana', 'S. Santana', 'M', 'suporte.silvio@repbrasil.salvador.br', 'suporte.silvio@repbrasil.salvador.br', '$2y$10$E/2TLSzGe85AAsTVHeFr/uHC4slybbjC9NDGYhuYHmOdUfOhyL1wm', NULL, '$2y$10$gl2uU0sY7Tam3lLKAKdpHOpSFi9dF8Y1MBNcyyLcKeZIe2lceThxO', NULL, 1, 14, NULL, NULL, NULL, 'Canhoto', 260, 0, 0, 'Semiclassista', 1, '(71) 98821-0168', '(71) 99624-4812', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-20 15:38:40', '2026-04-01 18:54:09'),
(469, 'Mauricio Carvalho', 'M. Carvalho', 'M', 'suporte.mauricio@repbrasil.salvador.br', 'suporte.mauricio@repbrasil.salvador.br', '$2y$10$gvdeTDhiw3dSCzGxv4EyQuB5t/XBBvCvmZLbaU5UWf59sRC0D/WZe', NULL, '$2y$10$fgQPQDvigl0E5/xNz4z70.6Mz3ZBxd7thnHaOAjpTMP/1E/eTpkiW', 'foto-maurício.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 290, 0, 0, 'Caneteiro', 1, '(71) 99624-4812', '(71) 98173-4240', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-20 15:39:43', '2026-04-01 18:56:06'),
(470, 'Luan Santos', 'L. Santos', 'M', 'luansantos@tmnet.com', 'luansantos@tmnet.com', '$2y$10$NnLF46NTepFoTJwQcpafwOuFf4WEmou2WSI36rUlm20jO0G6P6p.K', NULL, '$2y$10$mT3AY6KsL3Zb6Q8Jp2nhx.VdticSPjZMnW9R98EHYrUZ9AUui81xa', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 310, 0, 0, 'Classista', 1, '(71) 99284-4030', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 05:48:51', '2026-03-29 11:08:37'),
(471, 'Kuan Monteiro', 'K. Monteiro', 'M', 'kmonteiro@tmnet.com', 'kmonteiro@tmnet.com', '$2y$10$TdReEWiZbD.Y8/J5Y1DI5Ok4CGbomQaPbsfuPhud9WqljcsImCw.y', NULL, '$2y$10$rRcHxtr9x1ipM4WcZRXGb.z/35qy0l2VeTzT1hLLIlostRmrEWNTC', 'foto-diego.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 310, 0, 0, 'Classista', 1, '(71) 99284-4030', '(71) 99284-4030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:07:17', '2026-04-01 18:53:27'),
(472, 'Abimael Araujo', 'A. Araujo', 'M', 'aaraujo@tmnet.com', 'aaraujo@tmnet.com', '$2y$10$QJdTf6zhmDAi1tYmIMz8hu2wW0KT75nM9Fw06FRiogINwjs5rzJ0G', NULL, '$2y$10$9twSLolDunxC.buNu7YtsubVSspXcUIeNCPUDnPUEEq5vnwzwtouq', 'foto-silvio.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 600, 0, 0, 'Classista', 1, '(71) 3365-3343', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:08:53', '2026-04-02 09:03:57'),
(473, 'Anderson Santos', 'A. Santos', 'M', 'asantos@tmnet.com', 'asantos@tmnet.com', '$2y$10$gm84BtsTahWxUsMDzrisBOw2gncCOUzDH8ixH69m2sc2R1Md7ZOBG', NULL, '$2y$10$Bs35mLRzhMLdePLrrrNgveOlHF8y6sDr6SnXyu98.21UnxBrM.3fq', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 520, 0, 0, 'Classista', 1, '(71) 98821-0168', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:10:01', '2026-03-29 09:49:19'),
(474, 'Guilherme Barros', 'G. Barros', 'M', 'gbarros@tmnet.co', 'gbarros@tmnet.co', '$2y$10$rRVsJSBlvDe9.ZhJw4A9lOcSrdrbTdScxATwFVmP8pidFH2K7OM1m', NULL, '$2y$10$aQcW1Ote8ExuwE9VJvOtHOSH1HDUuKfo/Af0xnjiT3MLGrWkElnKC', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 640, 0, 0, 'Classista', 1, '(71) 3365-3343', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:11:07', '2026-04-01 19:03:48'),
(475, 'João Moreira', 'J. Moreira', 'M', 'jmoreira@tmnet.com', 'jmoreira@tmnet.com', '$2y$10$YUb4x7s9M8.krVteNtHEb.W0eoPUcqbFJcgE2Af0f1g3qhLq5Agze', NULL, '$2y$10$yEqrcuzX0jFD9b3bXAExZu/giUdj/HsSFSCC1OJ7HsZiaqF5YHX82', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 550, 0, 0, 'Classista', 1, '(71) 3365-3343', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:12:00', '2026-03-29 11:10:16'),
(476, 'CarlosSilva', 'C. Silva', 'M', 'csilva@tmnet.com', 'csilva@tmnet.com', '$2y$10$FViY4aEsw19QVPF.f3isJuBbIEhRFZ0M9EwBYRpCVQTL.wuKejE4W', NULL, '$2y$10$v7gwr5xTr9CgT.JHw5a7q.2EDz3TCBBJW4axkfNXLRc4QOlGSYS.O', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 1000, 0, 0, 'Classista', 1, '(71) 98821-0168', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:12:53', '2026-03-24 17:59:03'),
(477, 'Theo Felix Cruz', 'T. Cruz', 'M', 'tcruz@tmnet.com', 'tcruz@tmnet.com', '$2y$10$5uRv4uvsPjGXnq.TrafrIuHGoYEhxSCO33LfsU2X21Ttfo9qy31yq', NULL, '$2y$10$YfU8deW8xgtZtOV6uBj30..cCGSKTmRGaW.x0tvfQAb1rBuYCR/Re', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 1000, 0, 0, 'Classista', 1, '(71) 98821-0168 ', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:14:31', '2026-03-25 09:56:12'),
(478, 'Carlos Ishida', 'C. Ishida', 'M', 'cishida@tmnet.com', 'cishida@tmnet.com', '$2y$10$SB7TE5yJ4OQvTXw/zSjsiuPth9KReVqj87WuLxaA9xfdXbeDO5BoK', NULL, '$2y$10$9Lsa8ZGluxBDQatpvobgdezfiaG8OjjnOOcD1nVSd/5ojo.x28E6a', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 1010, 0, 0, 'Classista', 1, '(71) 99624-4812  ', '(71) 99624-4812', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:15:15', '2026-04-01 18:53:46'),
(479, 'Guilherme Teodoro', 'G. Teodoro', 'M', 'gteodoro@tmnet.com', 'gteodoro@tmnet.com', '$2y$10$hOVUBePKLif55DXle0S8F.8pn.Z6zNVfrOIcl33oz3p6oI5ZqntNe', NULL, '$2y$10$2HsujZT7P6K4V5JFo90mfuIZJL5FSwRJFOdxjbcSilnX9CW9xjcg2', NULL, 1, 14, NULL, NULL, '2017-04-03', 'Destro', 1060, 0, 0, 'Classista', 1, '(71) 98821-0168   ', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-21 06:16:11', '2026-04-03 07:34:09'),
(480, 'Daniel de OLiveira Canedo', 'D. Canedo', 'M', 'dcanedo@gmail.com', 'dcanedo@gmail.com', '$2y$10$imyhejWuTzeN71p6y/PBg.zVFe.6TZ385f.Rb7tg6WUUVojm0BGVC', NULL, '$2y$10$aXNZpi3usqxzEVSdjbKu3u.Ki4iLZxjH4nliikBYpQVjWB7M000hu', 'foto-daniel-2.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 180, NULL, NULL, 'Classista', 1, '(71) 99284-4030', '(71) 99284-4030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 07:31:05', '2026-04-02 09:15:52'),
(481, 'Sofia Kano', 'S. Kano', 'F', 'skano@tmnet.site', 'skano@tmnet.site', '$2y$10$fh41LcDvQRgjwVxXl8lStuoD3oSJEuop7/yXlEevv2.VNp4Hxj9Ta', NULL, '$2y$10$DJsyc58EWBhDGtFdJvkBueNqcalwgrhjM0f0v2dFaTX/0rALjLKX.', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 1020, NULL, NULL, 'Classista', 2, '(71) 99624-4812', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:20:03', '2026-04-01 18:54:15'),
(482, 'Valesca Maranhão', 'V. Maranhão', 'F', 'vmaranhao@tmnet.site', 'vmaranhao@tmnet.site', '$2y$10$6n9bPhvtkBx6i8RNMG0o7uU28bEwXxuqsWG639RXm0raAvMP1v4US', NULL, '$2y$10$DH.V8CHdS8okBqTVerlDRuBNviFcFXgSNQQx.VtrXah7HhafJ2kMy', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 1010, NULL, NULL, 'Classista', 2, '(71) 99624-4812', '(71) 99624-4812', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:21:48', '2026-04-01 18:44:51'),
(483, 'Jessica Prates', 'J. Prates', 'F', 'jprates@tmnet.site', 'jprates@tmnet.site', '$2y$10$jtPaLbqnOPAK2vsmTyUOpOaPV2A5D5iL4MBk8SUg6I.Gt12zzdizO', NULL, '$2y$10$brOUhxHZSBYgVI5dXQKuB.jGeMA1xBhjCsdH2pXwRonvCkwZwAFMW', 'fotos-gabi.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 1060, NULL, NULL, 'Classista', 2, '(71) 98173-4240', '(71) 98173-4240', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:22:56', '2026-04-02 09:04:54'),
(484, 'Melissa Aoyama', 'M. Aoyama', 'F', 'maoyama@tmnet.site', 'maoyama@tmnet.site', '$2y$10$6tCXWgRuh5S7b1zwjtkoROURQ60.4DkXA9fIRIxKyGMCSgWH6hLGu', NULL, '$2y$10$jTQq83T.ei9yE4qnt2.IPuMO.2UUy/llqF5ZAIuVY4kAvqVwr2TCe', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 1020, NULL, NULL, 'Classista', 2, '(71) 98821-0168', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:24:29', '2026-04-01 18:54:58'),
(485, 'Maria Fernandes', 'M. Fernandes', 'F', 'mfernandes@tmnet.site', 'mfernandes@tmnet.site', '$2y$10$Ar/EijkULAk7P7mJO1CkJuA/uEXfVodpwZMyX1jBxosBcOwzdeBoG', NULL, '$2y$10$jEUS4k5Vc7cMjgVAz/kTMuNX8GchPlzKgbxK01lDIn6kTylKhKvTi', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 540, NULL, NULL, 'Classista', 2, '(71) 98821-0168', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:25:41', '2026-03-25 17:42:57'),
(486, 'Emanuelly Santos', 'E. Santos', 'F', 'esantos@tmnet.site', 'esantos@tmnet.site', '$2y$10$BaIQJon7adpf/LUionJ0sOl6z6Dga4rmLQBo31TGcsmTT8dhEYgNq', NULL, '$2y$10$1YFo0K8GPavGTCiOlDqrMeZRDYHk2r/.hGc83yiHwGt8WUeCPuVX2', 'foto-jonathas.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 680, NULL, NULL, 'Classista', 2, '(71) 98821-0168', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:26:39', '2026-04-01 18:54:40'),
(487, 'Sabrina Miyabara', 'S. Miyabara', 'F', 'smiyabara@tmnet.site', 'smiyabara@tmnet.site', '$2y$10$qNDoZj5p1dmwm9XiALt0XuVtnJQch7xlfkq/xL3hLwhsxFErLVMlu', NULL, '$2y$10$r2.gITfq.w.5DBJDRl2I6.vNxBQVvfW/Oon3dH2nkIFcNPH9QabPy', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 580, NULL, NULL, 'Classista', 2, '(71) 3365-3343', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:28:01', '2026-04-01 18:55:10'),
(488, 'Livia Lima', 'L. Lima', 'F', 'llima@tmnet.site', 'llima@tmnet.site', '$2y$10$qpMTkPi.l2mjo/JlCReqCuWhsk5ngNJbsKcSSqxsb9NemoA3Hckhm', NULL, '$2y$10$2J18325ZNs4POTz5YodRX.Dq3gSbzWGmWZ6YQ7oJBT3m9uq9zhWbK', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 540, NULL, NULL, 'Classista', 2, '(71) 98821-0168', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:29:19', '2026-03-30 19:41:21'),
(489, 'Analu Prust', 'A. Prust', 'F', 'aprust@tmnet.site', 'aprust@tmnet.site', '$2y$10$N6vjqAzMUDeupOcGVdAtxeGYCAYJ5YH98cuQM1P4bdwbaEQ3hrxse', NULL, '$2y$10$ZPddkwAZrg4WRWZLBL8XpOCXQX/bEThnp6ikvicrftLa3JejH.Z/e', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 290, NULL, NULL, 'Classista', 2, '(71) 98821-0168', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:30:52', '2026-03-29 11:23:40'),
(490, 'Marina Sousa', 'M. Sousa', 'F', 'marinasousa@tmnet.site', 'marinasousa@tmnet.site', '$2y$10$bSuJ9FB.nrD/gnk2CHU0k./epuA8M7OEM5lsBLgEm.o1vr83AVfGK', NULL, '$2y$10$YFOfC5vzTKHxJe5t8Cjhge8OOwtQ9zorZhVCbClL7XeeE/.jw2yne', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 250, NULL, NULL, 'Classista', 1, '(71) 3365-3343 ', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:32:10', NULL),
(491, 'Francis Pamplona', 'F. Pamplona', 'F', 'fpamplona@tmnet.site', 'fpamplona@tmnet.site', '$2y$10$uBelwsixffSSZN1cqx7TzOZjTPNKBGrJD39/cEG0xI1rHdn7sjeaK', NULL, '$2y$10$ZFTlAbQ38z80x8CSjeRDGuLBhy0/FcU3X8AvCuC4XH4EtkjGnZoqO', NULL, 1, 14, NULL, NULL, NULL, 'Destro', 280, NULL, NULL, 'Classista', 1, '(71) 98821-0168 ', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:33:17', '2026-04-01 18:20:51'),
(492, 'Luciana Marimoto', 'L. Marimoto', 'F', 'lmarimoto@tmnet.site', 'lmarimoto@tmnet.site', '$2y$10$WpKbDaYHtgmWZU5YDOr.y.k5iLpL5Jx0JLWQXfB9WaMLw5kRMO3E2', NULL, '$2y$10$0cByZgWuBjeF1Lcj/ZcK0eljUKmFa.B3QdzSIJ3FHp.K97XZTBvs.', 'foto-monique.jpg', 1, 14, NULL, NULL, NULL, 'Destro', 270, NULL, NULL, 'Classista', 2, '(71) 98821-0168', '(71) 99284-4030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:34:43', '2026-03-30 19:31:24'),
(493, 'Isabel Amorim', 'I. Amorim', 'F', 'iamorim@tmnet.site', 'iamorim@tmnet.site', '$2y$10$8JSILaLj4EXaaf5WzbVbm.RP1NxuEyZk3Z48yu4VzYSnl2oLDfQiy', NULL, '$2y$10$MeSrhYNSeFFGNEBcJF62dOZuLB71Cs/RjkEnnp1e4jph0ij0RbryW', NULL, 1, 14, NULL, NULL, '2006-01-01', 'Destro', 10, NULL, NULL, 'Classista', 1, '(71) 98821-0168 ', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-25 10:35:28', '2026-04-03 07:59:57'),
(494, 'Daniel Oliveira Canedo', 'Arb. Canedo', 'M', 'canedo@tmnet.site', 'canedo@tmnet.site', '$2y$10$fhacq.Vr6hu6bzFW7KukNePwiWU45N6WRLNcrgcgYBEhll7TbS.5C', NULL, '$2y$10$2Wn1s6RFhBrZ7v62XTP/jux1P15mzJ/7ddAijbN22Lwu2Hs5i1g/q', NULL, 1, 15, NULL, NULL, NULL, 'Destro', 0, NULL, NULL, 'Classista', 1, '(71) 99284-4030 ', '(71) 99284-4030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-27 08:42:26', '2026-03-27 11:41:07'),
(495, 'Camila Oliveira', 'Camila Arbitra', 'M', 'camila@tmnet.site', 'camila@tmnet.site', '$2y$10$XW9rXT6v3AuSTxQMZb84c.Ey7pKb.cUdsCALqWTRRq6Gctvrn/9hC', NULL, '$2y$10$vS5nCkFO28zel/61voTc4evdzrX.V6WQnSM0wvooHEA3nKX3c7xO6', 'foto-silvio.jpg', 1, 15, NULL, NULL, NULL, 'Destro', 0, NULL, NULL, 'Classista', 1, '(71) 3365-3343', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-03-27 14:26:23', '2026-03-31 05:33:04'),
(496, 'Grazielle', 'Grazy Canedo', 'M', 'grazy@tmnet.site', 'grazy@tmnet.site', '$2y$10$nA.ytxMFX1r0meUxCp95.eiYYVn7QjIBvExlWnYeQFYcvaHnjT.jq', NULL, '$2y$10$iIQAo/EUvb3kU/lFupS9d.RNgG/wjErxCprINE15Nt.kWJAdc0eXi', NULL, 1, 14, NULL, NULL, '2016-04-13', 'Destro', 0, NULL, NULL, 'Classista', 2, '(71) 99284-4030', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 06:40:26', NULL),
(497, 'Jade Moutinho', 'Jade', 'M', 'jade@tmnet.site', 'jade@tmnet.site', '$2y$10$/8j3EecnD1YgDBWpxy6qKu3mE0Nw4s/MpZKzexPSNByx13eQ7k7Jm', NULL, '$2y$10$rpz4aWSgL407imKzhXKlCOOrFLCmaS84XRHZzynEy9Mn5F82WgTwu', NULL, 1, 14, NULL, NULL, '2016-01-01', 'Destro', 0, NULL, NULL, 'Classista', 2, '(71) 99624-4812', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 06:41:37', NULL),
(498, 'Brisa', 'Brisa', 'M', 'brisa@tmnet.site', 'brisa@tmnet.site', '$2y$10$R00ukQOt01cBwXllX6FhKOjtZeYbq/K5xlF6acpxeBjtRi/b43xbC', NULL, '$2y$10$UISzIxrc1HdbOuPBTS9TS.mBIarnngnLhd6VBsWU6CJbSkIzf0nSq', NULL, 1, 14, NULL, NULL, '2016-12-31', 'Destro', 0, NULL, NULL, 'Caneteiro', 2, '(71) 3365-3343', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 06:43:23', NULL),
(499, 'Catarina', 'Cate', 'M', 'cati@tmnet.site', 'cati@tmnet.site', '$2y$10$z8ArAr2swYGo234xHefrvOlU9Mt0XY3Qvbe50VSV57qBDeLYe1aie', NULL, '$2y$10$Q8rW7ZNv9/SmN.Syw7cvjO1fcboWU4zo.bZgPLHuLpJG/e3G0KUhm', NULL, 1, 14, NULL, NULL, '2016-06-01', 'Destro', 0, NULL, NULL, 'Classista', 2, '(71) 98821-0168', '(71) 99624-4812', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 06:44:22', NULL),
(500, 'Mariza Monte', 'Mariza', 'M', 'marizamonte@tmnet.site', 'marizamonte@tmnet.site', '$2y$10$7jFSgFeTe6/okSdFG/XTveCeEmTvCEV4.mRhoJ9xxYynyYCnSqkeu', NULL, '$2y$10$kjwBVfV8.tUFNqynZQglLeyDeYZZZGu7V35ZYVplvKRFmxdEDJO36', NULL, 1, 14, NULL, NULL, '2012-08-25', 'Destro', 0, NULL, NULL, 'Classista', 1, '(71) 99284-4030 ', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 06:45:49', '2026-04-03 07:58:57'),
(501, 'Carlos Canedo', 'Carlos Canedo', 'M', 'carloscanedo@tmnet.site', 'carloscanedo@tmnet.site', '$2y$10$0y3EVA0WvF6qqna13ffH/O8wrSjHMFx2MTh3xWDtZrEkZ/ejHdAFi', NULL, '$2y$10$2Tpc/MSaNQwNhiw.C8ArT.iCMJdCGySn2uWbJjJoh0juCYnfq2cxu', NULL, 1, 14, NULL, NULL, '2009-01-01', 'Destro', 0, NULL, NULL, 'Classista', 1, '(71) 98821-0168 ', '(71) 98821-0168', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 06:46:51', '2026-04-03 08:00:53'),
(502, 'José Bento', 'Bentão', 'M', 'bentao@tmnet.site', 'bentao@tmnet.site', '$2y$10$1h2d/SEzZp/jYn8RjxtpfuQ10tiQUm85G73juYqbaJ8Tkw.D3FldG', NULL, '$2y$10$lyOo9MXYfZ8TTOrbOcEDkuP6tuexjrCoj3SaNdzbD45pMe3oPjVBO', NULL, 1, 14, NULL, NULL, '2016-12-01', 'Destro', 0, NULL, NULL, 'Classista', 1, '(71) 99284-4030', '(71) 3365-3343', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 06:48:06', NULL),
(503, 'Luana', 'Luana', 'M', 'luana@tmnet.site', 'luana@tmnet.site', '$2y$10$1ZRsaVYcnr3Vkc8vFtZ0yeHFSfBzL8xw8WNdHynuPYbn7kM5rDaiK', NULL, '$2y$10$k/J4zP7jvRtlt0uZ9oOUwOxUe23znFB29XIx9QKjJjy3a8jzxymCC', NULL, 1, 14, NULL, NULL, '2013-01-01', 'Destro', 0, NULL, NULL, 'Classista', 2, '(71) 3365-3343', '(71) 9945-6879', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 06:51:34', NULL),
(504, 'Mario Bross', 'Mario Bross', 'M', 'mariobross@tmnet.site', 'mariobross@tmnet.site', '$2y$10$ZsCVv3KykV5BJPl4EfGsHeKpM1195VEalmUSINA.wGqorjio6.GVa', NULL, '$2y$10$270lTkk2ddgPZZs3MrD3fONSYXpCIk81pa6/lV2mfBGItU0RTZBj.', NULL, 1, 14, NULL, NULL, '2013-06-01', 'Destro', 0, NULL, NULL, 'Classista', 1, '(71) 99284-4030', '(71) 99284-4030', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 331, '2026-04-03 07:01:24', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `rating`
--

DROP TABLE IF EXISTS `rating`;
CREATE TABLE IF NOT EXISTS `rating` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `name` int NOT NULL,
  `sexo` int NOT NULL,
  `ano` int NOT NULL,
  `pontuacao_min` int NOT NULL,
  `pontuacao_max` int NOT NULL,
  `created` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `adms_empresa`
--
ALTER TABLE `adms_empresa`
  ADD CONSTRAINT `adms_empresa_ibfk_1` FOREIGN KEY (`situacao`) REFERENCES `adms_sits_empr_unid` (`id`);

--
-- Restrições para tabelas `adms_emp_principal`
--
ALTER TABLE `adms_emp_principal`
  ADD CONSTRAINT `adms_emp_principal_ibfk_1` FOREIGN KEY (`situacao`) REFERENCES `adms_sits_empr_unid` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restrições para tabelas `adms_info_login`
--
ALTER TABLE `adms_info_login`
  ADD CONSTRAINT `adms_info_login_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `adms_emp_principal` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restrições para tabelas `adms_levels_forms`
--
ALTER TABLE `adms_levels_forms`
  ADD CONSTRAINT `adms_levels_forms_ibfk_1` FOREIGN KEY (`adms_access_level_id`) REFERENCES `adms_access_levels` (`id`),
  ADD CONSTRAINT `adms_levels_forms_ibfk_2` FOREIGN KEY (`adms_sits_user_id`) REFERENCES `adms_sits_users` (`id`);

--
-- Restrições para tabelas `adms_levels_pages`
--
ALTER TABLE `adms_levels_pages`
  ADD CONSTRAINT `adms_levels_pages_ibfk_1` FOREIGN KEY (`adms_access_level_id`) REFERENCES `adms_access_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adms_levels_pages_ibfk_2` FOREIGN KEY (`adms_page_id`) REFERENCES `adms_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adms_levels_pages_ibfk_3` FOREIGN KEY (`adms_items_menu_id`) REFERENCES `adms_items_menus` (`id`);

--
-- Restrições para tabelas `adms_pages`
--
ALTER TABLE `adms_pages`
  ADD CONSTRAINT `adms_pages_ibfk_1` FOREIGN KEY (`adms_sits_pgs_id`) REFERENCES `adms_sits_pgs` (`id`),
  ADD CONSTRAINT `adms_pages_ibfk_2` FOREIGN KEY (`adms_types_pgs_id`) REFERENCES `adms_types_pgs` (`id`),
  ADD CONSTRAINT `adms_pages_ibfk_3` FOREIGN KEY (`adms_groups_pgs_id`) REFERENCES `adms_groups_pgs` (`id`);

--
-- Restrições para tabelas `adms_partidas`
--
ALTER TABLE `adms_partidas`
  ADD CONSTRAINT `adms_partidas_ibfk_1` FOREIGN KEY (`atleta_a_id`) REFERENCES `adms_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `adms_partidas_ibfk_2` FOREIGN KEY (`atleta_b_id`) REFERENCES `adms_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `adms_partidas_ibfk_3` FOREIGN KEY (`adms_competicao_id`) REFERENCES `adms_competicoes` (`id`);

--
-- Restrições para tabelas `adms_prioridade`
--
ALTER TABLE `adms_prioridade`
  ADD CONSTRAINT `adms_prioridade_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `adms_emp_principal` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restrições para tabelas `adms_sits_users`
--
ALTER TABLE `adms_sits_users`
  ADD CONSTRAINT `adms_sits_users_ibfk_1` FOREIGN KEY (`adms_color_id`) REFERENCES `adms_colors` (`id`);

--
-- Restrições para tabelas `adms_users`
--
ALTER TABLE `adms_users`
  ADD CONSTRAINT `adms_users_ibfk_1` FOREIGN KEY (`adms_sits_user_id`) REFERENCES `adms_sits_users` (`id`),
  ADD CONSTRAINT `adms_users_ibfk_2` FOREIGN KEY (`adms_access_level_id`) REFERENCES `adms_access_levels` (`id`),
  ADD CONSTRAINT `adms_users_ibfk_6` FOREIGN KEY (`empresa_id`) REFERENCES `adms_emp_principal` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
