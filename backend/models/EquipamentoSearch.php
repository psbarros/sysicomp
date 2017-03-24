<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Equipamento;

/**
 * EquipamentoSearch represents the model behind the search form of `app\models\Equipamento`.
 */
class EquipamentoSearch extends Equipamento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idEquipamento'], 'integer'],
            [['NomeEquipamento', 'Nserie', 'NotaFiscal', 'Localizacao', 'StatusEquipamento', 'OrigemEquipamento', 'ImagemEquipamento'], 'safe'],
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
        $query = Equipamento::find();

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
            'idEquipamento' => $this->idEquipamento,
        ]);

        $query->andFilterWhere(['like', 'NomeEquipamento', $this->NomeEquipamento])
            ->andFilterWhere(['like', 'Nserie', $this->Nserie])
            ->andFilterWhere(['like', 'NotaFiscal', $this->NotaFiscal])
            ->andFilterWhere(['like', 'Localizacao', $this->Localizacao])
            ->andFilterWhere(['like', 'StatusEquipamento', $this->StatusEquipamento])
            ->andFilterWhere(['like', 'OrigemEquipamento', $this->OrigemEquipamento])
            ->andFilterWhere(['like', 'ImagemEquipamento', $this->ImagemEquipamento]);

        return $dataProvider;
    }
}
