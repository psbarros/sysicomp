<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_rubricasdeprojetos".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property integer $rubrica_id
 * @property string $descricao
 * @property double $valor_total
 * @property double $valor_gasto
 * @property double $valor_disponivel
 */
class ContProjRubricasdeProjetos extends \yii\db\ActiveRecord
{

    public $nomeprojeto;
    public $nomerubrica;
    public $coordenador;
    public $data_fim;
    public $agencia;
    public $projeto;
    public $ordem_bancaria;
	public $codigo;
    public $tipo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_rubricasdeprojetos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'rubrica_id', 'descricao', 'valor_total', 'valor_gasto'], 'required'],
            [['projeto_id', 'rubrica_id'], 'integer'],
            [['valor_total', 'valor_gasto', 'valor_disponivel'], 'number'],
            [['descricao','ordem_bancaria'], 'string', 'max' => 200],
            [['valor_disponivel'],'validar_ordem'],
            ['valor_disponivel','validar_valor'],
            ['valor_total','checarOrcamento'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomeprojeto' => 'Projeto',
            'nomerubrica' => 'Rubrica',
            'projeto_id' => 'Projeto',
            'rubrica_id' => 'Rubrica',
            'descricao' => 'Descrição',
            'valor_total' => 'Valor Previsto',
            'valor_gasto' => 'Valor Gasto',
            'valor_disponivel' => 'Saldo',
            'ordem_bancaria' => 'Ordem Bancária',
        ];
    }

    public function getRubrica()
    {
        return $this->hasOne(Rubrica::className(), ['id' => 'rubrica_id']);
    }

	public function validar_valor($attribute,$params){
        if($this->$attribute > $this->valor_total ){
            $this->addError($attribute, 'O Saldo inicial não pode ser maior que o valor previsto para a rubrica');
        }
    }

    public function validar_ordem($attribute){
        //$this->addError($attribute, "OB: $this->ordem_bancaria");
        if($this->valor_disponivel>0 && $this->ordem_bancaria==null){
            $this->addError($attribute, "'Ordem Bancária' é um campo obrigatório quando o Saldo é maior que zero");
        }
    }

    public function checarOrcamento($attribute,$params){
        $soma = 0.00;
        $projeto = \backend\models\ContProjProjetos::find()->where("id=$this->projeto_id")->sum("orcamento");
        $valorRubricas =  \backend\models\ContProjRubricasdeProjetos::find()->where("projeto_id=$this->projeto_id")->all();
        foreach($valorRubricas as $valor){
            if($valor->id != $this->id){
                $soma = $soma + $valor->valor_total;
            }
        }
        $permitido = $projeto - $soma;
        if( $permitido >0){
            $permitido = number_format ( $permitido , 2 );
            $messagem = "O Valor ainda disponivél para cadastro é de R$ $permitido!";
        }else{
            $messagem = "O orçamento do projeto foi atingido, não é possível cadastrar novas rubricas!";
        }
        $total = $this->valor_total + $soma;
        if ($total > $projeto) {
            $this->addError($attribute, $messagem);
        }
    }

}
