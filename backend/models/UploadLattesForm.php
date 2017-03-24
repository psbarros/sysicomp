<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\ParserXML;

class UploadLattesForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $lattesFile;

    public function rules()
    {
        return [
            [['lattesFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xml'],
        ];
    }
    
    public function upload($idUsuario)
    {
        if ($this->validate()) {
			$nomeArquivo = 'uploads/lattes-' . $idUsuario . '.' . $this->lattesFile->extension;
            $this->lattesFile->saveAs($nomeArquivo);
			if($xml = simplexml_load_file($nomeArquivo)){
				$parserXML = new ParserXML($xml);

				$idProfessor = $idUsuario;
				$parserXML->setProfessor($idProfessor);
				$parserXML->setPremios($idProfessor);
				$parserXML->setProjetos($idProfessor);
				$parserXML->setPublicacoesConferencias($idProfessor);
				$parserXML->setPublicacoesPeriodicos($idProfessor);
				$parserXML->setPublicacoesLivros($idProfessor);
				$parserXML->setPublicacoesCapitulos($idProfessor);
						
				$parserXML->setOrientacoesAndamentoGraduacao($idProfessor);
				$parserXML->setOrientacoesAndamentoMestrado($idProfessor);
				$parserXML->setOrientacoesAndamentoDoutorado($idProfessor);
						
				$parserXML->setOrientacoesConcluidasGraduacao($idProfessor);
				$parserXML->setOrientacoesConcluidasMestrado($idProfessor);
				$parserXML->setOrientacoesConcluidasDoutorado($idProfessor);

				$alias = $parserXML->getAliasById($idProfessor);
            }
        } else {
            return false;
        }
		return true;
    }
    
	/*Extração dos periódicos e conferências e salvamento no banco*/
    public function uploadXml($xmlFile) {
		
		$alias = "";
        if(!isset($xmlFile))
            return true;
        
        else{
            if($xmlFile->type == 'text/xml'){
                $caminho = 'uploads/';
                $xmlFile->saveAs($caminho."publicacao.xml");
                if($xml = simplexml_load_file($this->diretorio.'publicacao.xml')){
					include_once($caminho."parser_xml.php");						
					$parserXML = new ParserXML($xml);

					$idProfessor = Yii::$app->user->identity->id;
					$parserXML->setProfessor($idProfessor);
					$parserXML->setPremios($idProfessor);
					$parserXML->setProjetos($idProfessor);
					$parserXML->setPublicacoesConferencias($idProfessor);
					$parserXML->setPublicacoesPeriodicos($idProfessor);
					$parserXML->setPublicacoesLivros($idProfessor);
					$parserXML->setPublicacoesCapitulos($idProfessor);
						
					$parserXML->setOrientacoesAndamentoGraduacao($idProfessor);
					$parserXML->setOrientacoesAndamentoMestrado($idProfessor);
					$parserXML->setOrientacoesAndamentoDoutorado($idProfessor);
						
					$parserXML->setOrientacoesConcluidasGraduacao($idProfessor);
					$parserXML->setOrientacoesConcluidasMestrado($idProfessor);
					$parserXML->setOrientacoesConcluidasDoutorado($idProfessor);

					$alias = $parserXML->getAliasById($idProfessor);
                }
            }
        }
        return false;
    }

}