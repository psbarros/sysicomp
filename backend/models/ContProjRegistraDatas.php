<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_registradatas".
 *
 * @property integer $id
 * @property string $evento
 * @property string $data
 * @property integer $projeto_id
 * @property string $observacao
 * @property string $tipo
 */
class ContProjRegistraDatas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_registradatas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['evento', 'data', 'projeto_id', 'observacao', 'tipo'], 'required'],
            [['data'], 'safe'],
            [['projeto_id'], 'integer'],
            [['evento'], 'string', 'max' => 150],
            [['observacao'], 'string', 'max' => 200],
            [['tipo'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'evento' => 'Evento',
            'data' => 'Data',
            'projeto_id' => 'Projeto',
            'observacao' => 'Observacao',
            'tipo' => 'Tipo',
        ];
    }
}
