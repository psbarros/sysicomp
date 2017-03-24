<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjProjetos;

/**
 * ContProjProjetosSearch represents the model behind the search form about `backend\models\ContProjProjetos`.
 */
class ContProjProjetosSearch extends ContProjProjetos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'coordenador_id', 'agencia_id', 'banco_id'], 'integer'],
            [['nomeprojeto', 'coordenador','data_inicio', 'data_fim', 'data_fim_alterada', 'agencia', 'conta', 'edital', 'proposta', 'status'], 'safe'],
            [['orcamento', 'saldo'], 'number'],
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
        if(!Yii::$app->user->identity->checarAcesso('secretaria')) {
            $coordenador_id = Yii::$app->user->getId();
            $query = ContProjProjetos::find()->select("j17_user.nome as coordenador,j17_contproj_projetos.*")
                ->leftJoin("j17_user", "j17_contproj_projetos.coordenador_id = j17_user.id")
                ->where("j17_contproj_projetos.coordenador_id=$coordenador_id");
        }else{
            $query = ContProjProjetos::find()->select("j17_user.nome as coordenador,j17_contproj_projetos.*")
                ->leftJoin("j17_user", "j17_contproj_projetos.coordenador_id = j17_user.id");
        }
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

        $dataProvider->sort->attributes['coordenador'] = [
            'asc' => ['coordenador' => SORT_ASC],
            'desc' => ['coordenador' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'coordenador' => $this->coordenador,
            'orcamento' => $this->orcamento,
            'saldo' => $this->saldo,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
            'data_fim_alterada' => $this->data_fim_alterada,
            'coordenador_id' => $this->coordenador_id,
            'agencia_id' => $this->agencia_id,
            'banco_id' => $this->banco_id,
        ]);

        $query->andFilterWhere(['like', 'nomeprojeto', $this->nomeprojeto])
            ->andFilterWhere(['like', 'j17_user.nome', $this->coordenador])
            ->andFilterWhere(['like', 'agencia', $this->agencia])
            ->andFilterWhere(['like', 'conta', $this->conta])
            ->andFilterWhere(['like', 'edital', $this->edital])
            ->andFilterWhere(['like', 'proposta', $this->proposta])
            ->andFilterWhere(['like', 'j17_contproj_projetos.status', $this->status]);
        return $dataProvider;
    }
}
