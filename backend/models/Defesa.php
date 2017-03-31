<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;


class Defesa extends \yii\db\ActiveRecord
{

    public $nome_aluno;
    public $curso_aluno;
    public $membrosBancaInternos = [];
    public $membrosBancaExternos = [];
    public $membrosBancaSuplentes = [];
    public $auxiliarTipoDefesa;
    public $presidente;
    public $membrosBancaExternosPassagem = [];
    public $status_banca;
    public $anoPesq;
    public $tipoRelat;
    public $idProfessor;
    public $portariaID;
    public $portariaAno;

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
            [['resumo', 'banca_id', 'aluno_id', 'titulo', 'data', 'horario','resumo', 'local'], 'required'],
            [['portariaID', 'portariaAno'], 'required', 'on' => 'gerar_portaria'],
           [['membrosBancaInternos', 'membrosBancaExternos', 'membrosBancaSuplentes', 'presidente'] , 'required',


            'when' => function ($model) {
                     return $model->auxiliarTipoDefesa != 2;
                 },
            'whenClient' => "function (attribute, value) {

                return $('#membrosObrigatorios').val() == 1;
            }"],

           [ ['examinador', 'emailExaminador'] , 'required',
            'when' => function ($model) {
                     return $model->auxiliarTipoDefesa == 2;
                 },
            'whenClient' => "function (attribute, value) {

                return $('#membrosObrigatorios').val() == 0;
            }"],


            [['membrosBancaExternos', 'membrosBancaInternos', 'membrosBancaSuplentes', 'examinador', 'emailExaminador', 'auxiliarTipoDefesa','presidente','idProfessor' ], 'safe'],
            [['resumo', 'examinador', 'emailExaminador'], 'string'],
            [['emailExaminador'] , 'email'],
            [['numDefesa', 'reservas_id', 'banca_id', 'aluno_id', 'status_banca', 'portariaID', 'portariaAno'], 'integer'],
            [['titulo'], 'string', 'max' => 180],
            [['tipoDefesa'], 'string', 'max' => 2],
            [['data', 'horario'], 'string', 'max' => 10],
            [['conceito'], 'string', 'max' => 9],
            [['local'], 'string', 'max' => 100],
            [['previa'], 'string', 'max' => 45],
            [['anoPesq'], 'integer'],
            [['tipoRelat'], 'integer'],
            [['idProfessor'], 'integer'],
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
            'tipoDefesa' => 'Tipo de Defesa',
            'data' => 'Data',
            'conceito' => 'Conceito',
            'horario' => 'Horario',
            'local' => 'Local',
            'resumo' => 'Resumo',
            'numDefesa' => 'Número da Defesa',
            'examinador' => 'Examinador',
            'emailExaminador' => 'Email do Examinador',
            'reservas_id' => 'Reservas',
			'nome_aluno' => 'Nome do Aluno',
            'banca_id' => 'Banca',
            'aluno_id' => 'Aluno',
            'previa' => 'Previa',
            'portariaID' => 'Nº da Portaria',
            'portariaAno' => 'Ano da Portaria',
            'presidente' => 'Presidente da Banca'
        ];
    }

    public function uploadDocumento($previa)
    {
        if (isset($previa)) {
            $this->previa = "previa-".date('dmYHisu') . '.' . $previa->extension;
            $previa->saveAs('previa/' . $this->previa);
            return true;
        } else if(isset($this->previa)){
            return true;
        }else{
            return false;
        }
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

    public function getBanca(){
        return $this->hasOne(BancaControleDefesas::className(), ['id' => 'banca_id']);
    }

    public function getMembrosBanca(){
    	return $this->hasMany(Banca::className(), ['banca_id'=>'banca_id']);
    }

    public function getIdProfessor(){
    	return $this->idProfessor;
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

    public function salvaMembrosBanca(){
        $this->beforeDelete();

        $this->membrosBancaExternos = $this->membrosBancaExternos == "" ? array() : $this->membrosBancaExternos;
        $this->membrosBancaInternos = $this->membrosBancaInternos == "" ? array() : $this->membrosBancaInternos;
        $this->membrosBancaSuplentes = $this->membrosBancaSuplentes == "" ? array() : $this->membrosBancaSuplentes;

        $sql = "INSERT INTO j17_banca_has_membrosbanca (banca_id, membrosbanca_id, funcao) VALUES ('$this->banca_id', '".$this->presidente."', 'P');";
        Yii::$app->db->createCommand($sql)->execute();

        for ($i = 0; $i < count($this->membrosBancaExternos); $i++) {
            $sql = "INSERT INTO j17_banca_has_membrosbanca (banca_id, membrosbanca_id, funcao) VALUES ('$this->banca_id', '".$this->membrosBancaExternos[$i]."', 'E');";
            Yii::$app->db->createCommand($sql)->execute();
        }

        for ($i = 0; $i < count($this->membrosBancaInternos); $i++) {
            $sql = "INSERT INTO j17_banca_has_membrosbanca (banca_id, membrosbanca_id, funcao) VALUES ('$this->banca_id', '".$this->membrosBancaInternos[$i]."', 'I');";
            Yii::$app->db->createCommand($sql)->execute();
        }

        for ($i = 0; $i < count($this->membrosBancaSuplentes); $i++) {
            $sql = "INSERT INTO j17_banca_has_membrosbanca (banca_id, membrosbanca_id, funcao) VALUES ('$this->banca_id', '".$this->membrosBancaSuplentes[$i]."', 'S');";
            Yii::$app->db->createCommand($sql)->execute();
        }

        return true;
    }

    public function beforeDelete(){
        try{
            $sql = "DELETE FROM j17_banca_has_membrosbanca WHERE banca_id = '$this->banca_id'";
            Yii::$app->db->createCommand($sql)->execute();
        } catch (ErrorException $e){
             return false;
        }

        return true;
    }

    public function getConceitoDefesa(){

        return $this->conceito == null ? "<div style = \"color:red \"><b>Não Julgado</b></div>" : $this->conceito;
    }

    public function conceitoPendente($aluno_id){
        $conceitos = Defesa::find()->select("cd.status_banca as status_banca, j17_defesa.*")
        ->leftJoin("j17_banca_controledefesas as cd","cd.id = j17_defesa.banca_id")->Where(["j17_defesa.aluno_id" => $aluno_id , "conceito" => null, "status_banca" => 1])->count();

        if ($conceitos == 0){
            return false;
        }
        else{
            return true;
        }

    }

    public function getCoordenadorPPGI(){
        $user = User::find()->where(["coordenador"=>1, "administrador"=>0])->one();

        return $user->nome;

    }

}
