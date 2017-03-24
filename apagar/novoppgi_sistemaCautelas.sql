-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 13-Dez-2016 às 16:19
-- Versão do servidor: 5.6.26
-- PHP Version: 5.5.28

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
-- Estrutura da tabela `j17_cautela`
--

CREATE TABLE IF NOT EXISTS `j17_cautela` (
  `idCautela` int(11) NOT NULL,
  `NomeResponsavel` varchar(50) NOT NULL,
  `OrigemCautela` varchar(50) NOT NULL,
  `DataDevolucao` varchar(50) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `ValidadeCautela` varchar(50) DEFAULT NULL,
  `TelefoneResponsavel` varchar(50) NOT NULL,
  `ImagemCautela` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `j17_cautela`
--

INSERT INTO `j17_cautela` (`idCautela`, `NomeResponsavel`, `OrigemCautela`, `DataDevolucao`, `Email`, `ValidadeCautela`, `TelefoneResponsavel`, `ImagemCautela`) VALUES
(1, 'Daniel Godinho', 'Icomp', '12/12/2012', 'dgp@icomp.ufam.edu.br', '01/01/2016', '99999-9999', ''),
(2, 'Priscila Barros', 'FAPEAM', '31-01-2017', 'priscila@gmail.com', '31-05-2017', '99999-9999', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_cautela_avulsa`
--

CREATE TABLE IF NOT EXISTS `j17_cautela_avulsa` (
  `idCautelaAvulsa` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `NomeResponsavel` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `ValidadeCautela` varchar(50) DEFAULT NULL,
  `TelefoneResponsavel` int(20) NOT NULL,
  `ObservacoesDescarte` varchar(50) NOT NULL,
  `ImagemCautela` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_descarte_equipamento`
--

CREATE TABLE IF NOT EXISTS `j17_descarte_equipamento` (
  `idDescarte` int(11) NOT NULL,
  `NomeResponsavel` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `TelefoneResponsavel` varchar(50) NOT NULL,
  `ObservacoesDescarte` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `j17_equipamento`
--

CREATE TABLE IF NOT EXISTS `j17_equipamento` (
  `idEquipamento` int(11) NOT NULL,
  `NomeEquipamento` varchar(50) NOT NULL,
  `Nserie` varchar(50) DEFAULT NULL,
  `NotaFiscal` varchar(50) NOT NULL,
  `Localizacao` varchar(50) NOT NULL,
  `StatusEquipamento` varchar(50) NOT NULL,
  `OrigemEquipamento` varchar(50) NOT NULL,
  `ImagemEquipamento` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `j17_cautela`
--
ALTER TABLE `j17_cautela`
  ADD PRIMARY KEY (`idCautela`);

--
-- Indexes for table `j17_cautela_avulsa`
--
ALTER TABLE `j17_cautela_avulsa`
  ADD PRIMARY KEY (`idCautelaAvulsa`,`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `j17_descarte_equipamento`
--
ALTER TABLE `j17_descarte_equipamento`
  ADD PRIMARY KEY (`idDescarte`);

--
-- Indexes for table `j17_equipamento`
--
ALTER TABLE `j17_equipamento`
  ADD PRIMARY KEY (`idEquipamento`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `j17_cautela`
--
ALTER TABLE `j17_cautela`
  MODIFY `idCautela` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `j17_cautela_avulsa`
--
ALTER TABLE `j17_cautela_avulsa`
  MODIFY `idCautelaAvulsa` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `j17_descarte_equipamento`
--
ALTER TABLE `j17_descarte_equipamento`
  MODIFY `idDescarte` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `j17_equipamento`
--
ALTER TABLE `j17_equipamento`
  MODIFY `idEquipamento` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `j17_cautela_avulsa`
--
ALTER TABLE `j17_cautela_avulsa`
  ADD CONSTRAINT `j17_cautela_avulsa_ibfk_1` FOREIGN KEY (`id`) REFERENCES `j17_user` (`id`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `atualizaIniciado` ON SCHEDULE EVERY 1 DAY STARTS '2016-09-29 19:05:14' ON COMPLETION PRESERVE ENABLE DO UPDATE j17_contproj_projetos SET status = 'Ativo' WHERE status != 'Ativo' AND data_inicio >= CURDATE()$$

CREATE DEFINER=`root`@`localhost` EVENT `atualizaFinalizado` ON SCHEDULE EVERY 1 DAY STARTS '2016-09-29 19:05:44' ON COMPLETION PRESERVE ENABLE DO UPDATE j17_contproj_projetos SET status = 'Encerrado' WHERE data_fim_alterada < CURDATE()$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
