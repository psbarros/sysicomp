<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_receitas".
 *
 * @property integer $id
 * @property integer $rubricasdeprojetos_id
 * @property string $descricao
 * @property double $valor_receita
 * @property string $data
 * @property string $ordem_bancaria
 */
class ContProjReceitas extends \yii\db\ActiveRecord
{

    public $nomeRubrica;
    public $codigo;
    public $tipo;
    public $ordem;
    public $total;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_receitas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rubricasdeprojetos_id', 'descricao', 'valor_receita', 'data','ordem_bancaria'], 'required'],
            [['rubricasdeprojetos_id'], 'integer'],
            [['valor_receita'], 'number'],
            [['valor_receita'], 'validar_receita'],
            [['data','tipo'], 'safe'],
            [['descricao'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo'=>'Tipo',
            'rubricasdeprojetos_id' => 'Rubricas',
            'descricao' => 'Descricao',
            'valor_receita' => 'Valor Receita',
            'data' => 'Data',
            'nomeRubrica' => 'Item de Despêndio',
            'ordem_bancaria' => 'Ordem Bancária',
        ];
    }

    public function validar_receita($attribute,$params){

        /*$rubrica = ContProjRubricasdeProjetos::find()->select(["*"])
           ->where("j17_contproj_rubricasdeprojetos.id=$this->rubricasdeprojetos_id")->one();
        $receitas = ContProjReceitas::find()->select(["*"])
            ->where("rubricasdeprojetos_id=$this->rubricasdeprojetos_id")->sum("valor_receita");
        $this->addError($attribute, "$receitas");
        if($this->valor_receita > $rubrica->valor_previsto){
            $permitido = number_format ( $permitido , 2 );
            $messagem = "limite atingido maximo ainda permitido é $permitido";
            $this->addError($attribute, $messagem);
        }*/
    }
}
