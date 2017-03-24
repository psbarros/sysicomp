<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_projetos".
 *
 * @property integer $id
 * @property string $nomeprojeto
 * @property double $orcamento
 * @property double $saldo
 * @property string $data_inicio
 * @property string $data_fim
 * @property string $data_fim_alterada
 * @property integer $coordenador_id
 * @property integer $agencia_id
 * @property integer $banco_id
 * @property string $agencia
 * @property string $conta
 * @property string $edital
 * @property string $proposta
 * @property string $status
 */
class ContProjProjetos extends \yii\db\ActiveRecord
{
    public $coordenador;
    public $editalArquivo;
    public $propostaArquivo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_projetos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nomeprojeto', 'orcamento', 'saldo' ,'coordenador_id', 'agencia_id', 'banco_id'], 'required'],
            [['orcamento', 'saldo'], 'number'],
            [['data_inicio', 'data_fim', 'data_fim_alterada'], 'safe'],
            ['data_fim','valid_number'],
            [['coordenador_id', 'agencia_id', 'banco_id'], 'integer'],
            [['nomeprojeto', 'proposta'], 'string', 'max' => 200],
            [['agencia', 'conta', 'status'], 'string', 'max' => 11],
            [['edital'], 'string', 'max' => 150],
            [['nomeprojeto'], 'unique'],
            [['nomeprojeto'], 'unique'],
            [['nomeprojeto'], 'unique'],
            [['nomeprojeto'], 'unique'],
            [['editalArquivo','propostaArquivo'],'safe'],
            [['editalArquivo','propostaArquivo'],'file','extensions'=>'pdf'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomeprojeto' => 'Título do projeto',
            'orcamento' => 'Orçamento',
            'saldo' => 'Saldo',
            'data_inicio' => 'Data de Inicio',
            'data_fim' => 'Data Final',
            'data_fim_alterada' => 'Data Final Alterada',
            'coordenador_id' => 'Coordenador',
            'agencia_id' => 'Agencia de Fomento',
            'banco_id' => 'Banco',
            'agencia' => 'Agencia',
            'conta' => 'Conta',
            'editalArquivo' => 'Edital',
            'propostaArquivo' => 'Proposta',
            'edital' => 'Edital',
            'proposta' => 'Proposta',
            'status' => 'Status',
        ];
    }

    public function valid_number($attribute,$params){

        if( time($this->data_fim) < time($this->data_inicio)){
            $this->addError($attribute, 'Data final do projeto precisa ser maior que data inicial!');
        }
    }

    public function dataMaior(){
        if($this->data_fim_alterada>$this->data_fim) {
            return $this->data_fim_alterada;
        }else{
            return $this->data_fim;
        }
    }
}
