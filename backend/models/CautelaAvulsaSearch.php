<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CautelaAvulsa;

/**
 * CautelaAvulsaSearch represents the model behind the search form of `app\models\CautelaAvulsa`.
 */
class CautelaAvulsaSearch extends CautelaAvulsa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCautelaAvulsa', 'id',], 'integer'],
        	[['TelefoneResponsavel'], 'string', 'max'=>15],
            [['NomeResponsavel', 'Email', 'ValidadeCautela', 'ObservacoesDescarte', 'ImagemCautela', 'StatusCautelaAvulsa', 'TelefoneResponsavel', 'origem', 'NomeEquipamento'], 'safe'],
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
        $query = CautelaAvulsa::find();

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
            'idCautelaAvulsa' => $this->idCautelaAvulsa,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'NomeResponsavel', $this->NomeResponsavel])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'ValidadeCautela', $this->ValidadeCautela])
            ->andFilterWhere(['like', 'ObservacoesDescarte', $this->ObservacoesDescarte])
            ->andFilterWhere(['like', 'ImagemCautela', $this->ImagemCautela])
            ->andFilterWhere(['like', 'StatusCautelaAvulsa', $this->StatusCautelaAvulsa])
            ->andFilterWhere(['like', 'TelefoneResponsavel', $this->TelefoneResponsavel])
        	->andFilterWhere(['like', 'NomeEquipamento', $this->NomeEquipamento])
        	->andFilterWhere(['like', 'origem', $this->origem]);

        return $dataProvider;
    }
}
