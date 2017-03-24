<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Prorrogacao;

/**
 * ProrrogacaoSearch represents the model behind the search form of `app\models\Prorrogacao`.
 */
class ProrrogacaoSearch extends Prorrogacao
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qtdDias', 'status'], 'integer'],
            [['matricula', 'idAluno','orientador', 'dataSolicitacao', 'dataInicio', 'dataInicio0', 'justificativa'], 'safe'],
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
        $query = Prorrogacao::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

            //Specifies the criteria for sorting
            'sort' => array(
                'attributes' => array(
                    'matricula' => array(
                        //Specifies the ordering criteria in ascending order, the others have similar operation
                        'asc' => array('j17_aluno.matricula' => SORT_ASC),
                        //Specifies the ordering criteria in descending order, the others have similar operation
                        'desc'=> array('j17_aluno.matricula' => SORT_DESC)
                    ),
                    'idAluno' => array(
                        'asc' => array('j17_aluno.nome' => SORT_ASC),
                        'desc'=> array('j17_aluno.nome' => SORT_DESC)
                    ),
                    'orientador' => array(
                        'asc' => array('j17_user.nome' => SORT_ASC),
                        'desc'=> array('j17_user.nome' => SORT_DESC)
                    ),
                    'dataInicio0' => array(
                        'asc' => array('dataInicio' => SORT_ASC),
                        'desc'=> array('dataInicio' => SORT_DESC)
                    ),
                    'qtdDias',
                    'status'
                )
            ),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //Joins with tables for students and advisors
        //See 'aluno' and 'orientador0' in the 'Trancamento' model for more information
        $query->joinWith('aluno');
        $query->joinWith('orientador0');

        $searchedDataInicio = explode("/", $this->dataInicio0);
        if (sizeof($searchedDataInicio) == 3) {
            $searchedDataInicio = $searchedDataInicio[2]."-".$searchedDataInicio[1]."-".$searchedDataInicio[0];
        }
        else $searchedDataInicio = '';

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'dataSolicitacao' => $this->dataSolicitacao,
            'dataInicio' => $searchedDataInicio,
            'qtdDias' => $this->qtdDias,
            'j17_prorrogacoes.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'j17_aluno.matricula', $this->matricula])
            ->andFilterWhere(['like', 'j17_aluno.nome', $this->idAluno])
            ->andFilterWhere(['like', 'j17_user.nome', $this->orientador]);
            //->andFilterWhere(['like', 'justificativa', $this->justificativa])

        return $dataProvider;
    }
}
