--
-- INSTRUÇÕES ABAIXO. IMPORTAR NA TABELA J17_USER DO BANCO.
--

-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 29-Nov-2016 às 15:33
-- Versão do servidor: 5.5.53-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.20

/*SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";*/


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `novoppgi`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_user`
--

/*
CREATE TABLE `j17_user` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `visualizacao_candidatos` datetime NOT NULL,
  `visualizacao_candidatos_finalizados` datetime NOT NULL,
  `visualizacao_cartas_respondidas` datetime NOT NULL,
  `administrador` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordenador` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secretaria` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `professor` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aluno` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `siape` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dataIngresso` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telcelular` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telresidencial` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unidade` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titulacao` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `classe` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nivel` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regime` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `turno` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idLattes` bigint(20) DEFAULT NULL,
  `formacao` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resumo` text COLLATE utf8_unicode_ci,
  `alias` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ultimaAtualizacao` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idRH` int(11) DEFAULT NULL,
  `cargo` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
*/

--
-- Extraindo dados da tabela `j17_user`
--

--
-- *****MUDAR DADOS AQUI*****
-- ADMIN_TESTE para seu nome, 012.345.678-90 para seu CPF e teste@teste.com para seu email
-- Ou utilizar dados padrão, MENOS O CPF
-- Senha: 123456
--

INSERT INTO `j17_user` (`nome`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `visualizacao_candidatos`, `visualizacao_candidatos_finalizados`, `visualizacao_cartas_respondidas`, `administrador`, `coordenador`, `secretaria`, `professor`, `aluno`, `siape`, `dataIngresso`, `endereco`, `telcelular`, `telresidencial`, `unidade`, `titulacao`, `classe`, `nivel`, `regime`, `turno`, `idLattes`, `formacao`, `resumo`, `alias`, `ultimaAtualizacao`, `idRH`, `cargo`) VALUES
('admin teste', '033.840.071-07', 'JsHPm23fX1lCpVMrFD9wLZnRztGemGqF', '$2y$13$q2Wg3LKKplx4scKbUNKFqu/.FoHDIGc8hkV81RktklS77Rr9AzPJG', NULL, 'mscs@icomp.ufam.edu.br', 10, '', '', '2016-12-06 22:00:00', '2016-12-06 22:00:00', '2016-12-06 22:00:00', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

/*
--
-- Indexes for dumped tables
--

--
-- Indexes for table `j17_user`
--
ALTER TABLE `j17_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);
  */

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `j17_user`
--

/*ALTER TABLE `j17_user`*/
/*  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69; */
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
