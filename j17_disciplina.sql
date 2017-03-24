CREATE TABLE `j17_disciplina` (
  `codDisciplina` varchar(10) CHARACTER SET utf8 NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8 NOT NULL,
  `creditos` int(11) NOT NULL,
  `nomeCurso` varchar(50) DEFAULT NULL,
  `cargaHoraria` int(11) NOT NULL,
  `instituicao` varchar(50) DEFAULT NULL,
  `preRequisito` int(11) DEFAULT NULL,
  `obrigatoria` int(11) DEFAULT NULL,
  `id` bigint(20) unsigned AUTO_INCREMENT,
  PRIMARY KEY (`codDisciplina`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Lista de Disciplinas para Aproveitamento.';