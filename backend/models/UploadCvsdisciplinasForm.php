<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\ParserCSV;

class UploadCvsdisciplinasForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $csvDisciplinasFile;

    public function rules()
    {
        return [
         //   [['csvDisciplinasFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv'],
        ];
    }
    
    function csv_to_array($filename, $delimiter){
	    if(!file_exists($filename) || !is_readable($filename))
	        return FALSE;

	    $header = NULL;
	    $data = array();
	    if (($handle = fopen($filename, 'r')) !== FALSE){
	    	while (($row = fgetcsv($handle, $delimiter)) !== FALSE){
	        	if(!$header)
	                $header = $row;
	            else{
	            	if (count($row) != 1) 
	                	$data[] = array_combine($header, $row);
	            }
	        }
	        fclose($handle);
	    }
	    return $data;
	}
	
	public function upload($idUsuario)
    {
        if ($this->validate()) {
			$nomeArquivo = 'uploads/csv-disciplinas-' . $idUsuario . '.' . $this->csvDisciplinasFile->extension;
            $this->csvDisciplinasFile->saveAs($nomeArquivo);
				
			$data = array();
			$data = $this->csv_to_array($nomeArquivo, ',');

			$parserCSV = new ParserCSV($data);
			$parserCSV->setDisciplinas();
			
        } else {
            return false;
        }
		return true;
    }
    
}