<?php

namespace app\models;

use Yii;
use common\models\Recomendacoes;


class Candidato extends \yii\db\ActiveRecord
{

        public $siglaLinhaPesquisa;
        public $qtd_cartas;
        public $cartas_pendentes;
        public $cartas_respondidas;
        public $carta_recomendacao;
        public $fase;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_candidatos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['carta_recomendacao'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'senha' => 'Senha',
            'inicio' => 'Data da Inicialização da Inscrição',
            'fim' => 'Data da Finalização da Inscrição',
            'passoatual' => 'Passoatual',
            'nome' => 'Nome',
            'endereco' => 'Endereço',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'uf' => 'Estado',
            'cep' => 'CEP',
            'email' => 'E-mail',
            'datanascimento' => 'Data de nascimento',
            'nacionalidade' => 'Nacionalidade',
            'pais' => 'País',
            'estadocivil' => 'Estadocivil',
            'rg' => 'RG',
            'orgaoexpedidor' => 'Orgaoexpedidor',
            'dataexpedicao' => 'Dataexpedicao',
            'passaporte' => 'Passaporte',
            'cpf' => 'CPF',
            'sexo' => 'Sexo',
            'telresidencial' => 'Telefone Principal',
            'telcelular' => 'Telefone Alternativo',
            'nomepai' => 'Nomepai',
            'nomemae' => 'Nomemae',
            'cursodesejado' => 'Curso desejado',
            'regime' => 'Regime de Dedicação',
            'inscricaoposcomp' => 'Inscrição poscomp',
            'anoposcomp' => 'Ano poscomp',
            'notaposcomp' => 'Nota poscomp',
            'solicitabolsa' => 'Solicita Bolsa?',
            'cotas' => 'Cotista?',
            'deficiencia' => 'Possui Deficiência?',
            'vinculoemprego' => 'Vinculoemprego',
            'empregador' => 'Empregador',
            'cargo' => 'Cargo',
            'vinculoconvenio' => 'Vinculoconvenio',
            'convenio' => 'Convenio',
            //'idLinhaPesquisa' => 'idLinhaPesquisa',
            'tituloproposta' => 'Tituloproposta',
            'diploma' => 'Diploma',
            'historico' => 'Historico',
            'motivos' => 'Motivos',
            'proposta' => 'Proposta',
            'curriculum' => 'Curriculum',
            'cartaempregador' => 'Cartaempregador',
            'comprovantepagamento' => 'Comprovantepagamento',
            'cursograd' => 'Curso de graduação',
            'instituicaograd' => 'Instituicao da graduação',
//            'crgrad' => 'Coeficiente de Rendimento da graduação',
            'egressograd' => 'Ano de Egresso na Graduação',
            'dataformaturagrad' => 'Data da formatura (graduação)',
            'cursoesp' => 'Cursoesp',
            'instituicaoesp' => 'Instituicaoesp',
            'egressoesp' => 'Egressoesp',
            'dataformaturaesp' => 'Dataformaturaesp',
            'cursopos' => 'Curso de pós Graduação',
            'instituicaopos' => 'Instituição da Pós Graduação',
            'tipopos' => 'Tipo de Pós Graduação',
            'mediapos' => 'Mediapos',
            'egressopos' => 'Ano de Egresso Na Pós Graduação',
            //'dataformaturapos' => 'Dataformaturapos',
            'periodicosinternacionais' => 'Periodicosinternacionais',
            'periodicosnacionais' => 'Periodicosnacionais',
            'conferenciasinternacionais' => 'Conferenciasinternacionais',
            'conferenciasnacionais' => 'Conferenciasnacionais',
            'instituicaoingles' => 'Instituicaoingles',
            'duracaoingles' => 'Duracaoingles',
            'nomeexame' => 'Nomeexame',
            'dataexame' => 'Dataexame',
            'notaexame' => 'Notaexame',
            'empresa1' => 'Empresa1',
            'empresa2' => 'Empresa2',
            'empresa3' => 'Empresa3',
            'cargo1' => 'Cargo1',
            'cargo2' => 'Cargo2',
            'cargo3' => 'Cargo3',
            'periodoprofissional1' => 'Periodoprofissional1',
            'periodoprofissional2' => 'Periodoprofissional2',
            'periodoprofissional3' => 'Periodoprofissional3',
            'instituicaoacademica1' => 'Instituicaoacademica1',
            'instituicaoacademica2' => 'Instituicaoacademica2',
            'instituicaoacademica3' => 'Instituicaoacademica3',
            'atividade1' => 'Atividade1',
            'atividade2' => 'Atividade2',
            'atividade3' => 'Atividade3',
            'periodoacademico1' => 'Periodoacademico1',
            'periodoacademico2' => 'Periodoacademico2',
            'periodoacademico3' => 'Periodoacademico3',
            'resultado' => 'Resultado',
            'periodo' => 'Periodo',
        ];
    }



    /*Inicio dos Relacionamentos*/
    public function getEdital()
    {
        return $this->hasOne(Edital::className(), ['numero' => 'idEdital']);
    }

    public function getlinhaPesquisa()
    {
        return $this->hasOne(LinhaPesquisa::className(), ['id' => 'idLinhaPesquisa']);
    }

    public function getRecomendacoes()
    {
        return $this->hasMany(Recomendacoes::className(), ['idCandidato' => 'id']);
    }

    /*Fim dos Relacionamentos*/


    public function download($idCandidato,$idEdital){

        return Candidato::findOne(['id' => $idCandidato]);
    }

    public function getDiretorio(){
        $salt1 = "programadeposgraduacaoufamicompPPGI";
        $salt2 = $this->id * 777;
        $id = $this->id;
        $idCriptografado = md5($salt1+$id+$salt2);
        //definição de um caminho padrão, baseado no ID do candidato
        $caminho = 'documentos/'.$this->idEdital.'/'.$idCriptografado.'/';
        //fim da definição do caminho padrão
        return $caminho;

    }

    public function getQtdCartasRespondidas(){
        $recomedacoes = new Recomendacoes();
        return $recomedacoes->getCartasRespondidas($this->id);
    }

    public function getQtdCartasEmitidas(){
        $recomedacoes = new Recomendacoes();
        return $recomedacoes->getCartasEmitidas($this->id);
    }

    public function getCartasPrazo(){
        $recomendacoes = Recomendacoes::findOne(['idCandidato' => $this->id, 'dataResposta' => '0000-00-00 00:00:00']);
        
        if(count($recomendacoes))
            return $recomendacoes->prazo < date('Y-m-d') ? true : false;
        else
            return false;
    }


}
