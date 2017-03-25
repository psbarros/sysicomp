<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "j17_edital".
 *
 * @property string $numero
 * @property string $prazocarta
 * @property string $datainicio
 * @property string $datafim
 * @property string $documento
 */
class Edital extends \yii\db\ActiveRecord
{
    public $documentoFile;
    public $mestrado;
    public $doutorado;
    public $editalUpload;
    public $update=0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_edital';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numero', 'datainicio', 'datafim', 'cartarecomendacao'], 'required'],
            [['documentoFile'], 'required', 'when' => function($model){ return !isset($model->documento);}, 'whenClient' => "function (attribute, value) {
                return $('#form_upload').val() == 0;
            }"],
            [['vagas_mestrado'], 'required', 'when' => function($model){ return $model->curso == 1 || $model->curso == 3; },'whenClient' => "function (attribute, value) {
                return $('#form_mestrado').val() == 1;
            }"],
            [['vagas_doutorado'], 'required','when' => function($model){ return $model->curso == 2 || $model->curso == 3; },'whenClient' => "function (attribute, value) {
                return $('#form_doutorado').val() == 1;
            }"],
            [['doutorado', 'mestrado'], 'validaCurso', 'when' => function($model){ return $model->curso == 0; }],
            [['doutorado', 'mestrado'], 'integer'],
            [['numero', 'curso'], 'string'],
            ['numero', 'checkNumero'],
            ['numero', 'unique','message' => 'Já existe edital cadastrado com esse Número.'],
            [['numero'], 'unique', 'message' => 'Edital já criado'],
            [['vagas_mestrado','vagas_doutorado', 'cotas_mestrado', 'cotas_doutorado', 'cartaorientador'], 'integer', 'min' => 0],
            [['datainicio', 'datafim', 'documentoFile', 'documento'], 'safe'],
            [['datainicio'], 'validarDataInicio', 'when' => function($model){ return $model->update != 1; }],
            [['datafim'], 'validarDataFim', 'when' => function($model){ return $model->update != 1; }],
            [['documentoFile'], 'file', 'extensions' => 'pdf'],
            [['documento'], 'string', 'max' => 100],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'numero' => 'Nº edital',
            'datainicio' => 'Ínicio das inscrições',
            'datafim' => 'Término das inscrições',
            'documento' => 'Documento',
            'cartarecomendacao' => 'Carta de Recomendação',
			'cartaorientador' => 'Carta do Orientador',
            'curso' => 'Curso',
            'documentoFile' => 'Edital (PDF)',
        ];
    }

    public function validarDataInicio($attribute, $params){
        if (!$this->hasErrors()) {
            if (date("Y-m-d", strtotime(str_replace('/', '-', $this->datainicio))) < date('Y-m-d')) {
                $this->addError($attribute, 'Informe uma data igual ou posterior ao dia atual ('.date('d/m/Y').').');
            }
        }
    }

    public function validarDataFim($attribute, $params){
        if (!$this->hasErrors()) {
            if (date("Y-m-d", strtotime(str_replace('/', '-', $this->datainicio))) > date("Y-m-d", strtotime(str_replace('/', '-', $this->datafim)))) {
                $this->addError($attribute, 'Informe uma data igual ou posterior a '.date("d/m/Y", strtotime(str_replace('/', '-', $this->datainicio))).'.');
            }
        }
    }

    public function validaCurso($attribute, $params){
        $this->addError($attribute, "Escoha um dos cursos");
    }

    public function checkNumero($attribute, $params)
    {

        $conta = strlen($this->numero);

        if(strpos($this->numero, '_') == true){
            return $this->addError('numero', 'Você deve entrar com 8 números.');
        }
        else{
            return true;
        }
    }

    public function beforeSave($insert){

        if (parent::beforeSave($insert)) {
            $this->datainicio = date('Y-m-d', strtotime(str_replace('/', '-', $this->datainicio)));
            $this->datafim =  date('Y-m-d', strtotime(str_replace('/', '-', $this->datafim)));
            return true;
        } else {
            return false;
        }
    }

    public function afterFind(){
        if($this->curso == '3')
            $this->mestrado = $this->doutorado = 1;
        else if($this->curso == '1')
            $this->mestrado = 1;
        else if($this->curso == '2')
            $this->doutorado = 1;

        return true;
    }

    /*Relacionamento*/
    public function getCandidato()
    {
        return $this->hasMany(Candidato::className(), ['idEdital' => 'numero']);
    }
    //fim do relacionamento



    public function uploadDocumento($documentoFile)
    {
        if (isset($documentoFile)) {
            $this->documento = "edital-".date('dmYHisu') . '.' . $documentoFile->extension;
            $documentoFile->saveAs('editais/' . $this->documento);
            return true;
        } else if(isset($this->documento)){
            return true;
        }else{
            return false;
        }
    }

    public function getNomeCurso()
    {

        if ($this->curso == 1){
            return "Mestrado";
        }
        else if ($this->curso == 2){
            return "Doutorado";
        }
        else{
            return "Mestrado e Doutorado";
        }

    }

    public function getCartaRecomendacao($numero){



        $resultado = Edital::find()->select("cartarecomendacao")->where("numero ='".$numero."'")->one();
        return $resultado->cartarecomendacao;

    }

    public function getQuantidadeInscritos()
    {

        $results1 = Candidato::find()->where("idEdital = '".$this->numero."'")->count();

        return $results1;
    }

    public function getQuantidadeInscritosFinalizados()
    {

        $results2 = Candidato::find()->where("passoatual = 4 AND idEdital = '".$this->numero."'")->count();

        return $results2;
    }

        public function getVagasMestrado()
    {

        return 'REG: '.$this->vagas_mestrado.' / SUP: '.$this->cotas_mestrado;

   }

        public function getVagasDoutorado()
    {

        return 'REG: '.$this->vagas_doutorado.' / SUP: '.$this->cotas_doutorado;

   }

}
