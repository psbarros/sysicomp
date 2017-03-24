<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_transferenciassaldorubricas".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property integer $rubrica_origem
 * @property integer $rubrica_destino
 * @property double $valor
 * @property string $data
 * @property string $autorizacao
 */
class ContProjTransferenciasSaldoRubricas extends \yii\db\ActiveRecord
{

    public $nomeprojeto;
    public $nomeRubricaOrigem;
    public $nomeRubricaDestino;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_transferenciassaldorubricas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'rubrica_origem', 'rubrica_destino', 'valor', 'data', 'autorizacao'], 'required'],
            [['projeto_id', 'rubrica_origem', 'rubrica_destino'], 'integer'],
            [['valor'], 'number'],
            [['valor'], 'validarValor'],
            [['data'], 'safe'],
            [['data'], 'validarData'],
            [['rubrica_destino'], 'validarRubrica'],
            [['autorizacao'], 'string', 'max' => 100],
        ];
    }


    function trunc()
    {
        return substr($this->nomeRubricaDestino, 0, 14) . '...';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'projeto_id' => 'Projeto ID',
            'rubrica_origem' => 'Rubrica Origem',
            'rubrica_destino' => 'Rubrica Destino',
            'valor' => 'Valor',
            'data' => 'Data',
            'autorizacao' => 'Autorizacao',
            'nomeRubricaOrigem'=>'Rubrica de Origem',
            'nomeRubricaDestino'=>'Rubrica de Destino',
        ];
    }

    public function validarData($attribute, $params)
    {
        $data=ContProjProjetos::find()->select("data_inicio")->where("id=$this->projeto_id")->one();

        //echo '<script language="javascript">';
        //echo 'alert("'.$data->data_inicio.'")';
        //echo '</script>';

        if($this->data < $data->data_inicio){
            $this->addError($attribute, "A data da transferência precisa ser posterior a data de inicio do projeto");
        }

    }

    public function validarRubrica($attribute, $params)
    {
        //$data=ContProjProjetos::find()->select("data_inicio")->where("id=$this->projeto_id")->one();

        if($this->rubrica_origem === $this->rubrica_destino){
            $this->addError($attribute, "A rubrica de destino tem que ser diferente da rubrica de origem");
        }

    }

    public function validarValor($attribute, $params)
    {
        $valor=ContProjRubricasdeProjetos::find()->select("valor_disponivel")->where("id=$this->rubrica_origem")->one();

        if($this->valor > $valor->valor_disponivel){
            $this->addError($attribute, "valor a ser transferido não pode exceder o limite disponível na rubrica de origem");
        }
    }

}
