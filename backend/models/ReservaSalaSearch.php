<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReservaSala;

/**
 * ReservaSalaSearch represents the model behind the search form about `app\models\ReservaSala`.
 */
class ReservaSalaSearch extends ReservaSala
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sala', 'idSolicitante'], 'integer'],
            [['dataReserva', 'atividade', 'tipo', 'dataInicio', 'dataTermino', 'horaInicio', 'horaTermino'], 'safe'],
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
        if(Yii::$app->user->identity->secretaria)
            $query = ReservaSala::find()->where('dataInicio >= \''.date('Y-m-d').'\'')->orderBy("dataInicio");
        else
            $query = ReservaSala::find()->where('dataInicio >= \''.date('Y-m-d').'\'')->andwhere(['idSolicitante' => Yii::$app->user->identity->id])->orderBy("dataInicio");

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
            'dataReserva' => $this->dataReserva,
            'sala' => $this->sala,
            'idSolicitante' => $this->idSolicitante,
            'dataInicio' => $this->dataInicio,
            'dataTermino' => $this->dataTermino,
            'horaInicio' => $this->horaInicio,
            'horaTermino' => $this->horaTermino,
        ]);

        $query->andFilterWhere(['like', 'atividade', $this->atividade])
            ->andFilterWhere(['like', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
