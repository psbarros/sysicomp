-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 30-Nov-2016 às 03:23
-- Versão do servidor: 5.5.53-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `novoppgi`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_aluno`
--

CREATE TABLE `j17_aluno` (
  `id` int(11) NOT NULL,
  `nome` varchar(60) CHARACTER SET utf8 NOT NULL,
  `email` varchar(60) CHARACTER SET utf8 NOT NULL,
  `senha` varchar(40) CHARACTER SET utf8 NOT NULL,
  `matricula` varchar(15) CHARACTER SET utf8 NOT NULL,
  `area` int(11) NOT NULL,
  `curso` int(11) NOT NULL,
  `endereco` varchar(160) CHARACTER SET utf8 DEFAULT NULL,
  `bairro` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `cidade` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `uf` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `cep` varchar(9) CHARACTER SET utf8 DEFAULT NULL,
  `datanascimento` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `sexo` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `nacionalidade` int(11) DEFAULT NULL,
  `estadocivil` varchar(15) CHARACTER SET utf8 NOT NULL,
  `cpf` varchar(14) CHARACTER SET utf8 NOT NULL,
  `rg` varchar(10) CHARACTER SET utf8 NOT NULL,
  `orgaoexpeditor` varchar(10) CHARACTER SET utf8 NOT NULL,
  `dataexpedicao` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `telresidencial` varchar(18) CHARACTER SET utf8 DEFAULT NULL,
  `telcomercial` varchar(18) CHARACTER SET utf8 DEFAULT NULL,
  `telcelular` varchar(18) CHARACTER SET utf8 DEFAULT NULL,
  `nomepai` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `nomemae` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `regime` int(11) DEFAULT NULL,
  `bolsista` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `financiadorbolsa` varchar(45) DEFAULT NULL,
  `dataimplementacaobolsa` date DEFAULT NULL,
  `agencia` varchar(30) CHARACTER SET utf8 NOT NULL,
  `pais` varchar(30) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `dataingresso` date NOT NULL,
  `idiomaExameProf` varchar(20) DEFAULT NULL,
  `conceitoExameProf` varchar(9) DEFAULT NULL,
  `dataExameProf` varchar(10) DEFAULT NULL,
  `tituloQual2` varchar(180) DEFAULT NULL,
  `dataQual2` varchar(10) DEFAULT NULL,
  `conceitoQual2` varchar(9) DEFAULT NULL,
  `tituloTese` varchar(180) DEFAULT NULL,
  `dataTese` varchar(10) DEFAULT NULL,
  `conceitoTese` varchar(9) DEFAULT NULL,
  `horarioQual2` varchar(10) DEFAULT NULL,
  `localQual2` varchar(100) DEFAULT NULL,
  `resumoQual2` text NOT NULL,
  `horarioTese` varchar(10) DEFAULT NULL,
  `localTese` varchar(100) DEFAULT NULL,
  `resumoTese` text NOT NULL,
  `tituloQual1` varchar(180) DEFAULT NULL,
  `numDefesa` int(11) DEFAULT NULL,
  `dataQual1` varchar(10) DEFAULT NULL,
  `examinadorQual1` varchar(60) DEFAULT NULL,
  `conceitoQual1` varchar(9) DEFAULT NULL,
  `cursograd` varchar(100) DEFAULT NULL,
  `instituicaograd` varchar(100) DEFAULT NULL,
  `crgrad` varchar(10) DEFAULT NULL,
  `egressograd` int(11) DEFAULT NULL,
  `dataformaturagrad` varchar(10) DEFAULT NULL,
  `idUser` int(11) NOT NULL,
  `orientador` smallint(6) NOT NULL,
  `anoconclusao` date NOT NULL,
  `sede` varchar(2) NOT NULL DEFAULT 'AM'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_banca_has_membrosbanca`
--

