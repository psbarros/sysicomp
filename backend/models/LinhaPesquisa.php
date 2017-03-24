<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_linhaspesquisa".
 *
 * @property integer $id
 * @property string $nome
 * @property string $icone
 * @property string $sigla
 */
class LinhaPesquisa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_linhaspesquisa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sigla', 'nome', 'cor'], 'required'],
            [['nome'], 'string', 'max' => 60],
            [['sigla'], 'string', 'max' => 20],
            [['icone'], 'string', 'max' => 20],
            [['cor'], 'string', 'max' => 7],
            [['cor', 'sigla'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'icone' => 'Ícone',
            'sigla' => 'Sigla',
        ];
    }
}
