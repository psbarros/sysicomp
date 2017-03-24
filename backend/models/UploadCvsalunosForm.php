<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\ParserCSV;

class UploadCvsalunosForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $csvAlunosFile;

    public function rules()
    {
        return [
            [['csvAlunosFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv'],
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
			$nomeArquivo = 'uploads/csv-alunos-' . $idUsuario . '.' . $this->csvAlunosFile->extension;
            $this->csvAlunosFile->saveAs($nomeArquivo);
				
			$data = array();
			$data = csv_to_array($fileName, ',');

			$parserCSV = new ParserCSV($data);
			$parserCSV->setAlunos();
			
        } else {
            return false;
        }
		return true;
    }
    
}