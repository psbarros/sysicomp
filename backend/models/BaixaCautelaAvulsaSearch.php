<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BaixaCautelaAvulsa;

/**
 * BaixaCautelaAvulsaSearch represents the model behind the search form of `app\models\BaixaCautelaAvulsa`.
 */
class BaixaCautelaAvulsaSearch extends BaixaCautelaAvulsa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idBaixaCautelaAvulsa', 'idCautelaAvulsa'], 'integer'],
            [['Recebedor', 'DataDevolucao', 'ObservacaoBaixaCautela'], 'safe'],
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
        $query = BaixaCautelaAvulsa::find();

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
            'idBaixaCautelaAvulsa' => $this->idBaixaCautelaAvulsa,
            'idCautelaAvulsa' => $this->idCautelaAvulsa,
        	'DataDevolucao' => $this->DataDevolucao,
        ]);

        $query->andFilterWhere(['like', 'Recebedor', $this->Recebedor])
            //->andFilterWhere(['like', )
            //->andFilterWhere(['like', 'Equipamento', $this->Equipamento])
            ->andFilterWhere(['like', 'ObservacaoBaixaCautela', $this->ObservacaoBaixaCautela]);

        return $dataProvider;
    }
}
