<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Projetos;

/**
 * ProjetosSearch represents the model behind the search form about `backend\models\Projetos`.
 */
class ProjetosSearch extends Projetos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idProfessor', 'inicio', 'fim'], 'integer'],
            [['titulo', 'descricao', 'papel', 'financiadores', 'integrantes'], 'safe'],
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
        $query = Projetos::find();

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
            'id' => $this->id,
            'idProfessor' => $this->idProfessor,
            'inicio' => $this->inicio,
            'fim' => $this->fim,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'papel', $this->papel])
            ->andFilterWhere(['like', 'financiadores', $this->financiadores])
            ->andFilterWhere(['like', 'integrantes', $this->integrantes]);

        return $dataProvider;
    }
}
