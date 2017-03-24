<?php

namespace app\models;

use Yii;


class Afastamentos extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_afastamentos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
			[['local', 'tipo', 'datasaida', 'dataretorno', 'justificativa'], 'required'],
            [['tipo'], 'integer'],
            [['datasaida', 'dataretorno'], 'safe'],
            [['local'], 'string', 'max' => 40],
			[['justificativa', 'reposicao'], 'string'],
			[['dataretorno'], 'validarDataRetorno']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idusuario' => 'ID do Usuário',
            'datasaida' => 'Data de Saída',
            'dataretorno' => 'Data de Retorno',
            'dataenvio' => 'Data de Envio do Pedido',
            'nomeusuario' => 'Nome do Usuário',
            'tipo' => 'Tipo de Viagem',
            'local' => 'Local da Viagem',
            'justificativa' => 'Justificativa',
            'reposicao' => 'Plano de Reposição das Aulas',
        ];
    }

    public function validarDataRetorno($attribute, $params){
        if (!$this->hasErrors()) {
            if (date("Y-m-d", strtotime($this->dataretorno)) < date("Y-m-d", strtotime($this->datasaida))) {
                $this->addError($attribute, 'Informe uma data igual ou posterior a '.date("d-m-Y", strtotime($this->datasaida)));
            }
        }
    }

	public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            $this->datasaida = date('Y-m-d', strtotime($this->datasaida));
            $this->dataretorno =  date('Y-m-d', strtotime($this->dataretorno));
            return true;
        } else {
            return false;
        }
    }

}
