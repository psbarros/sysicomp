<?php

namespace app\models;

use Yii;
use yiibr\brvalidator\CpfValidator;

/**
 * This is the model class for table "j17_membrosbanca".
 *
 * @property integer $id
 * @property string $nome
 * @property string $email
 * @property string $filiacao
 * @property string $telefone
 * @property string $cpf
 * @property string $dataCadastro
 * @property integer $idProfessor
 */
class Membrosbanca extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_membrosbanca';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'email', 'filiacao', 'telefone', 'cpf'], 'required'],
            [['dataCadastro'], 'safe'],
            [['idProfessor'], 'integer'],
            [['nome', 'email', 'filiacao'], 'string', 'max' => 100],
            [['telefone'], 'string', 'max' => 20],
            [['cpf'], CpfValidator::className(), 'message' => 'CPF Inválido'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'email' => 'E-mail',
            'filiacao' => 'Filiacão',
            'telefone' => 'Telefone',
            'cpf' => 'CPF',
            'dataCadastro' => 'Data de Cadastro',
            'idProfessor' => 'Id Professor',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBancas()
    {
        return $this->hasMany(Banca::className(), ['membrosbanca_id' => 'id']);
    }
    /*
     * $curso = [Mestrado | Doutorado]
     * $tipo  = [Q1, Q2, T, D]; Mestrado=>Q1,D; Doutorado=>Q1,Q2,T;
     */
    public function getBancasbytipo($tipo, $curso, $ano=null)
    {	
    	$bancasSel = null;
    	foreach ($this->bancas as $banca){    		
    		if($banca->defesa!=null && (strtolower($banca->defesa->curso) == strtolower($curso) ) && strtolower($banca->defesa->tipoDefesa) == strtolower($tipo))
    			if($ano==null || date( 'Y', strtotime( $banca->defesa->data )) == $ano)
	    		 $bancasSel[] = $banca;
    	}
    	return $bancasSel;
    }
    
    public function getBancasbytipoEnum($tipo, $curso, $ano=null)
    {	$strenum = "";
    	$bancas = $this->getBancasbytipo($tipo, $curso, $ano);
    	if(count($bancas)>0){
    		$strenum = "<ol start=1 style=\"padding-left: 120px;\">";
    		foreach($bancas as $banca){
    			$strenum .= "<li style=\"margin-bottom:3px;\">".$banca->defesa->nome."; defesa em ".date('d', strtotime($banca->defesa->data) )."/".date('m', strtotime($banca->defesa->data) )."/".date('Y', strtotime($banca->defesa->data) ).".</li>";
    		}
    		$strenum .= "</ol>";
    	}
    	
    	return $strenum;
    }
    
    public static function membroIdByUserId($userId){
    	$usuario = User::findOne($userId);
    	//Usuario.ID TO Membro.idProfessor
    	$membro = Membrosbanca::findOne(['idProfessor'=>$usuario->id]);
    	//Usuario.CPF TO Membro.CPF
    	if($membro == null)
    		$membro = Membrosbanca::findOne(['cpf'=>str_replace([".","-"],"",$usuario->username)]);
    	//Usuario.Nome To Membro.Nome
    	if($membro == null)
    		$membro = Membrosbanca::find()->where(['like','nome',$usuario->nome])->one();
    	
    	if($membro == null)
    		return null;
    	else
    		return $membro->id;
    }
}
