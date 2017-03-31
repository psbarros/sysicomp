<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "defesas_tipo".
 *
 * @property integer $id
 * @property string $sigla
 * @property string $nome
 * @property integer $status
 */
class DefesasTipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'defesas_tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sigla', 'nome'], 'required'],
            [['id', 'status'], 'integer'],
            [['sigla'], 'string', 'max' => 20],
            [['nome'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sigla' => 'Sigla',
            'nome' => 'Nome',
            'status' => 'Status',
        ];
    }
}
