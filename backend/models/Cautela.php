<?php

namespace backend\models;

use Yii;
use backend\models\Equipamento;
use backend\models\ContProjProjetos;
use app\models\BaixaCautela;
/**
 * This is the model class for table "j17_cautela".
 *
 * @property integer $idCautela
 * @property string $NomeResponsavel
 * @property string $OrigemCautela
 * @property string $DataDevolucao
 * @property string $Email
 * @property string $ValidadeCautela
 * @property string $TelefoneResponsavel
 * @property string $ImagemCautela
 * @property string $Equipamento
 * @property string $StatusCautela
 * @property integer $idEquipamento
 * @property integer $idProjeto
 */
class Cautela extends \yii\db\ActiveRecord
{
	public $idsmulticautela;
	public $nomeEquipamento;
	public $nomeProjeto;
	public $validade;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_cautela';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NomeResponsavel', 'OrigemCautela', 'Email', 'TelefoneResponsavel', 'StatusCautela', 'idEquipamento', 'idProjeto', 'DataDevolucao', 'dataInicial'], 'required'],
            [['idEquipamento', 'idProjeto'], 'integer'],
            [['NomeResponsavel', 'OrigemCautela', 'DataDevolucao', 'Email', 'ValidadeCautela', 'TelefoneResponsavel', 'Equipamento','nomeEquipamento', 'StatusCautela'], 'string', 'max' => 50],
        	[['dataInicial'], 'string', 'max'=>20],
            [['ImagemCautela'], 'string', 'max' => 100],
        	[['nomeProjeto'], 'string', 'max'=>200],
        	[['Email'], 'email'],
            [['idEquipamento'], 'exist', 'skipOnError' => true, 'targetClass' => Equipamento::className(), 'targetAttribute' => ['idEquipamento' => 'idEquipamento']],
        	[['idsmulticautela', 'nomeEquipamento', 'nomeProjeto', 'validade'], 'safe'],	
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idCautela' => 'Cod. Cautela',
            'NomeResponsavel' => 'Responsável',
            'OrigemCautela' => 'Origem',
            'DataDevolucao' => 'Devolucao Prevista',
            'Email' => 'Email',
            'ValidadeCautela' => 'Validade',
            'TelefoneResponsavel' => 'Telefone',
            'ImagemCautela' => 'Imagem Cautela',
            'Equipamento' => 'Equipamento',
            'StatusCautela' => 'Status',
            'idEquipamento' => 'Cod. Equipamento',
            'idProjeto' => 'Projeto',
        	'idsmulticautela' => 'Cautelas',
        	'nomeEquipamento'=>'Nome Equip.',
        	'nomeProjeto'=>'Projeto',
        	'dataInicial'=> 'Data Inicial',
        	'validade' => 'Validade (dias)',
        ];
    }
    
    public function getCautelatemequipamento(){
    	return $this->hasOne(Equipamento::className(),['idEquipamento'=>'idEquipamento']);
    }
    
    public function getNomeEquipamento(){
    	return $this->cautelatemequipamento->NomeEquipamento;
    }
    
    public function getCautelatemprojeto(){
    	return $this->hasOne(ContProjProjetos::className(),['id'=>'idProjeto']);
    }
    
    public function getCautelaTemBaixa(){
    	if($this->StatusCautela === Cautela::getStatusConcluida())
    		return $this->hasOne(BaixaCautela::className(), ['idCautela'=>'idCautela']);
    	
    	return false; 
    }
    
    public function getValidade(){
    	return $this->validadeCalculada;
    }
    
    public function getValidadeCalculada(){
    	if(!$this->isNewRecord){
    		$dataIni = date_create_from_format('d-m-Y', $this->dataInicial);
    		$dataFim = date_create_from_format('d-m-Y', $this->DataDevolucao);

    		$strIni = $dataIni->format('Y-m-d');
    		$strFim = $dataFim->format('Y-m-d');
    		
    		return floor( (strtotime($strFim)-strtotime($strIni))/86400 );
    	}
    	return 0;
    }
    
    public static function getStatusAtraso(){
    	return "Em atraso";
    }
    
    public static function getStatusConcluida(){
    	return "Concluída";
    }
    
    public static function getStatusAberto(){
    	return "Em aberto";
    }
    
    public function getBaixaReversivel(){
    	//Se somente se (possui baixa) & (equipamento disponivel)
    	if( isset($this->cautelaTemBaixa->idBaixaCautela) )
    		if($this->cautelatemequipamento->StatusEquipamento == Equipamento::getStatusDisponivel())
    			return true;
    		
    	return false;
    }
    /* Retorna o Status adequado, com base na Data de Devolucao Prevista e a data atual do sistema. 
     */
    public function getAjustaStatus(){
    	//Status permitidos: 'Em atraso' e 'Em uso'
    	if(strtotime($this->DataDevolucao)<(strtotime(date('d-m-Y')))){
    		return Cautela::getStatusAtraso();
    	}elseif(strtotime($this->DataDevolucao)>=(strtotime(date('d-m-Y')))){
    		return Cautela::getStatusAberto();
    	}
    }
    
}
