<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Candidato;

/**
 * CandidatosSearch represents the model behind the search form about `app\models\Candidato`.
 */
class CandidatosSearch extends Candidato
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'passoatual', 'nacionalidade', 'cursodesejado', 'regime', 'anoposcomp', 'idLinhaPesquisa', 'egressograd', 'tipopos', 'egressopos', 'periodicosinternacionais', 'periodicosnacionais', 'conferenciasinternacionais', 'conferenciasnacionais', 'resultado'], 'integer'],
            [['senha', 'inicio', 'fim', 'nome', 'endereco', 'bairro', 'cidade', 'uf', 'cep', 'email', 'datanascimento', 'pais', 'passaporte', 'cpf', 'sexo', 'telresidencial', 'telcelular', 'inscricaoposcomp', 'notaposcomp', 'solicitabolsa', 'tituloproposta', 'cartaorientador', 'motivos', 'proposta', 'curriculum', 'comprovantepagamento', 'cursograd', 'instituicaograd',  'dataformaturagrad', 'cursopos', 'instituicaopos', 'dataformaturapos', 'periodo'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $idEdital = $params['id'];
        Yii::$app->db->createCommand('SET SESSION wait_timeout = 28800;')->execute();
        $query = Candidato::find()->select("j17_edital.cartarecomendacao as carta_recomendacao ,j17_linhaspesquisa.sigla as siglaLinhaPesquisa, candidato1.*, qtd_cartas, cartas_pendentes, (qtd_cartas-cartas_pendentes) as cartas_respondidas")
        ->leftJoin("j17_linhaspesquisa","candidato1.idLinhaPesquisa =   j17_linhaspesquisa.id")
        ->innerJoin("j17_edital", "j17_edital.numero = candidato1.idEdital")
        ->leftJoin("j17_recomendacoes","j17_recomendacoes.idCandidato = candidato1.id")->alias('candidato1')
        ->leftJoin("(SELECT idCandidato, if(dataResposta = '0000-00-00 00:00:00', count(dataResposta),0) as cartas_pendentes from j17_recomendacoes group by idCandidato, dataResposta) recomendacao1"," candidato1.id = recomendacao1.idCandidato")
        ->leftJoin("(SELECT idCandidato, count(idCandidato) as qtd_cartas from j17_recomendacoes group by idCandidato) recomendacao2 "," candidato1.id = recomendacao2.idCandidato")
        ->where('idEdital ="'.$idEdital.'" AND candidato1.passoatual = 4')
        ->groupBy('id');

        // Para que o comando acima funcione, é importante dar o seguinte comando no banco de dados:
        // SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['siglaLinhaPesquisa'] = [
        'asc' => ['siglaLinhaPesquisa' => SORT_ASC],
        'desc' => ['siglaLinhaPesquisa' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['qtd_cartas'] = [
        'asc' => ['qtd_cartas' => SORT_ASC],
        'desc' => ['qtd_cartas' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['cartas_respondidas'] = [
        'asc' => ['cartas_respondidas' => SORT_ASC],
        'desc' => ['cartas_respondidas' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['fase'] = [
        'asc' => ['resultado' => SORT_ASC],
        'desc' => ['resultado' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'inicio' => $this->inicio,
            'fim' => $this->fim,
            'passoatual' => $this->passoatual,
            'nacionalidade' => $this->nacionalidade,
            'cursodesejado' => $this->cursodesejado,
            'regime' => $this->regime,
            'anoposcomp' => $this->anoposcomp,
            'idLinhaPesquisa' => $this->idLinhaPesquisa,
            'egressograd' => $this->egressograd,
            'tipopos' => $this->tipopos,
            'egressopos' => $this->egressopos,
            'periodicosinternacionais' => $this->periodicosinternacionais,
            'periodicosnacionais' => $this->periodicosnacionais,
            'conferenciasinternacionais' => $this->conferenciasinternacionais,
            'conferenciasnacionais' => $this->conferenciasnacionais,
            'resultado' => $this->resultado,
        ]);

        $query->andFilterWhere(['like', 'senha', $this->senha])
            ->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'endereco', $this->endereco])
            ->andFilterWhere(['like', 'bairro', $this->bairro])
            ->andFilterWhere(['like', 'cidade', $this->cidade])
            ->andFilterWhere(['like', 'uf', $this->uf])
            ->andFilterWhere(['like', 'cep', $this->cep])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'datanascimento', $this->datanascimento])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'passaporte', $this->passaporte])
            ->andFilterWhere(['like', 'cpf', $this->cpf])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'telresidencial', $this->telresidencial])
            ->andFilterWhere(['like', 'telcelular', $this->telcelular])
            ->andFilterWhere(['like', 'inscricaoposcomp', $this->inscricaoposcomp])
            ->andFilterWhere(['like', 'notaposcomp', $this->notaposcomp])
            ->andFilterWhere(['like', 'solicitabolsa', $this->solicitabolsa])
            ->andFilterWhere(['like', 'tituloproposta', $this->tituloproposta])
            ->andFilterWhere(['like', 'cartaorientador', $this->cartaorientador])
            ->andFilterWhere(['like', 'motivos', $this->motivos])
            ->andFilterWhere(['like', 'proposta', $this->proposta])
            ->andFilterWhere(['like', 'curriculum', $this->curriculum])
            ->andFilterWhere(['like', 'comprovantepagamento', $this->comprovantepagamento])
            ->andFilterWhere(['like', 'cursograd', $this->cursograd])
            ->andFilterWhere(['like', 'instituicaograd', $this->instituicaograd])
            ->andFilterWhere(['like', 'dataformaturagrad', $this->dataformaturagrad])
            ->andFilterWhere(['like', 'cursopos', $this->cursopos])
            ->andFilterWhere(['like', 'instituicaopos', $this->instituicaopos])
            ->andFilterWhere(['like', 'dataformaturapos', $this->dataformaturapos])
            ->andFilterWhere(['like', 'periodo', $this->periodo]);

        return $dataProvider;
    }


