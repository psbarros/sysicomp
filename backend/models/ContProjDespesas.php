<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_despesas".
 *
 * @property integer $id
 * @property integer $quantidade
 * @property integer $rubricasdeprojetos_id
 * @property string $descricao
 * @property double $valor_despesa
 * @property double $valor_unitario
 * @property string $tipo_pessoa
 * @property string $data_emissao
 * @property string $ident_nf
 * @property string $nf
 * @property string $ident_cheque
 * @property string $data_emissao_cheque
 * @property double $valor_cheque
 * @property string $favorecido
 * @property string $cnpj_cpf
 * @property string $comprovante
 */
class ContProjDespesas extends \yii\db\ActiveRecord
{
    public $nomeRubrica;
    public $codigo;
    public $tipo;
    public $comprovanteArquivo;
    public $data;
    public $total;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_despesas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rubricasdeprojetos_id', 'descricao', 'valor_unitario', 'quantidade','tipo_pessoa', 'data_emissao', 'ident_nf', 'valor_cheque'], 'required'],
            [['rubricasdeprojetos_id','quantidade'], 'integer'],
            [['quantidade'], 'integer', 'min'=>1],
            [['valor_despesa', 'valor_cheque','valor_unitario'], 'number'],
            [['valor_despesa'],'validar_despesa'],
            [['valor_unitario'],'validar_valor'],
            [['valor_cheque'],'validar_cheque'],
            [['data_emissao', 'data_emissao_cheque'], 'safe'],
            [['descricao', 'favorecido'], 'string', 'max' => 150],
            [['tipo_pessoa'], 'string', 'max' => 11],
            [['ident_nf', 'nf'], 'string', 'max' => 100],
            [['ident_cheque'], 'string', 'max' => 70],
            [['cnpj_cpf'], 'string', 'max' => 20],
            [['comprovante'], 'string', 'max' => 200],
            [['comprovanteArquivo'],'safe'],
            [['comprovanteArquivo'],'file','extensions'=>'pdf'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rubricasdeprojetos_id' => 'Rubricas',
            'descricao' => 'Descrição',
            'valor_despesa' => 'Valor da Despesa',
            'tipo_pessoa' => 'Tipo  de pessoa',
            'data_emissao' => 'Data Emissao',
            'ident_nf' => 'Tipo de documento fiscal',
            'nf' => 'Nota Fiscal',
            'ident_cheque' => 'Identidade do cheque',
            'data_emissao_cheque' => 'Data de emissao do cheque',
            'valor_cheque' => 'Valor do cheque',
            'favorecido' => 'Favorecido',
            'cnpj_cpf' => 'CNPJ/CPF',
            'comprovante' => 'Comprovante',
            'nomeRubrica' => 'Item de Despêndio',
            'valor_unitario'=> 'Valor Unitário',
            'quantidade' => 'Quantidade'
        ];
    }

    public function validar_despesa($attribute,$params){
        $rubrica = ContProjRubricasdeProjetos::find()->select(["*"])
            ->where("j17_contproj_rubricasdeprojetos.id=$this->rubricasdeprojetos_id")->one();
        $projeto = ContProjProjetos::find()->select(["*"])
            ->where("id=$rubrica->projeto_id")->one();
        if($this->valor_despesa > $projeto->saldo ){
            $messagem = "Despesa não pode exceder o saldo disponivel para o projeto";
            $this->addError($attribute, $messagem);
        }
    }

    public function validar_valor($attribute,$params)
    {
        $valor = (float)$attribute;
        if($this->valor_unitario <= 0.0 ){
            $messagem = "O valor precisa ser maior que zero";
            $this->addError($attribute, $messagem);
        }
    }

    public function validar_cheque($attribute,$params)
    {
        $valor = (float)$attribute;
        if($this->valor_cheque <= 0.0 ){
            $messagem = "O valor precisa ser maior que zero";
            $this->addError($attribute, $messagem);
        }
    }

}
