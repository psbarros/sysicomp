<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_disciplina".
 *
 * @property string $codDisciplina
 * @property string $nome
 * @property integer $creditos
 * @property integer $cargaHoraria
 *
 * @property J17Aproveitamento[] $j17Aproveitamentos
 * @property J17Aproveitamento[] $j17Aproveitamentos0
 */
class Disciplina extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_disciplina';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codDisciplina', 'nome', 'creditos', 'nomeCurso','cargaHoraria','instituicao',
            'preRequisito','obrigatoria'], 'required'],
            [['creditos', 'cargaHoraria'], 'integer'],
            [['codDisciplina'], 'string', 'max' => 10],
            [['nome'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'codDisciplina' => 'Cod Disciplina',
            'nome' => 'Nome',
            'creditos' => 'Creditos',
            'nomeCurso' => 'Nome Curso',
            'cargaHoraria' => 'Carga Horaria',
            'instituicao' => 'Instituição',
            'preRequisito' => 'Pre-Requisito',
            'obrigatoria' => 'Obrigatória',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAproveitamentosDestino()
    {
        return $this->hasMany(Aproveitamento::className(), ['codDisciplinaDestinoFK' => 'codDisciplina']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAproveitamentosOrigem()
    {
        return $this->hasMany(Aproveitamento::className(), ['codDisciplinaOrigemFK' => 'codDisciplina']);
    }
    
    public function getObrigatoriaLabel()
    {
    	return ($this->obrigatoria === 1)?"SIM":"NÃO";
    }
    
    public function beforeSave($insert)
    {	
    	$this->nome = strtolower($this->nome);
    	$this->codDisciplina = strtolower($this->codDisciplina);
    	
    	return true;
    }
}
