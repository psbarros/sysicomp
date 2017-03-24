<?php

namespace app\models;
use Yii;

//	include_once("components/com_portalsecretaria/upload_csv/controller/conexao.php");

	class ParserCSV{

	//	private $conn;
		private $disciplinas;
		private $alunos;
	 
		public function __construct($data) {
			//$registry = Registry::getInstance();
			//$this->conn = $registry->get('Connection');
			$this->disciplinas = $data;
			$this->alunos = $data;
		}

		/////////////////////////
		//Oferta de Disciplinas//
		/////////////////////////

		function setDisciplinas(){

			try {
				for ($i=0; $i < count($this->disciplinas); $i++) { 
					$idRH = $this->disciplinas[$i]['ID_CONTRATO_RH'];
					$codDisciplina = $this->disciplinas[$i]['COD_DISCIPLINA'];
					$codTurma = $this->disciplinas[$i]['COD_TURMA'];
					$dia = $this->disciplinas[$i]['DIA_SEMANA_ITEM'];
					$nome = htmlentities($this->disciplinas[$i]['NOME_DISCIPLINA'], ENT_COMPAT,'ISO-8859-1');
				
					$hr = explode(" ", $this->disciplinas[$i]['HR_INICIO']);
					if (isset($hr[1])) {
						$aux = explode(":", $hr[1]);
						$horaInicio = $aux[0];	
					}
					else
						$horaInicio = "";

					$hr = explode(" ", $this->disciplinas[$i]['HR_FIM']);
					if (isset($hr[1])) {
						$aux = explode(":", $hr[1]);
						$horaFim = $aux[1]==0?$aux[0]:$aux[0]+1;	
					}
					else
						$horaFim = "";

					$creditos = $this->disciplinas[$i]['CREDITOS'];
					$semestre = $this->disciplinas[$i]['PERIODO']{1};
					$ano = $this->disciplinas[$i]['ANO'];
					$vagasOferecidas = $this->disciplinas[$i]['VAGAS_OFERECIDAS'];

					$sql = "INSERT INTO j17_oferta_disciplinas (idRH, codDisciplina, codTurma, dia, nome, horaInicio, horaFim, creditos, semestre, ano, vagasOferecidas) 
		                 VALUES ($idRH, '$codDisciplina', '$codTurma', '$dia', '".html_entity_decode($nome)."', '$horaInicio', '$horaFim', '$creditos', '$semestre', $ano, $vagasOferecidas)";
					Yii::$app->db->createCommand($sql)->execute();
				}	
				return true;
			}
			catch(Exception $e){
					return false;
			}				

		}
		
		function setAlunos(){

			$dataAtualizacao = date('d/m/Y');
			$cc_matriculados = 0;
			$cc_formados = 0;
			$si_matriculados = 0;
			$si_formados = 0;

			for ($i = 0; $i<count($this->alunos); $i++){
				if ($this->alunos[$i]['COD_CURSO'] == "IE08" && html_entity_decode(htmlentities($this->alunos[$i]['FORMA_EVASA0'], ENT_COMPAT,'ISO-8859-1')) == "Sem Evasão")
					$cc_matriculados++;
				else if ($this->alunos[$i]['COD_CURSO'] == "IE08" && html_entity_decode(htmlentities($this->alunos[$i]['FORMA_EVASA0'], ENT_COMPAT,'ISO-8859-1')) == "Formado")
					$cc_formados++;
				else if ($this->alunos[$i]['COD_CURSO'] == "IE15" && html_entity_decode(htmlentities($this->alunos[$i]['FORMA_EVASA0'], ENT_COMPAT,'ISO-8859-1')) == "Sem Evasão")
					$si_matriculados++;
				else if ($this->alunos[$i]['COD_CURSO'] == "IE15" && html_entity_decode(htmlentities($this->alunos[$i]['FORMA_EVASA0'], ENT_COMPAT,'ISO-8859-1')) == "Formado")
					$si_formados++;
			}


	        try {
	            $sql = "DELETE FROM j17_numero_alunos";
				Yii::$app->db->createCommand($sql)->execute();
	        }
	        catch(Exception $e) {
	        	print_r($e);
	            $this->conn->rollback();
	        }	

	        try {
				
				$sql = "INSERT INTO j17_numero_alunos (cc_matriculados, cc_formados, si_matriculados, si_formados, ultimaAtualizacao) 
	                 VALUES ($cc_matriculados, $cc_formados, $si_matriculados, $si_formados, $dataAtualizacao)";
				Yii::$app->db->createCommand($sql)->execute();

				return true;
			}catch(Exception $e){
				return false;
			}
	 			
		}		
	}
?>