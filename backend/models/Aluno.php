<?php

namespace app\models;

use Yii;
use yiibr\brvalidator\CpfValidator;

use yii\db\Query;
use yii\data\SqlDataProvider;
use app\models\Trancamento;
use yii\db\ActiveQuery;
use yii\db\Connection;

class Aluno extends \yii\db\ActiveRecord
{
    public $siglaLinhaPesquisa;
    public $corLinhaPesquisa;
    public $nomeOrientador;
    public $icone;
    public $username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_aluno';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'email', 'curso', 'cpf', 'cep', 'endereco', 'datanascimento', 'sexo', 'uf', 'cidade', 'bairro', 'telresidencial', 'regime', 'matricula', 'orientador', 'dataingresso', 'curso', 'area'], 'required'],
            [['financiadorbolsa', 'dataimplementacaobolsa'], 'required', 'when' => function ($model) { return $model->bolsista; }, 'whenClient' => "function (attribute, value) {
                    return $('#form_bolsista').val() == '1';
                }"],
            [['area', 'curso', 'regime', 'status', 'egressograd', 'orientador'], 'integer'],
            [['nome'], 'string', 'max' => 60],
            [['email'],'email'],
            [['cidade'], 'string', 'max' => 40],
            [['senha'], 'string', 'max' => 255],
            [['matricula'], 'string', 'max' => 15],
            [['endereco'], 'string', 'max' => 160],
            [['bairro'], 'string', 'max' => 50],
            [['uf'], 'string', 'max' => 5],
            [['cep', 'conceitoExameProf'], 'string', 'max' => 9],
            [['datanascimento', 'dataExameProf'], 'string', 'max' => 10],
            [['sexo'], 'string', 'max' => 1],
            [['cpf'], CpfValidator::className(), 'message' => 'CPF Inválido'],
            [['telresidencial', 'telcelular'], 'string', 'max' => 18],
            [['bolsista'], 'string', 'max' => 3],
            [['financiadorbolsa'], 'string', 'max' => 45],
            [['idiomaExameProf'], 'string', 'max' => 20],
            [['cursograd', 'instituicaograd'], 'string', 'max' => 100],
            [['sede'], 'string', 'max' => 2],
            [['dataingresso', 'dataimplementacaobolsa'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            
            'nome' => 'Nome',
            'email' => 'Email',
            'senha' => 'Senha',
            'matricula' => 'Matrícula',
			'name' => 'Nome do Aluno',
            'area' => 'Linha de Pesquisa',
            'curso' => 'Curso',
            'endereco' => 'Endereço',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'uf' => 'UF',
            'cep' => 'CEP',
            'datanascimento' => 'Data de Nascimento',
            'sexo' => 'Sexo',
            'cpf' => 'CPF',
            'rg' => 'RG',
            'telresidencial' => 'Telefone Principal',
            'telcelular' => 'Telefone Alternativo',
            'regime' => 'Regime',
            'bolsista' => 'Bolsista',
            'status' => 'Status',
            'dataingresso' => 'Data de Ingresso',
            'idiomaExameProf' => 'Idioma do Exame de Proficiência',
            'conceitoExameProf' => 'Conceito do Exame de Proficiência',
            'dataExameProf' => 'Data do Exame de Proficiência',
            'cursograd' => 'Curso da Graduação',
            'instituicaograd' => 'Instituicão da Graduação',
            'egressograd' => 'Término da Graduação',
            'orientador' => 'Orientador',
            'anoconclusao' => 'Ano de Conclusão',
            'sede' => 'Sede',
            'financiadorbolsa' => 'Financiador da Bolsa',
            'dataimplementacaobolsa' => 'Início da Vigência',
            'orientador1.nome' => 'Orientador',
        ];
    }

