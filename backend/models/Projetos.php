<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_projetos".
 *
 * @property integer $id
 * @property integer $idProfessor
 * @property string $titulo
 * @property string $descricao
 * @property integer $inicio
 * @property integer $fim
 * @property string $papel
 * @property string $financiadores
 * @property string $integrantes
 */
class Projetos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_projetos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idProfessor', 'titulo', 'descricao', 'inicio', 'papel', 'financiadores', 'integrantes'], 'required'],
            [['idProfessor', 'inicio', 'fim'], 'integer'],
            [['descricao'], 'string'],
            [['titulo'], 'string', 'max' => 300],
            [['papel'], 'string', 'max' => 15],
            [['financiadores', 'integrantes'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idProfessor' => 'Id Professor',
            'titulo' => 'Titulo',
            'descricao' => 'Descricao',
            'inicio' => 'Inicio',
            'fim' => 'Fim',
            'papel' => 'Papel',
            'financiadores' => 'Financiadores',
            'integrantes' => 'Integrantes',
        ];
    }
}
