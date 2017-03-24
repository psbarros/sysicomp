<?php

namespace app\models;
use Yii;
//include_once("controller/conexao.php");

	class ParserXML{

		private $xml;
		//private $conn;
	 
		////////////////////////
		//Informações Pessoais//
		////////////////////////
		
		public function __construct($x) {
			$this->xml = $x;
		}
		
		function setProfessor($idProfessor){

			$idLattes = $this->xml['NUMERO-IDENTIFICADOR'];

			//Forma��o acad�mica
			if (!empty($this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'DOUTORADO'}['NOME-CURSO'])){
				$formacao = "3;" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'DOUTORADO'}['NOME-CURSO'] . ";" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'DOUTORADO'}['NOME-INSTITUICAO'] . ";" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'DOUTORADO'}['ANO-DE-CONCLUSAO'];
			}
			else if(!empty($this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'MESTRADO'}['NOME-CURSO'])){
				$formacao = "2;" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'MESTRADO'}['NOME-CURSO'] . ";" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'MESTRADO'}['NOME-INSTITUICAO'] . ";" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'MESTRADO'}['ANO-DE-CONCLUSAO'];
			}
			else{
				$formacao = "1;" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'GRADUACAO'}['NOME-CURSO'] . ";" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'GRADUACAO'}['NOME-INSTITUICAO'] . ";" . $this->xml->{'DADOS-GERAIS'}->{'FORMACAO-ACADEMICA-TITULACAO'}->{'GRADUACAO'}['ANO-DE-CONCLUSAO'];
			}
		
			$formacao = htmlentities($formacao, ENT_QUOTES);

			//Resumo
			$resumo = htmlentities($this->xml->{'DADOS-GERAIS'}->{'RESUMO-CV'}['TEXTO-RESUMO-CV-RH'], ENT_QUOTES);

			$data = date('d/m/Y');
			
			try{
				$sql = "UPDATE j17_user SET idLattes = '$idLattes', formacao = '$formacao', resumo = '$resumo', ultimaAtualizacao = '$data' 
								WHERE id = $idProfessor;";
				Yii::$app->db->createCommand($sql)->execute();

				return true;
			}catch(Exception $e){
				return false;
			}			
		}

		/////////////////////
		//Prêmios e Títulos//
		/////////////////////

		function setPremios($idProfessor){

			$sql = "DELETE FROM j17_premios WHERE idProfessor = $idProfessor;";
			Yii::$app->db->createCommand($sql)->execute();

			foreach ($this->xml->{'DADOS-GERAIS'}->{'PREMIOS-TITULOS'} as $premio) {
				for ($i=0; $i < count($premio); $i++) { 
					
					$titulo = htmlentities($premio->{'PREMIO-TITULO'}[$i]['NOME-DO-PREMIO-OU-TITULO'], ENT_QUOTES);
					$entidade = htmlentities($premio->{'PREMIO-TITULO'}[$i]['NOME-DA-ENTIDADE-PROMOTORA'], ENT_QUOTES);
					$ano = $premio->{'PREMIO-TITULO'}[$i]['ANO-DA-PREMIACAO'];
					
					$sql = "INSERT INTO j17_premios (idProfessor, titulo, entidade, ano) VALUES ($idProfessor, '$titulo', '$entidade', '$ano')";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}
		}

		////////////////////////
		//Projetos de Pesquisa//
		////////////////////////

		function setProjetos($idProfessor){
			try{
				$sql = "DELETE FROM j17_projetos WHERE idProfessor = $idProfessor";
				Yii::$app->db->createCommand($sql)->execute();
			}catch(Exception $e){
				return false;
			}		
         		
			foreach ($this->xml->{'DADOS-GERAIS'}->{'ATUACOES-PROFISSIONAIS'}->{'ATUACAO-PROFISSIONAL'} as $atuacao){
				if (isset($atuacao->{'ATIVIDADES-DE-PARTICIPACAO-EM-PROJETO'})){
				    foreach ($atuacao->{'ATIVIDADES-DE-PARTICIPACAO-EM-PROJETO'} as $projeto) {
					for ($i=0; $i < count($projeto); $i++) { 
						
						for ($j = 0; $j < count($projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}); $j++){

							if ($projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]['NATUREZA'] == "PESQUISA") {
							
								$titulo = $projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]['NOME-DO-PROJETO'];
								$descricao = $projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]['DESCRICAO-DO-PROJETO'];
								$inicio = $projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]['ANO-INICIO'];
								$fim = $projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]['ANO-FIM'];
								
								if ($projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]->{'EQUIPE-DO-PROJETO'}->{'INTEGRANTES-DO-PROJETO'}['FLAG-RESPONSAVEL'] == "SIM"){
									$papel = "(Coordenador)";
								} 
								else{
									$papel = "(Participante)";
								}

								$fin = "";
								
								if (isset($projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]->{'FINANCIADORES-DO-PROJETO'})) {
								
									foreach ($projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]->{'FINANCIADORES-DO-PROJETO'} as $financiadores) {
										for ($z=0; $z < count($financiadores); $z++) { 
											if ($financiadores->{'FINANCIADOR-DO-PROJETO'}[$z]['NOME-INSTITUICAO'] != ""){
												$fin .= $financiadores->{'FINANCIADOR-DO-PROJETO'}[$z]['NOME-INSTITUICAO'].", ";
											}else{
												$fin .= "N�o possui";
											}
										}
									}
								
								}
								else{
									$fin .= "N�o possui";
								} 
								
								$integrantesProj = "";
									if (isset($projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]->{'EQUIPE-DO-PROJETO'})) {
										foreach ($projeto->{'PARTICIPACAO-EM-PROJETO'}[$i]->{'PROJETO-DE-PESQUISA'}[$j]->{'EQUIPE-DO-PROJETO'} as $integrantes) {
											foreach ($integrantes->{'INTEGRANTES-DO-PROJETO'} as $integrante) {
												$integrantesProj .= $integrante['NOME-COMPLETO']."; ";
											}
										}
									}
								
							try{
								$fin = rtrim($fin, ", ");
								$integrantesProj = rtrim($integrantesProj, "; ");
								$sql = "INSERT INTO j17_projetos (idProfessor, titulo, descricao, inicio, fim, papel, financiadores, integrantes) VALUES ($idProfessor, '".htmlentities($titulo, ENT_QUOTES)."', '".htmlentities($descricao, ENT_QUOTES)."', '$inicio', '$fim', '$papel', '".htmlentities($fin, ENT_QUOTES)."', '".htmlentities($integrantesProj, ENT_QUOTES)."')";
								Yii::$app->db->createCommand($sql)->execute();

							}catch(Exception $e){
								return false;
							}	
					      }
				       }
					}
				}
			}
		  }
		  	return true;	
		}

		//////////////////////////////////////
		//Artigos publicados em Conferências//
		//////////////////////////////////////

		function setPublicacoesConferencias($idProfessor){
		
			$sql = "DELETE FROM j17_publicacoes WHERE idProfessor = $idProfessor AND tipo = 1";
			Yii::$app->db->createCommand($sql)->execute();

			foreach ($this->xml->{'PRODUCAO-BIBLIOGRAFICA'}->{'TRABALHOS-EM-EVENTOS'} as $publicacao) {
				
				for ($i=0; $i < count($publicacao); $i++) { 
					
					$titulo = $publicacao->{'TRABALHO-EM-EVENTOS'}[$i]->{'DADOS-BASICOS-DO-TRABALHO'}['TITULO-DO-TRABALHO'];
					$local = $publicacao->{'TRABALHO-EM-EVENTOS'}[$i]->{'DETALHAMENTO-DO-TRABALHO'}['NOME-DO-EVENTO'];
					$ano = $publicacao->{'TRABALHO-EM-EVENTOS'}[$i]->{'DADOS-BASICOS-DO-TRABALHO'}['ANO-DO-TRABALHO']; 
					$tipo = 1;
					$natureza = ucwords(strtolower($publicacao->{'TRABALHO-EM-EVENTOS'}[$i]->{'DADOS-BASICOS-DO-TRABALHO'}['NATUREZA'])); 
					$autores = "";
					foreach ($publicacao->{'TRABALHO-EM-EVENTOS'}[$i]->{'AUTORES'} as $autor) {
						$autores .= ucwords(strtolower($autor['NOME-COMPLETO-DO-AUTOR']))."; ";
					}
					
					$sql = "INSERT INTO j17_publicacoes (idProfessor, titulo, ano, local, tipo, natureza, autores) VALUES ($idProfessor, '".htmlentities($titulo, ENT_QUOTES)."', '$ano', '".htmlentities($local, ENT_QUOTES)."', '$tipo', '".htmlentities($natureza, ENT_QUOTES)."', '".htmlentities($autores, ENT_QUOTES)."')";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}	
		}

		////////////////////////////////////
		//Artigos publicados em Periódicos//
		////////////////////////////////////

		function setPublicacoesPeriodicos($idProfessor){
		
			$sql = "DELETE FROM j17_publicacoes WHERE idProfessor = $idProfessor AND tipo = 2";
			Yii::$app->db->createCommand($sql)->execute();

			foreach ($this->xml->{'PRODUCAO-BIBLIOGRAFICA'}->{'ARTIGOS-PUBLICADOS'} as $publicacao) {
				
				for ($i=0; $i < count($publicacao); $i++) { 
					
					$titulo = $publicacao->{'ARTIGO-PUBLICADO'}[$i]->{'DADOS-BASICOS-DO-ARTIGO'}['TITULO-DO-ARTIGO'];
					$local = $publicacao->{'ARTIGO-PUBLICADO'}[$i]->{'DETALHAMENTO-DO-ARTIGO'}['TITULO-DO-PERIODICO-OU-REVISTA'];
					$ano = $publicacao->{'ARTIGO-PUBLICADO'}[$i]->{'DADOS-BASICOS-DO-ARTIGO'}['ANO-DO-ARTIGO']; 
					$tipo = 2;
					$autores = "";
					foreach ($publicacao->{'ARTIGO-PUBLICADO'}[$i]->{'AUTORES'} as $autor) {
						$autores .= ucwords(strtolower($autor['NOME-COMPLETO-DO-AUTOR']))."; ";
					}
					
					$sql = "INSERT INTO j17_publicacoes (idProfessor, titulo, ano, local, tipo, autores) VALUES ($idProfessor, '".htmlentities($titulo, ENT_QUOTES)."', '$ano', '".htmlentities($local, ENT_QUOTES)."', '$tipo', '".htmlentities($autores, ENT_QUOTES)."')";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}	
		}

		/////////////////////
		//Livros Publicados//
		/////////////////////

		function setPublicacoesLivros($idProfessor){
		
			$sql = "DELETE FROM j17_publicacoes WHERE idProfessor = $idProfessor AND tipo = 3";
			Yii::$app->db->createCommand($sql)->execute();

			if (isset($this->xml->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'LIVROS-PUBLICADOS-OU-ORGANIZADOS'})) {
				foreach ($this->xml->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'LIVROS-PUBLICADOS-OU-ORGANIZADOS'} as $publicacao) {
					
					for ($i=0; $i < count($publicacao); $i++) { 
						$titulo = $publicacao->{'LIVRO-PUBLICADO-OU-ORGANIZADO'}[$i]->{'DADOS-BASICOS-DO-LIVRO'}['TITULO-DO-LIVRO'];
						$ano = $publicacao->{'LIVRO-PUBLICADO-OU-ORGANIZADO'}[$i]->{'DADOS-BASICOS-DO-LIVRO'}['ANO']; 
						$tipo = 3;
						
						$sql = "INSERT INTO j17_publicacoes (idProfessor, titulo, ano, tipo) VALUES ($idProfessor, '".htmlentities($titulo, ENT_QUOTES)."', '$ano', '$tipo')";
						Yii::$app->db->createCommand($sql)->execute();
					}			
				}	
			}
		}
		
		//////////////////////////////////
		//Capítulos de Livros Publicados//
		//////////////////////////////////

		function setPublicacoesCapitulos($idProfessor){

			$sql = "DELETE FROM j17_publicacoes WHERE idProfessor = $idProfessor AND tipo = 4";
			Yii::$app->db->createCommand($sql)->execute();

			if (isset($this->xml->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'CAPITULOS-DE-LIVROS-PUBLICADOS'})) {
				foreach ($this->xml->{'PRODUCAO-BIBLIOGRAFICA'}->{'LIVROS-E-CAPITULOS'}->{'CAPITULOS-DE-LIVROS-PUBLICADOS'} as $publicacao) {
					
					for ($i=0; $i < count($publicacao); $i++) { 
						$titulo = $publicacao->{'CAPITULO-DE-LIVRO-PUBLICADO'}[$i]->{'DADOS-BASICOS-DO-CAPITULO'}['TITULO-DO-CAPITULO-DO-LIVRO'];
						$ano = $publicacao->{'CAPITULO-DE-LIVRO-PUBLICADO'}[$i]->{'DADOS-BASICOS-DO-CAPITULO'}['ANO']; 
						$local = $publicacao->{'CAPITULO-DE-LIVRO-PUBLICADO'}[$i]->{'DETALHAMENTO-DO-CAPITULO'}['TITULO-DO-LIVRO'];
						$tipo = 4;
						
						$sql = "INSERT INTO j17_publicacoes (idProfessor, titulo, ano, local, tipo) VALUES ($idProfessor, '".htmlentities($titulo, ENT_QUOTES)."', '$ano', '".htmlentities($local, ENT_QUOTES)."', '$tipo')";
						Yii::$app->db->createCommand($sql)->execute();
					}
				}
			}	
		}

		////////////////////////////////////////
		//Orientacoes em Andamento - Graduação//
		////////////////////////////////////////

		function setOrientacoesAndamentoGraduacao($idProfessor){

			$sql = "DELETE FROM j17_orientacoes WHERE idProfessor = $idProfessor AND tipo = 1 AND status = 1";
			Yii::$app->db->createCommand($sql)->execute();
			
			foreach ($this->xml->{'DADOS-COMPLEMENTARES'}->{'ORIENTACOES-EM-ANDAMENTO'} as $orientacao) {
				
				for ($i=0; $i < count($orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}) ; $i++) { 
					
					$titulo = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}[$i]->{'DADOS-BASICOS-DA-ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}['TITULO-DO-TRABALHO'];
					$aluno = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}[$i]->{'DETALHAMENTO-DA-ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}['NOME-DO-ORIENTANDO'];
					$ano = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}[$i]->{'DADOS-BASICOS-DA-ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}['ANO'];
					$natureza = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}[$i]->{'DADOS-BASICOS-DA-ORIENTACAO-EM-ANDAMENTO-DE-INICIACAO-CIENTIFICA'}['NATUREZA'];
					$tipo = 1;
					$status = 1;
					
					$sql = "INSERT INTO j17_orientacoes (idProfessor, titulo, aluno, ano, natureza, tipo, status) VALUES ($idProfessor, '$titulo', '$aluno', '$ano', '$natureza', $tipo, $status)";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}	
		}	

		///////////////////////////////////////
		//Orientacoes em Andamento - Mestrado//
		///////////////////////////////////////

		function setOrientacoesAndamentoMestrado($idProfessor){

			$sql = "DELETE FROM j17_orientacoes WHERE idProfessor = $idProfessor AND tipo = 2 AND status = 1";
			Yii::$app->db->createCommand($sql)->execute();
			
			foreach ($this->xml->{'DADOS-COMPLEMENTARES'}->{'ORIENTACOES-EM-ANDAMENTO'} as $orientacao) {
				
				for ($i=0; $i < count($orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-MESTRADO'}); $i++) { 
					
					$titulo = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-MESTRADO'}[$i]->{'DADOS-BASICOS-DA-ORIENTACAO-EM-ANDAMENTO-DE-MESTRADO'}['TITULO-DO-TRABALHO'];
					$aluno = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-MESTRADO'}[$i]->{'DETALHAMENTO-DA-ORIENTACAO-EM-ANDAMENTO-DE-MESTRADO'}['NOME-DO-ORIENTANDO'];
					$ano = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-MESTRADO'}[$i]->{'DADOS-BASICOS-DA-ORIENTACAO-EM-ANDAMENTO-DE-MESTRADO'}['ANO'];
					$tipo = 2;
					$status = 1;
					
					$sql = "INSERT INTO j17_orientacoes (idProfessor, titulo, aluno, ano, tipo, status) VALUES ($idProfessor, '$titulo', '$aluno', '$ano', $tipo, $status)";
					Yii::$app->db->createCommand($sql)->execute();
					
				}
			}	
		}

		////////////////////////////////////////
		//Orientacoes em Andamento - Doutorado//
		////////////////////////////////////////

		function setOrientacoesAndamentoDoutorado($idProfessor){

			$sql = "DELETE FROM j17_orientacoes WHERE idProfessor = $idProfessor AND tipo = 3 AND status = 1";
			Yii::$app->db->createCommand($sql)->execute();

			foreach ($this->xml->{'DADOS-COMPLEMENTARES'}->{'ORIENTACOES-EM-ANDAMENTO'} as $orientacao) {
				
				for ($i=0; $i < count($orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO'}); $i++) { 
					
					$titulo = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO'}[$i]->{'DADOS-BASICOS-DA-ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO'}['TITULO-DO-TRABALHO'];
					$aluno = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO'}[$i]->{'DETALHAMENTO-DA-ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO'}['NOME-DO-ORIENTANDO'];
					$ano = $orientacao->{'ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO'}[$i]->{'DADOS-BASICOS-DA-ORIENTACAO-EM-ANDAMENTO-DE-DOUTORADO'}['ANO'];
					$tipo = 3;
					$status = 1;
					
					$sql = "INSERT INTO j17_orientacoes (idProfessor, titulo, aluno, ano, tipo, status) VALUES ($idProfessor, '$titulo', '$aluno', '$ano', $tipo, $status)";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}	
		}

		//////////////////////////////////////
		//Orientacoes Concluídas - Graduação//
		//////////////////////////////////////

		function setOrientacoesConcluidasGraduacao($idProfessor){

			$sql = "DELETE FROM j17_orientacoes WHERE idProfessor = $idProfessor AND tipo = 1 AND status = 2";
			Yii::$app->db->createCommand($sql)->execute();

			foreach ($this->xml->{'OUTRA-PRODUCAO'}->{'ORIENTACOES-CONCLUIDAS'} as $orientacao) {
				
				for ($i=0; $i < count($orientacao->{'OUTRAS-ORIENTACOES-CONCLUIDAS'}); $i++) { 
					
					$titulo = $orientacao->{'OUTRAS-ORIENTACOES-CONCLUIDAS'}[$i]->{'DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS'}['TITULO']; 
					$aluno = $orientacao->{'OUTRAS-ORIENTACOES-CONCLUIDAS'}[$i]->{'DETALHAMENTO-DE-OUTRAS-ORIENTACOES-CONCLUIDAS'}['NOME-DO-ORIENTADO'];
					$ano = $orientacao->{'OUTRAS-ORIENTACOES-CONCLUIDAS'}[$i]->{'DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS'}['ANO'];
					$natureza = ucwords(strtolower(str_replace("_", " ", $orientacao->{'OUTRAS-ORIENTACOES-CONCLUIDAS'}[$i]->{'DADOS-BASICOS-DE-OUTRAS-ORIENTACOES-CONCLUIDAS'}['NATUREZA'])));
					$tipo = 1;
					$status = 2;
					
					$sql = "INSERT INTO j17_orientacoes (idProfessor, titulo, aluno, ano, natureza, tipo, status) VALUES ($idProfessor, '$titulo', '$aluno', '$ano', '$natureza', $tipo, $status)";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}	
		}	

		/////////////////////////////////////
		//Orientacoes Concluídas - Mestrado//
		/////////////////////////////////////

		function setOrientacoesConcluidasMestrado($idProfessor){
			
			$sql = "DELETE FROM j17_orientacoes WHERE idProfessor = $idProfessor AND tipo = 2 AND status = 2";
			Yii::$app->db->createCommand($sql)->execute();

			foreach ($this->xml->{'OUTRA-PRODUCAO'}->{'ORIENTACOES-CONCLUIDAS'} as $orientacao) {
				
				for ($i=0; $i < count($orientacao->{'ORIENTACOES-CONCLUIDAS-PARA-MESTRADO'}); $i++) { 
					
					$titulo = $orientacao->{'ORIENTACOES-CONCLUIDAS-PARA-MESTRADO'}[$i]->{'DADOS-BASICOS-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO'}['TITULO']; 
					$aluno = $orientacao->{'ORIENTACOES-CONCLUIDAS-PARA-MESTRADO'}[$i]->{'DETALHAMENTO-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO'}['NOME-DO-ORIENTADO'];
					$ano = $orientacao->{'ORIENTACOES-CONCLUIDAS-PARA-MESTRADO'}[$i]->{'DADOS-BASICOS-DE-ORIENTACOES-CONCLUIDAS-PARA-MESTRADO'}['ANO'];
					$tipo = 2;
					$status = 2;
					
					$sql = "INSERT INTO j17_orientacoes (idProfessor, titulo, aluno, ano, tipo, status) VALUES ($idProfessor, '$titulo', '$aluno', '$ano', $tipo, $status)";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}	
		}

		//////////////////////////////////////
		//Orientacoes Concluídas - Doutorado//
		//////////////////////////////////////
		
		function setOrientacoesConcluidasDoutorado($idProfessor){

			$sql = "DELETE FROM j17_orientacoes WHERE idProfessor = $idProfessor AND tipo = 3 AND status = 2";
			Yii::$app->db->createCommand($sql)->execute();

			foreach ($this->xml->{'OUTRA-PRODUCAO'}->{'ORIENTACOES-CONCLUIDAS'} as $orientacao) {
				
				for ($i=0; $i < count($orientacao->{'ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO'}); $i++) { 
					
					$titulo = $orientacao->{'ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO'}[$i]->{'DADOS-BASICOS-DE-ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO'}['TITULO']; 
					$aluno = $orientacao->{'ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO'}[$i]->{'DETALHAMENTO-DE-ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO'}['NOME-DO-ORIENTADO'];
					$ano = $orientacao->{'ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO'}[$i]->{'DADOS-BASICOS-DE-ORIENTACOES-CONCLUIDAS-PARA-DOUTORADO'}['ANO'];
					$tipo = 3;
					$status = 2;
					
					$sql = "INSERT INTO j17_orientacoes (idProfessor, titulo, aluno, ano, tipo, status) VALUES ($idProfessor, '$titulo', '$aluno', '$ano', $tipo, $status)";
					Yii::$app->db->createCommand($sql)->execute();
				}
			}	
		}

		function getAliasById($idProfessor) {
			
			$resultado = User::find()->select('alias')->all();
			return ($resultado[0]->alias);
		}
	 
	}
?>