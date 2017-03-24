<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_banca_controledefesas".
 *
 * @property integer $id
 * @property integer $status_banca
 * @property string $justificativa
 *
 * @property J17Defesa[] $j17Defesas
 */
class BancaControledefesas extends \yii\db\ActiveRecord
{
    public $aluno_nome;
	public $cursoAluno;
	public $linhaSigla;
	public $titulo;
	public $local;
	public $horario;
	public $data;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_banca_controledefesas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_banca'], 'integer'],
            [['justificativa'], 'required'],
            [['justificativa'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_banca' => 'Status Banca',
            'justificativa' => 'Justificativa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJ17Defesas()
    {
        return $this->hasMany(J17Defesa::className(), ['banca_id' => 'id']);
    }
}
