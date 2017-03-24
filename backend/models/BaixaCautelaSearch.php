<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BaixaCautela;

/**
 * BaixaCautelaSearch represents the model behind the search form of `app\models\BaixaCautela`.
 */
class BaixaCautelaSearch extends BaixaCautela
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idBaixaCautela', 'idCautela'], 'integer'],
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
        $query = BaixaCautela::find();

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
            'idBaixaCautela' => $this->idBaixaCautela,
            'idCautela' => $this->idCautela,
           // 'idCautelaAvulsa' => $this->idCautelaAvulsa,
        ]);

        $query->andFilterWhere(['like', 'Recebedor', $this->Recebedor])
            ->andFilterWhere(['like', 'DataDevolucao', $this->DataDevolucao])
            //->andFilterWhere(['like', 'Equipamento', $this->Equipamento])
            ->andFilterWhere(['like', 'ObservacaoBaixaCautela', $this->ObservacaoBaixaCautela]);

        return $dataProvider;
    }
}
