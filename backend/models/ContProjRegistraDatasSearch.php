<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjRegistraDatas;

/**
 * ContProjRegistraDatasSearch represents the model behind the search form about `backend\models\ContProjRegistraDatas`.
 */
class ContProjRegistraDatasSearch extends ContProjRegistraDatas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id'], 'integer'],
            [['evento', 'data', 'observacao', 'tipo'], 'safe'],
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
        $projeto_id = Yii::$app->request->get('idProjeto');
        $query = ContProjRegistraDatas::find()->where("projeto_id=$projeto_id")->orderBy("data ASC");

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
            'data' => $this->data,
            'projeto_id' => $this->projeto_id,
        ]);

        $query->andFilterWhere(['like', 'evento', $this->evento])
            ->andFilterWhere(['like', 'observacao', $this->observacao])
            ->andFilterWhere(['like', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