    public function beforeSave($insert){

        if (parent::beforeSave($insert)) {
            if($this->dataingresso) $this->dataingresso = date('Y-m-d', strtotime($this->dataingresso));
    		if($this->datanascimento) $this->datanascimento = date('Y-m-d', strtotime($this->datanascimento));
            if($this->dataExameProf) $this->dataExameProf =  date('Y-m-d', strtotime($this->dataExameProf));
    		if($this->dataimplementacaobolsa) $this->dataimplementacaobolsa =  date('Y-m-d', strtotime($this->dataimplementacaobolsa));
            return true;
        } else {
            return false;
        }

    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getlinhaPesquisa()
    {
        return $this->hasOne(LinhaPesquisa::className(), ['id' => 'area']);
    }

    public function getOrientador1()
    {
        return $this->hasOne(User::className(), ['id' => 'orientador']);
    }

    public function orientados($idusuario){
       $alunos = Aluno::find()->where(["orientador" => $idusuario])->all();
       return $alunos;
    }

    /**
     * Gets the stop out's related to the student
     * Returns an array with all student-related stop out's
     *
     * @author Pedro Frota <pvmf@icomp.ufam.edu.br>
     *
     * @return \yii\db\ActiveQuery
     */

    public function getTrancamentos() {
        return $this->hasMany(Trancamentos::className(), ['idAluno' => 'id']);
    }

    

    public function getDiasParaFormar() {

        if($this->curso == 1){  //Mestrado
            $diasParaFormar= 730;
        }else{                  //Doutorado
            $diasParaFormar= 1460;
        }
        $dataIngresso= strtotime($this->dataingresso);
        $dataConclusao= strtotime($this->anoconclusao);
        $dataAtual= strtotime(date("Y-m-d"));

        if($dataConclusao == null){
            $diasPassados= (int)floor(($dataAtual - $dataIngresso) / (60 * 60 * 24));
        }else{
            $diasPassados= (int)floor(($dataConclusao - $dataIngresso) / (60 * 60 * 24));
        }

        $diasPassados= $diasPassados -1;
        $dMestrado= 730;
        $dDoutorado= 1460;
        $dSem= 180;

        if($this->curso == 1){
            //Prorrogação
            $prorrogacaoAluno= Prorrogacao::find()->where('idAluno =' . $this->id)->all();
            $tDiasProrrogacaoM= 0;
            foreach($prorrogacaoAluno as $pM) {
                if($this->id == $pM->idAluno){
                    $tDiasProrrogacaoM= $tDiasProrrogacaoM + $pM->qtdDias;
                }
            }

            if($diasPassados > $dMestrado+$tDiasProrrogacaoM){
                $diasPassados= $diasPassados - $dMestrado;
            }else{
            	$diasPassados= -1;
            }//Quando não está com prazo vencido----------------<

            //Trancamento
            $trancamentoAluno= Trancamento::find()->where('idAluno =' . $this->id)->all();
            $tDiasTrancamentoM= 0;
            $flagM= false;
            foreach($trancamentoAluno as $tM) {
                if($this->id == $tM->idAluno){
                    $datIni= strtotime($tM->dataInicio);
                    $datTer= strtotime($tM->dataTermino);
                    if($datTer == null){
                        $tDiasTrancamentoM= (int)floor(($dataAtual - $datIni) / (60 * 60 * 24)) + $tDiasTrancamentoM;
                    }else{
                        $tDiasTrancamentoM= (int)floor(($datTer - $datIni) / (60 * 60 * 24)) + $tDiasTrancamentoM;
                    }
                    $flagM= true;
                }
            }

            if($tDiasTrancamentoM > 2*$dSem){
                $diasPassados= $tDiasTrancamentoM - 2*$dSem;
            }else if($flagM == true){
                $diasPassados= -1;
            }
        }

        if($this->curso == 2){
            //Prorrogação
            $prorrogacaoAluno= Prorrogacao::find()->where('idAluno =' . $this->id)->all();
            $tDiasProrrogacaoD= 0;
            foreach($prorrogacaoAluno as $pD) {
                if($this->id == $pD->idAluno){
                    $tDiasProrrogacaoD= $tDiasProrrogacaoD + $pD->qtdDias;
                }
            }

            if($diasPassados > $dDoutorado+$tDiasProrrogacaoD){
                $diasPassados= $diasPassados - $dDoutorado;
            }else{
            	$diasPassados= -1;
            }//Quando não está com prazo vencido----------------<

            //Trancamento
            $trancamentoAluno= Trancamento::find()->where('idAluno =' . $this->id)->all();
            $tDiasTrancamentoD= 0;
            $flagD= false;
            foreach($trancamentoAluno as $tD) {
                if($this->id == $tD->idAluno){
                    $datIni= strtotime($tM->dataInicio);
                    $datTer= strtotime($tM->dataTermino);
                    if($datTer == null){
                        $tDiasTrancamentoD= (int)floor(($dataAtual - $datIni) / (60 * 60 * 24)) + $tDiasTrancamentoD;
                    }else{
                        $tDiasTrancamentoM= (int)floor(($datTer - $datIni) / (60 * 60 * 24)) + $tDiasTrancamentoD;
                    }
                    $flagD= true;
                }
            }

            if($tDiasTrancamentoD > 2*$dSem){
                $diasPassados= $tDiasTrancamentoD - 2*$dSem;
            }else if($flagD == true){
                $diasPassados= -1;
            }

        }

        return $diasPassados;
    }

}
