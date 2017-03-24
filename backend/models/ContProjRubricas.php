<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_rubricas".
 *
 * @property integer $id
 * @property string $codigo
 * @property string $nome
 * @property string $tipo
 */
class ContProjRubricas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_rubricas';
    }

	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo', 'nome', 'tipo'], 'required'],
            [['codigo'], 'string', 'max' => 11],
            [['nome'], 'string', 'max' => 150],
            [['tipo'], 'string', 'max' => 20],
            [['codigo'], 'unique'],
            [['nome'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Código da Rubrica',
            'nome' => 'Nome',
            'tipo' => 'Tipo',
        ];
    }
}
