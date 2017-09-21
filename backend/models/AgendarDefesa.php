<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_defesa".
 *
 * @property int $idDefesa
 * @property string $titulo
 * @property string $tipoDefesa
 * @property string $data
 * @property string $conceito
 * @property string $horario
 * @property string $local
 * @property string $resumo
 * @property int $numDefesa
 * @property string $examinador
 * @property string $emailExaminador
 * @property int $reservas_id
 * @property int $banca_id
 * @property int $aluno_id
 * @property string $previa
 */
class AgendarDefesa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_defesa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resumo', 'banca_id', 'aluno_id'], 'required'],
            [['resumo', 'examinador', 'emailExaminador'], 'string'],
            [['numDefesa', 'reservas_id', 'banca_id', 'aluno_id'], 'integer'],
            [['titulo'], 'string', 'max' => 180],
            [['tipoDefesa'], 'string', 'max' => 2],
            [['data', 'horario'], 'string', 'max' => 10],
            [['conceito'], 'string', 'max' => 9],
            [['local'], 'string', 'max' => 100],
            [['previa'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idDefesa' => 'Id Defesa',
            'titulo' => 'Titulo',
            'tipoDefesa' => 'Tipo  de Defesa',
            'data' => 'Data',
            'conceito' => 'Conceito',
            'horario' => 'Horario',
            'local' => 'Local',
            'resumo' => 'Resumo',
            'numDefesa' => 'Numero da Defesa',
            'examinador' => 'Examinador',
            'emailExaminador' => 'Email Examinador',
            'reservas_id' => 'Reservas ID',
            'banca_id' => 'Banca ID',
            'aluno_id' => 'Aluno ID',
            'previa' => 'Previa',
        ];
    }

    public function getModelAluno(){
        return Aluno::find()->where("id =".$this->aluno_id)->one();
    }

    public function getNome(){
        #$aluno = $this->getModelAluno();
        $aluno= Aluno::find()->where("id =".$this->aluno_id)->all();
        $nome= null;
        foreach($aluno as $key => $a){
            $nome= $a->nome;
        }
        #$aluno= Aluno::find()->one();
        return $nome;
    }


    public function getCurso(){
        #$aluno = $this->getModelAluno();
        $aluno= Aluno::find()->where("id =".$this->aluno_id)->all();
        $curso= null;
        foreach($aluno as $key => $a){
            $curso= $a->curso;
        }
        #$aluno= Aluno::find()->one();
        return $curso == 1 ? "Mestrado" : "Doutorado" ;
    }

    public function getTipoDefesa(){

        if ($this->tipoDefesa == "Q1"){
            $defesa = "Qualificação 1";
        }
        else if ($this->tipoDefesa == "Q2"){
            $defesa = "Qualificação 2";
        }
        else if ($this->tipoDefesa == "T"){
            $defesa = "Tese";
        }
        else if ($this->tipoDefesa == "D"){
            $defesa = "Dissertação";
        }

        return $defesa;
    }

}
