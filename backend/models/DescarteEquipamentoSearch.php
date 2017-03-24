<?php

namespace app\models;

//teste

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DescarteEquipamento;

/**
 * DescarteEquipamentoSearch represents the model behind the search form of `app\models\DescarteEquipamento`.
 */
class DescarteEquipamentoSearch extends DescarteEquipamento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idDescarte'], 'integer'],
            [['NomeResponsavel', 'Email', 'TelefoneResponsavel', 'ObservacoesDescarte', 'documento', 'dataDescarte', 'documentoImagem'], 'safe'],
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
        $query = DescarteEquipamento::find();

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
            'idDescarte' => $this->idDescarte,
        ]);

        $query->andFilterWhere(['like', 'NomeResponsavel', $this->NomeResponsavel])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'TelefoneResponsavel', $this->TelefoneResponsavel])
            ->andFilterWhere(['like', 'ObservacoesDescarte', $this->ObservacoesDescarte])
        	->andFilterWhere(['like', 'documento', $this->documento])
        	->andFilterWhere(['like', 'dataDescarte', $this->dataDescarte])
        	->andFilterWhere(['like', 'documentoImagem', $this->documentoImagem]);

        return $dataProvider;
    }
}
