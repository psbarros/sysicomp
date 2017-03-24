<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_bancos".
 *
 * @property integer $id
 * @property string $codigo
 * @property string $nome
 */
class ContProjBancos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_bancos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['codigo'], 'string', 'max' => 5],
            [['nome'], 'string', 'max' => 70],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Código',
            'nome' => 'Nome',
        ];
    }
}
