<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\models\User;

/**
 * This is the model class for table "j17_cautela_avulsa".
 *
 * @property integer $idCautelaAvulsa
 * @property integer $id
 * @property string $NomeResponsavel
 * @property string $Email
 * @property string $ValidadeCautela
 * @property integer $TelefoneResponsavel
 * @property string $ObservacoesDescarte
 * @property string $ImagemCautela
 * @property string $StatusCautelaAvulsa
 */
class CautelaAvulsa extends \yii\db\ActiveRecord
{
	public $idsmulticautela;
    public $tipoCautelaAvulsa;
    public $flagCautelaAvulsa=0;
    public $validade;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_cautela_avulsa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NomeResponsavel', 'Email', 'TelefoneResponsavel', 'ValidadeCautela', 'NomeEquipamento','dataInicial'], 'required'],
            [['id',], 'integer'],
        	[['TelefoneResponsavel'], 'string', 'max'=>15, 'min'=>11],
            [['NomeResponsavel', 'Email', 'ValidadeCautela', 'ObservacoesDescarte', 'StatusCautelaAvulsa', 'origem', 'NomeEquipamento'], 'string', 'max' => 50],
            [['ImagemCautela'], 'string', 'max' => 100],
        	[['dataInicial'], 'string', 'max'=>20],
        	[['Email'], 'email'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id' => 'id']],
        	[['idsmulticautela','validade'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idCautelaAvulsa' => 'Cod. Cautela Avulsa',
            'id' => 'Usuário do Sistema',
            'NomeResponsavel' => 'Nome Responsavel',
            'Email' => 'Email',
            'ValidadeCautela' => 'Devolução Prevista',
            'TelefoneResponsavel' => 'Telefone',
            'ObservacoesDescarte' => 'Observacoes',
            'ImagemCautela' => 'Imagem Cautela',
            'StatusCautelaAvulsa' => 'Status',
        	'origem' => 'Origem',
        	'NomeEquipamento'=>'Equipamento',
        	'dataInicial'=> 'Data Inicial',
        	'validade' => 'Validade (dias)',
        	'idsmulticautela' => 'Cautelas',
        ];
    }

    public function getTipoCautelaAvulsa(){

        if ($this->StatusCautelaAvulsa == "Em aberto"){
            $tipoCautelaAvulsa = "Em aberto";
        }
        else if ($this->StatusCautelaAvulsa == "Concluída"){
            $tipoCautelaAvulsa = "Concluída";
        }
        else if ($this->StatusCautelaAvulsa == "Em atraso"){
            $tipoCautelaAvulsa = "Em atraso";
        }

        return $tipoCautelaAvulsa;
    }
    
    public function getCautelaAvulsaTemUsuario(){
    	
    	return $this->hasOne(User::className(), ["id" => "id"]);
    }
    
    public function getCautelaAvulsaTemBaixa(){
    	if($this->StatusCautelaAvulsa === CautelaAvulsa::getStatusConcluida())
    		return $this->hasOne(BaixaCautelaAvulsa::className(), ['idCautelaAvulsa'=>'idCautelaAvulsa']);
    	
    	return false;
    }

    public function getValidade(){
    	return $this->validadeCalculada;
    }
    
    public function getValidadeCalculada(){
    	if(!$this->isNewRecord){
    		$dataIni = date_create_from_format('d-m-Y', $this->dataInicial);
    		$dataFim = date_create_from_format('d-m-Y', $this->ValidadeCautela);
    
    		$strIni = $dataIni->format('Y-m-d');
    		$strFim = $dataFim->format('Y-m-d');
    
    		return floor( (strtotime($strFim)-strtotime($strIni))/86400 );
    	}
    	return 0;
    }    
    
    public function getBaixaReversivel(){
    	if( $this->StatusCautelaAvulsa === CautelaAvulsa::getStatusConcluida() )
    		return true;
    
    	return false;
    }
    
    public function getTelefoneFormatado(){
    	
    	return preg_replace('/(\d{2})(\d{5})(\d{3})/i', '(${1}) ${2}-${3}', $this->TelefoneResponsavel);
    }
    
    public function beforeSave($insert){
    	
    	$this->TelefoneResponsavel = str_replace(["(",")","-"," "],"",$this->TelefoneResponsavel);
    	
    	return true;
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
    
    /* Retorna o Status adequado, com base na Data de Devolucao Prevista e a data atual do sistema.
     */
    public function getAjustaStatus(){
    	//Status permitidos: 'Em atraso' e 'Em uso'
    	if(strtotime($this->ValidadeCautela)<(strtotime(date('d-m-Y')))){
    		return CautelaAvulsa::getStatusAtraso();
    	}elseif(strtotime($this->ValidadeCautela)>=(strtotime(date('d-m-Y')))){
    		return CautelaAvulsa::getStatusAberto();
    	}
    }
}
