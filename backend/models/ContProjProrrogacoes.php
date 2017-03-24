<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_prorrogacoes".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property string $data_fim_alterada
 * @property string $descricao
 *
 * @property J17ContprojProjetos $projeto
 */
class ContProjProrrogacoes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_prorrogacoes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'data_fim_alterada', 'descricao'], 'required'],
            [['projeto_id'], 'integer'],
            [['data_fim_alterada'], 'safe'],
            [['data_fim_alterada'],'validarData'],
            [['descricao'], 'string'],
            [['projeto_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContprojProjetos::className(), 'targetAttribute' => ['projeto_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'projeto_id' => 'Projeto ID',
            'data_fim_alterada' => 'Data Final',
            'descricao' => 'Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjeto()
    {
        return $this->hasOne(ContprojProjetos::className(), ['id' => 'projeto_id']);
    }


    public function validarData($attribute, $params)
    {

        $projeto = ContProjProjetos::find()->select("*")->where("id=$this->projeto_id")->one();
        $data_final = date('Y-m-d', strtotime($this->data_fim_alterada));
        if ($data_final < $projeto->data_fim_alterada) {
            $this->addError($attribute, 'Nova data final precisa ser maior que a data final registrada atualmente');
        }
    }


}