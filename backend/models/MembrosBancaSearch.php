<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MembrosBanca;

/**
 * MembrosBancaSearch represents the model behind the search form about `app\models\MembrosBanca`.
 */
class MembrosBancaSearch extends MembrosBanca
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idProfessor'], 'integer'],
            [['nome', 'email', 'filiacao', 'telefone', 'cpf', 'dataCadastro'], 'safe'],
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
        $query = MembrosBanca::find();

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
            'dataCadastro' => $this->dataCadastro,
            'idProfessor' => $this->idProfessor,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'filiacao', $this->filiacao])
            ->andFilterWhere(['like', 'telefone', $this->telefone])
            ->andFilterWhere(['like', 'cpf', $this->cpf]);

        return $dataProvider;
    }
}