public function search2($params)
    {
        $idEdital = $params['id'];
        $query = Candidato::find()->select("j17_edital.cartarecomendacao as carta_recomendacao ,j17_linhaspesquisa.sigla as siglaLinhaPesquisa, candidato1.*, qtd_cartas, cartas_pendentes, (qtd_cartas-cartas_pendentes) as cartas_respondidas")
        ->leftJoin("j17_linhaspesquisa","candidato1.idLinhaPesquisa = j17_linhaspesquisa.id")
        ->innerJoin("j17_edital", "j17_edital.numero = candidato1.idEdital")
        ->leftJoin("j17_recomendacoes","j17_recomendacoes.idCandidato = candidato1.id")->alias('candidato1')
        ->leftJoin("(SELECT idCandidato, if(dataResposta = '0000-00-00 00:00:00', count(dataResposta),0) as cartas_pendentes from j17_recomendacoes group by idCandidato, dataResposta) recomendacao1"," candidato1.id = recomendacao1.idCandidato")
        ->leftJoin("(SELECT idCandidato, count(idCandidato) as qtd_cartas from j17_recomendacoes group by idCandidato) recomendacao2 "," candidato1.id = recomendacao2.idCandidato")
        ->where('idEdital ="'.$idEdital.'" AND candidato1.passoatual != 4')
        ->groupBy('id');

        // Para que o comando acima funcione, é importante dar o seguinte comando no banco de dados:
        // SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

        //$query2 = Candidato::find()->leftJoin(" (select * FROM j17_recomendacoes ) as rec ", "rec.idCandidato = j17_candidatos.id ");


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->attributes['siglaLinhaPesquisa'] = [
        'asc' => ['siglaLinhaPesquisa' => SORT_ASC],
        'desc' => ['siglaLinhaPesquisa' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['qtd_cartas'] = [
        'asc' => ['qtd_cartas' => SORT_ASC],
        'desc' => ['qtd_cartas' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['cartas_respondidas'] = [
        'asc' => ['cartas_respondidas' => SORT_ASC],
        'desc' => ['cartas_respondidas' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['fase'] = [
        'asc' => ['resultado' => SORT_ASC],
        'desc' => ['resultado' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'inicio' => $this->inicio,
            'fim' => $this->fim,
            'passoatual' => $this->passoatual,
            'nacionalidade' => $this->nacionalidade,
            'cursodesejado' => $this->cursodesejado,
            'regime' => $this->regime,
            'anoposcomp' => $this->anoposcomp,
            'idLinhaPesquisa' => $this->idLinhaPesquisa,
            'egressograd' => $this->egressograd,
            'tipopos' => $this->tipopos,
            'egressopos' => $this->egressopos,
            'periodicosinternacionais' => $this->periodicosinternacionais,
            'periodicosnacionais' => $this->periodicosnacionais,
            'conferenciasinternacionais' => $this->conferenciasinternacionais,
            'conferenciasnacionais' => $this->conferenciasnacionais,
            'resultado' => $this->resultado,
        ]);

        $query->andFilterWhere(['like', 'senha', $this->senha])
            ->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'endereco', $this->endereco])
            ->andFilterWhere(['like', 'bairro', $this->bairro])
            ->andFilterWhere(['like', 'cidade', $this->cidade])
            ->andFilterWhere(['like', 'uf', $this->uf])
            ->andFilterWhere(['like', 'cep', $this->cep])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'datanascimento', $this->datanascimento])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'passaporte', $this->passaporte])
            ->andFilterWhere(['like', 'cpf', $this->cpf])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'telresidencial', $this->telresidencial])
            ->andFilterWhere(['like', 'telcelular', $this->telcelular])
            ->andFilterWhere(['like', 'inscricaoposcomp', $this->inscricaoposcomp])
            ->andFilterWhere(['like', 'notaposcomp', $this->notaposcomp])
            ->andFilterWhere(['like', 'solicitabolsa', $this->solicitabolsa])
            ->andFilterWhere(['like', 'tituloproposta', $this->tituloproposta])
            ->andFilterWhere(['like', 'cartaorientador', $this->cartaorientador])
            ->andFilterWhere(['like', 'motivos', $this->motivos])
            ->andFilterWhere(['like', 'proposta', $this->proposta])
            ->andFilterWhere(['like', 'curriculum', $this->curriculum])
            ->andFilterWhere(['like', 'comprovantepagamento', $this->comprovantepagamento])
            ->andFilterWhere(['like', 'cursograd', $this->cursograd])
            ->andFilterWhere(['like', 'instituicaograd', $this->instituicaograd])
            ->andFilterWhere(['like', 'dataformaturagrad', $this->dataformaturagrad])
            ->andFilterWhere(['like', 'cursopos', $this->cursopos])
            ->andFilterWhere(['like', 'instituicaopos', $this->instituicaopos])
            ->andFilterWhere(['like', 'dataformaturapos', $this->dataformaturapos])
            ->andFilterWhere(['like', 'periodo', $this->periodo]);

            //

        return $dataProvider;
    }

}
