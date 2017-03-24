
-- Transforma a chave primaria em AutoIncremental
ALTER TABLE `j17_equipamento` CHANGE `idEquipamento` `idEquipamento` INT(11) NOT NULL AUTO_INCREMENT;


-- Transforma a chave primaria em AutoIncremental
ALTER TABLE `j17_cautela_avulsa` CHANGE `idCautelaAvulsa` `idCautelaAvulsa` INT(11) NOT NULL AUTO_INCREMENT;