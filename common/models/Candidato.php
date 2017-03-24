<?php

namespace common\models;

use Yii;
use yiibr\brvalidator\CpfValidator;
use yii\web\UploadedFile;
use yii\db\IntegrityException;
use yii\base\Exception;


class Candidato extends \yii\db\ActiveRecord
{
    /*Varaiáveis intermediarias para uploads*/
    public $recomendacoes;
    public $cartaOrientadorFile;
    public $curriculumFile;
    public $propostaFile;
    public $comprovanteFile;
    public $publicacoesFile;

    public $cartaOrientadorUpload;
    public $curriculumUpload;
    public $propostaUpload;
    public $comprovanteUpload;
    //public $declaracao;
    
    /*Cartas de recomendação Obrigatórias*/
    public $cartaNomeReq1;
    public $cartaNomeReq2;
    public $cartaEmailReq1;
    public $cartaEmailReq2;

    /*Cartas de recomendação Optativas (array)*/
    public $cartaNome;
    public $cartaEmail;

    public $instituicaoacademica1;
    public $instituicaoacademica2;
    public $instituicaoacademica3;
    public $atividade1;
    public $atividade2;
    public $atividade3;
    public $periodoacademico1;
    public $periodoacademico2;
    public $periodoacademico3;

    public $repetirSenha;
    public $auth_key;

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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'nomesocial' => 'Nome Social',
            'endereco' => 'Endereco',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'uf' => 'UF',
            'cep' => 'CEP',
            'email' => 'Email',
            'datanascimento' => 'Data Nascimento',
            'nacionalidade' => 'Nacionalidade',
            'pais' => 'Pais',
            'passaporte' => 'Passaporte',
            'cpf' => 'CPF',
            'sexo' => 'Sexo',
            'telresidencial' => 'Telefone Principal',
            'telcelular' => 'Telefone Alternativo',
            'cursodesejado' => 'Curso Desejado',
            'regime' => 'Regime',
            'inscricaoposcomp' => 'Inscricao PosComp',
            'anoposcomp' => 'Ano PosComp',
            'notaposcomp' => 'Nota PosComp',
            'solicitabolsa' => 'Solicita Bolsa de Estudo',
            'cursograd' => 'Curso',
            'instituicaograd' => 'Instituição',
            'egressograd' => 'Ano de Egresso',
            'cursopos' => 'Curso',
            'instituicaopos' => 'Instituição',
            'tipopos' => 'Tipo',
            'egressopos' => 'Ano Egresso',
            'periodicosinternacionais' => 'Periódicos Internacionais',
            'periodicosnacionais' => 'Periódicos Nacionais',
            'conferenciasinternacionais' => 'Conferências Internacionais',
            'conferenciasnacionais' => 'Conferências Nacionais',
            'instituicaoacademica1' => 'Instituição Acadêmica',
            'instituicaoacademica2' => 'Instituição Acadêmica',
            'instituicaoacademica3' => 'Instituição Acadêmica',
            'atividade1' => 'Atividade',
            'atividade2' => 'Atividade',
            'atividade3' => 'Atividade',
            'periodoacademico1' => 'Período Acadêmico',
            'periodoacademico2' => 'Período Acadêmico',
            'periodoacademico3' => 'Período Acadêmico',
            'senha' => 'Senha',
            'inicio' => 'Data Início',
            'fim' => 'Data Fim',
            'idLinhaPesquisa' => 'Linha de Pesquisa',
            'tituloproposta' => 'Titulo da Proposta',
            'motivos' => 'Motivos',
            'proposta' => 'Proposta',
            'curriculum' => 'Curriculum',
            'comprovantepagamento' => 'Comprovante de Pagamento',
            'dataformaturagrad' => 'Dataformaturagrad',
            'dataformaturapos' => 'Data de Formatura',
            'resultado' => 'Resultado',
            'periodo' => 'Período',
            'idEdital' => 'Edital',
            'declaracao' => 'Declaração de Veracidade de Informações',
            'cartaNomeReq1' => 'Nome',
            'cartaNomeReq2' => 'Nome',
            'cartaEmailReq1' => 'Email',
            'cartaEmailReq2' => 'Email',

                'cartaOrientadorFile' => 'Carta do Orientador',
                'curriculumFile' => 'Curriculum Vittae',
                'propostaFile' => 'Proposta de Trabalho',
                'comprovanteFile' => 'Comprovante de Pagamento',
                'publicacoesFile' => 'Curriculum Vittae XML',
            
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


}
