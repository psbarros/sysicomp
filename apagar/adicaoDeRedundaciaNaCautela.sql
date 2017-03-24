UPDATE j17_cautela

SET idEquipamento = cast(Equipamento as  INT)

WHERE
	idEquipamento != cast(Equipamento as  INT);