<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Disciplina;

/**
 * DisciplinaSearch represents the model behind the search form of `app\models\Disciplina`.
 */
class DisciplinaSearch extends Disciplina
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codDisciplina', 'nome', 'creditos', 'cargaHoraria', 'preRequisito', 'obrigatoria','nomeCurso','instituicao'], 'safe'],
            [['creditos', 'cargaHoraria', 'preRequisito', 'obrigatoria'], 'integer'],
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
        $query = Disciplina::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'creditos' => $this->creditos,
            'cargaHoraria' => $this->cargaHoraria,
        	'preRequisito'=> $this->preRequisito,
        	'obrigatoria'=> $this->obrigatoria,
        ]);

        $query->andFilterWhere(['like', 'codDisciplina', strtolower($this->codDisciplina)])
            ->andFilterWhere(['like', 'nome', strtolower($this->nome)])
        	->andFilterWhere(['like', 'nomeCurso', strtolower($this->nomeCurso)])
        	->andFilterWhere(['like', 'instituicao', strtolower($this->instituicao)]);

        return $dataProvider;
    }
}
