<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_aproveitamento".
 *
 * @property integer $id
 * @property string $codDisciplinaOrigemFK
 * @property string $codDisciplinaDestinoFK
 * @property double $nota
 * @property double $frequencia
 * @property string $situacao
 * @property integer $idAluno
 *
 * @property J17Disciplina $codDisciplinaDestinoFK0
 * @property J17Disciplina $codDisciplinaOrigemFK0
 * @property J17Aluno $idAlunoFK0
 */
class Aproveitamento extends \yii\db\ActiveRecord
{
	public $nomeAluno;
	public $flagFromAluno;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_aproveitamento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codDisciplinaOrigemFK', 'codDisciplinaDestinoFK', 'nota', 'frequencia', 'situacao', 'idAluno'], 'required'],
            [['nota', 'frequencia'], 'number'],
            [['idAluno'], 'integer'],
        	[['nomeAluno'], 'string', 'max'=>60],
            [['codDisciplinaOrigemFK', 'codDisciplinaDestinoFK', 'situacao'], 'string', 'max' => 10],
            [['codDisciplinaDestinoFK'], 'exist', 'skipOnError' => true, 'targetClass' => Disciplina::className(), 'targetAttribute' => ['codDisciplinaDestinoFK' => 'codDisciplina']],
            [['codDisciplinaOrigemFK'], 'exist', 'skipOnError' => true, 'targetClass' => Disciplina::className(), 'targetAttribute' => ['codDisciplinaOrigemFK' => 'codDisciplina']],
        	[['nomeAluno'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Aproveit.',
            'codDisciplinaOrigemFK' => 'Cód. Disciplina Origem',
            'codDisciplinaDestinoFK' => 'Cód. Disciplina Destino',
            'nota' => 'Nota',
            'frequencia' => 'Frequência',
            'situacao' => 'Situação',
            'idAluno' => 'ID Aluno',
        	'nomeAluno'=>'Aluno',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodDisciplinaDestinoFK0()
    {
        return $this->hasOne(Disciplina::className(), ['codDisciplina' => 'codDisciplinaDestinoFK']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodDisciplinaOrigemFK0()
    {
        return $this->hasOne(Disciplina::className(), ['codDisciplina' => 'codDisciplinaOrigemFK']);
    }

    public function getIdAlunoFK0(){
    	return $this->hasOne(Aluno::className(), ['id' => 'idAluno']);
    }

    /**
     * Indicates whether a pair (DisciplinaOrigem, DisciplinaDestino) already exists for ID idAluno.
     * Must be used before getAproveitamentoDisciplinaUsada(), becouse it has logical precedence for either one.
     *
     * @author Marcelo Brasil <malbf@icomp.ufam.edu.br>
     *
     * @return Null or Integer: models\Aproveitamento->codAproveitamento
     */
    public static function getAproveitamentoOrigemDestinoExiste($codOrigem, $codDestino, $idAluno)
    {
    	$idAproveitamento = Aproveitamento::findOne(['codDisciplinaOrigemFK'=>strtolower($codOrigem),
    			'codDisciplinaDestinoFK'=>strtolower($codDestino),
    			'idAluno'=>$idAluno]);
    	if($idAproveitamento !== Null)
    			$idAproveitamento = $idAproveitamento->id;
    	return $idAproveitamento;
    }

    /**
     * Indicates whether a codDisciplina is already used for ID idAluno in some pair (DisciplinaOrigem, DisciplinaDestino).
     * Must be used after getAproveitamentoOrigemDestinoExiste(), becouse the last one has logical precedence over the first one.
     *
     * @author Marcelo Brasil <malbf@icomp.ufam.edu.br>
     *
     * @return Null or Integer: models\Aproveitamento->codAproveitamento
     */
    public static function getAproveitamentoDisciplinaUsada($codDisciplina, $idAluno)
    {
    	$idAproveitamento = Aproveitamento::findOne(['codDisciplinaOrigemFK'=>strtolower($codDisciplina),
    			'idAluno'=>$idAluno]);
    	if($idAproveitamento === Null)
	    	$idAproveitamento = Aproveitamento::findOne(['codDisciplinaDestinoFK'=>strtolower($codDisciplina),
	    			'idAluno'=>$idAluno]);
	    if($idAproveitamento !== Null)
	    	$idAproveitamento = $idAproveitamento->id;

    	return $idAproveitamento;
    }

    public function beforeSave($insert)
    {		
        if (parent::beforeSave($insert)) {
			$this->codDisciplinaDestinoFK = strtolower($this->codDisciplinaDestinoFK);
	    	$this->codDisciplinaOrigemFK = strtolower($this->codDisciplinaOrigemFK);
            return true;
        } else {
            return false;
        }
    }

}
