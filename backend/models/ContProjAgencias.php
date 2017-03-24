<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_agencias".
 *
 * @property integer $id
 * @property string $nome
 * @property string $sigla
 */
class ContProjAgencias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_agencias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'sigla'], 'required'],
            [['nome'], 'string', 'max' => 70],
            [['sigla'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'sigla' => 'Sigla',
        ];
    }
}
