<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AgendarDefesa;

/**
 * AgendarDefesasSearch represents the model behind the search form of `app\models\AgendarDefesa`.
 */
class AgendarDefesasSearch extends AgendarDefesa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idDefesa', 'numDefesa', 'reservas_id', 'banca_id', 'aluno_id'], 'integer'],
            [['titulo', 'tipoDefesa', 'data', 'conceito', 'horario', 'local', 'resumo', 'examinador', 'emailExaminador', 'previa'], 'safe'],
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
        $query = AgendarDefesa::find();

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
            'idDefesa' => $this->idDefesa,
            'numDefesa' => $this->numDefesa,
            'reservas_id' => $this->reservas_id,
            'banca_id' => $this->banca_id,
            'aluno_id' => $this->aluno_id,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'tipoDefesa', $this->tipoDefesa])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'conceito', $this->conceito])
            ->andFilterWhere(['like', 'horario', $this->horario])
            ->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'resumo', $this->resumo])
            ->andFilterWhere(['like', 'examinador', $this->examinador])
            ->andFilterWhere(['like', 'emailExaminador', $this->emailExaminador])
            ->andFilterWhere(['like', 'previa', $this->previa]);

        return $dataProvider;
    }
}
