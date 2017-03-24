DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `prazoVencido`()
begin
create temporary table a(id int, curso int, dias int);
insert into a(id, curso, dias) select a.id,a.curso,DATEDIFF((select if(a.anoconclusao is NULL,CURDATE(),a.anoconclusao)),a.dataingresso) as dias from j17_aluno as a;
create temporary table p(id int, dias int, q int);
insert into p(id,dias,q) select p.idAluno,sum(p.qtdDias) as dias,count(p.idAluno) as q from j17_prorrogacoes as p group by p.idAluno;
create temporary table t(id int, dias int, q int);
insert into t(id,dias,q) select t.idAluno,sum(DATEDIFF((select if(t.dataTermino is NULL,CURDATE(),t.dataTermino)),t.dataInicio)) as dias,count(t.idAluno) as q from j17_trancamentos as t group by t.idAluno;
create temporary table pv(id int, curso int, dNormal int, dProrrogacao int, qProrrogacao int, dTrancamento int, qTrancamento int);
insert into pv(id, curso, dNormal, dProrrogacao, qProrrogacao, dTrancamento, qTrancamento) select a.id,a.curso,a.dias as dNormal,p.dias as dProrrogacao,p.q as qProrrogacao,t.dias as dTrancamento,t.q as qTrancamento from a left join p on p.id=a.id left join t on t.id=a.id;
end$$
DELIMITER ;