CREATE TABLE `j17_banca_has_membrosbanca` (
  `banca_id` int(11) NOT NULL,
  `membrosbanca_id` int(11) NOT NULL,
  `funcao` text,
  `passagem` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_defesa`
--

CREATE TABLE `j17_defesa` (
  `idDefesa` int(11) NOT NULL,
  `titulo` varchar(180) DEFAULT NULL,
  `tipoDefesa` char(2) DEFAULT NULL,
  `data` varchar(10) DEFAULT NULL,
  `conceito` varchar(9) DEFAULT NULL,
  `horario` varchar(10) DEFAULT NULL,
  `local` varchar(100) DEFAULT NULL,
  `resumo` text NOT NULL,
  `numDefesa` int(11) DEFAULT NULL,
  `examinador` text,
  `emailExaminador` text,
  `reservas_id` int(10) DEFAULT NULL,
  `aluno_id` int(11) NOT NULL,
  `previa` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `justificativa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_lista_alunos`
--

CREATE TABLE `j17_lista_alunos` (
  `matricula` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `curso` varchar(5) NOT NULL,
  `anoIngresso` int(11) NOT NULL,
  `anoEvasao` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `ultimaAtualizacao` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Status -> 0 (matriculado) e 1 (formado)';

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_membrosbanca`
--

CREATE TABLE `j17_membrosbanca` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `filiacao` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cpf` varchar(15) NOT NULL,
  `dataCadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idProfessor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_prorrogacoes`
--

CREATE TABLE `j17_prorrogacoes` (
  `id` int(11) NOT NULL,
  `idAluno` int(11) NOT NULL,
  `idOrientador` int(11) NOT NULL,
  `justificativa` text NOT NULL,
  `previa` text NOT NULL,
  `dataSolicitacao` date NOT NULL,
  `dataAprovOrientador` date NOT NULL,
  `dataAprovColegiado` date NOT NULL,
  `status` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_publicacoes`
--

CREATE TABLE `j17_publicacoes` (
  `id` int(11) NOT NULL,
  `idProfessor` int(11) NOT NULL,
  `titulo` varchar(300) NOT NULL,
  `ano` int(4) NOT NULL,
  `local` varchar(300) DEFAULT NULL,
  `tipo` smallint(1) NOT NULL,
  `natureza` varchar(10) DEFAULT NULL,
  `autores` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_trancamentos`
--

CREATE TABLE `j17_trancamentos` (
  `id` int(11) NOT NULL,
  `idAluno` int(11) NOT NULL,
  `idOrientador` int(11) NOT NULL,
  `dataSolicitacao` date NOT NULL,
  `dataAprovOrientador` date NOT NULL,
  `dataInicio` date NOT NULL,
  `prevTermino` date NOT NULL,
  `dataTermino` date NOT NULL,
  `justificativa` varchar(250) NOT NULL,
  `documento` text NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_user`
--

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `j17_aluno`
--
ALTER TABLE `j17_aluno`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `j17_banca_has_membrosbanca`
--
ALTER TABLE `j17_banca_has_membrosbanca`
  ADD PRIMARY KEY (`banca_id`,`membrosbanca_id`),
  ADD KEY `fk_banca_membrobanca` (`membrosbanca_id`);

--
-- Indexes for table `j17_defesa`
--
ALTER TABLE `j17_defesa`
  ADD PRIMARY KEY (`idDefesa`,`aluno_id`),
  ADD KEY `fk_defesa_aluno` (`aluno_id`);

--
-- Indexes for table `j17_lista_alunos`
--
ALTER TABLE `j17_lista_alunos`
  ADD PRIMARY KEY (`matricula`);

--
-- Indexes for table `j17_membrosbanca`
--
ALTER TABLE `j17_membrosbanca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idProfessor` (`idProfessor`);

--
-- Indexes for table `j17_prorrogacoes`
--
ALTER TABLE `j17_prorrogacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idAluno` (`idAluno`),
  ADD KEY `idOrientador` (`idOrientador`);

--
-- Indexes for table `j17_publicacoes`
--
ALTER TABLE `j17_publicacoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `j17_trancamentos`
--
ALTER TABLE `j17_trancamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idAluno` (`idAluno`),
  ADD KEY `idOrientador` (`idOrientador`);

--
-- Indexes for table `j17_user`
--
ALTER TABLE `j17_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `j17_aluno`
--
ALTER TABLE `j17_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=713;
--
-- AUTO_INCREMENT for table `j17_banca_has_membrosbanca`
--
ALTER TABLE `j17_banca_has_membrosbanca`
  MODIFY `banca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=767;
--
-- AUTO_INCREMENT for table `j17_defesa`
--
ALTER TABLE `j17_defesa`
  MODIFY `idDefesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=583;
--
-- AUTO_INCREMENT for table `j17_membrosbanca`
--
ALTER TABLE `j17_membrosbanca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
--
-- AUTO_INCREMENT for table `j17_publicacoes`
--
ALTER TABLE `j17_publicacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6125;
--
-- AUTO_INCREMENT for table `j17_trancamentos`
--
ALTER TABLE `j17_trancamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `j17_user`
--
ALTER TABLE `j17_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `j17_banca_has_membrosbanca`
--
ALTER TABLE `j17_banca_has_membrosbanca`
  ADD CONSTRAINT `fk_banca_idbanca` FOREIGN KEY (`banca_id`) REFERENCES `j17_defesa` (`idDefesa`),
  ADD CONSTRAINT `fk_banca_membrobanca` FOREIGN KEY (`membrosbanca_id`) REFERENCES `j17_membrosbanca` (`id`);

--
-- Limitadores para a tabela `j17_defesa`
--
ALTER TABLE `j17_defesa`
  ADD CONSTRAINT `fk_defesa_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `j17_aluno` (`id`);

--
-- Limitadores para a tabela `j17_membrosbanca`
--
ALTER TABLE `j17_membrosbanca`
  ADD CONSTRAINT `fk_membrosbanca_professorppgi` FOREIGN KEY (`idProfessor`) REFERENCES `j17_user` (`id`);

--
-- Limitadores para a tabela `j17_prorrogacoes`
--
ALTER TABLE `j17_prorrogacoes`
  ADD CONSTRAINT `fk_prorrogacao_aluno` FOREIGN KEY (`idAluno`) REFERENCES `j17_aluno` (`id`),
  ADD CONSTRAINT `fk_prorrogacao_orientador` FOREIGN KEY (`idOrientador`) REFERENCES `j17_user` (`id`);

--
-- Limitadores para a tabela `j17_trancamentos`
--
ALTER TABLE `j17_trancamentos`
  ADD CONSTRAINT `fk_trancamento_aluno` FOREIGN KEY (`idAluno`) REFERENCES `j17_aluno` (`id`),
  ADD CONSTRAINT `fk_trancamento_orientador` FOREIGN KEY (`idOrientador`) REFERENCES `j17_user` (`id`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `atualizaFinalizado` ON SCHEDULE EVERY 1 DAY STARTS '2016-09-29 19:05:44' ON COMPLETION PRESERVE ENABLE DO UPDATE j17_contproj_projetos SET status = 'Encerrado' WHERE data_fim_alterada < CURDATE()$$

CREATE DEFINER=`root`@`localhost` EVENT `atualizaIniciado` ON SCHEDULE EVERY 1 DAY STARTS '2016-09-29 19:05:14' ON COMPLETION PRESERVE ENABLE DO UPDATE j17_contproj_projetos SET status = 'Ativo' WHERE status != 'Ativo' AND data_inicio >= CURDATE()$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
