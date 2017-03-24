<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Banca;

/**
 * BancaSearch represents the model behind the search form about `app\models\Banca`.
 */
class BancaSearch extends Banca
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['banca_id', 'membrosbanca_id'], 'integer'],
            [['funcao', 'passagem'], 'safe'],
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
    public function search($params,$idBanca)
    {
        $query = Banca::find()->select("j17_banca_has_membrosbanca.* , j17_membrosbanca.nome as membro_nome, j17_membrosbanca.filiacao as membro_filiacao ")->where("banca_id = ".$idBanca)
            ->innerJoin("j17_membrosbanca","j17_membrosbanca.id = j17_banca_has_membrosbanca.membrosbanca_id")->orderBy(['funcao'=>SORT_DESC]);

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
            'banca_id' => $this->banca_id,
            'membrosbanca_id' => $this->membrosbanca_id,
        ]);

        $query->andFilterWhere(['like', 'funcao', $this->funcao])
            ->andFilterWhere(['like', 'passagem', $this->passagem]);

        return $dataProvider;
    }
}
