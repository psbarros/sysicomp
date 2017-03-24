<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjRubricasdeProjetos;

/**
 * ContProjRubricasdeProjetosSearch represents the model behind the search form about `backend\models\ContProjRubricasdeProjetos`.
 */
class ContProjRubricasdeProjetosSearch extends ContProjRubricasdeProjetos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id', 'rubrica_id'], 'integer'],
            [['descricao','nomeprojeto','nomerubrica','coordenador','data_fim'], 'safe'],
            [['valor_total', 'valor_gasto', 'valor_disponivel'], 'number'],
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
        $query = ContProjRubricasdeProjetos::find()->select("j17_contproj_projetos.nomeprojeto AS nomeprojeto,
        j17_contproj_rubricas.nome AS nomerubrica,j17_contproj_rubricasdeprojetos.*")
        ->leftJoin("j17_contproj_projetos","j17_contproj_projetos.id=j17_contproj_rubricasdeprojetos.projeto_id")
        ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricas.id=j17_contproj_rubricasdeprojetos.rubrica_id")
        ->where("j17_contproj_rubricasdeprojetos.projeto_id=$projeto_id");
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

        $dataProvider->sort->attributes['nomeprojeto'] = [
            'asc' => ['nomeprojeto' => SORT_ASC],
            'desc' => ['nomeprojeto' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['nomerubrica'] = [
            'asc' => ['nomerubrica' => SORT_ASC],
            'desc' => ['nomerubrica' => SORT_DESC],
        ];
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'nomeprojeto' => $this->nomeprojeto,
            //'nomerubrica' => $this->nomerubrica,
            'projeto_id' => $this->projeto_id,
            'rubrica_id' => $this->rubrica_id,
            'valor_total' => $this->valor_total,
            'valor_gasto' => $this->valor_gasto,
            'valor_disponivel' => $this->valor_disponivel,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'j17_contproj_projetos.nomeprojeto', $this->nomeprojeto])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.nome', $this->nomerubrica]);
        return $dataProvider;
    }

    public function searchByRubrica($params,$model)
    {
        $projeto_id = Yii::$app->request->get('idProjeto');
        $saldo = 5000;
        if($model->valor_disponivel==null){
            $query = ContProjRubricasdeProjetos::find()->select("j17_contproj_projetos.nomeprojeto AS nomeprojeto,
            j17_contproj_projetos.id as projeto, j17_contproj_projetos.coordenador_id AS coordenador,
            j17_contproj_projetos.data_fim AS data_fim,j17_contproj_projetos.agencia_id AS agencia,
            j17_contproj_rubricas.nome AS nomerubrica,j17_contproj_rubricasdeprojetos.*")
                ->leftJoin("j17_contproj_projetos", "j17_contproj_projetos.id=j17_contproj_rubricasdeprojetos.projeto_id")
                ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id=j17_contproj_rubricasdeprojetos.rubrica_id")
                ->where("j17_contproj_rubricasdeprojetos.valor_disponivel > 0 AND j17_contproj_rubricas.id = 1")
                ->orderBy("data_fim desc");;

        }else {
            $query = ContProjRubricasdeProjetos::find()->select("j17_contproj_projetos.nomeprojeto AS nomeprojeto,
            j17_contproj_projetos.id as projeto, j17_contproj_projetos.coordenador_id AS coordenador,
            j17_contproj_projetos.data_fim AS data_fim,j17_contproj_projetos.agencia_id AS agencia,
            j17_contproj_rubricas.nome AS nomerubrica,j17_contproj_rubricasdeprojetos.*")
                ->leftJoin("j17_contproj_projetos", "j17_contproj_projetos.id=j17_contproj_rubricasdeprojetos.projeto_id")
                ->leftJoin("j17_contproj_rubricas", "j17_contproj_rubricas.id=j17_contproj_rubricasdeprojetos.rubrica_id")
                ->where("j17_contproj_rubricasdeprojetos.valor_disponivel > 0 
            AND j17_contproj_rubricasdeprojetos.valor_disponivel <= $model->valor_disponivel AND j17_contproj_rubricas.id = 1")
                ->orderBy("data_fim desc");
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

        $dataProvider->sort->attributes['nomeprojeto'] = [
            'asc' => ['nomeprojeto' => SORT_ASC],
            'desc' => ['nomeprojeto' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['data_fim'] = [
            'asc' => ['data_fim' => SORT_ASC],
            'desc' => ['data_fim' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['agencia'] = [
            'asc' => ['agencia' => SORT_ASC],
            'desc' => ['agencia' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['projeto'] = [
            'asc' => ['projeto' => SORT_ASC],
            'desc' => ['projeto' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['coordenador'] = [
            'asc' => ['coordenador' => SORT_ASC],
            'desc' => ['coordenador' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nomerubrica'] = [
            'asc' => ['nomerubrica' => SORT_ASC],
            'desc' => ['nomerubrica' => SORT_DESC],
        ];
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'nomeprojeto' => $this->nomeprojeto,
            //'nomerubrica' => $this->nomerubrica,
            'projeto_id' => $this->projeto_id,
            'rubrica_id' => $this->rubrica_id,
            'coordenador_id' => $this->coordenador,
            'valor_total' => $this->valor_total,
            'valor_gasto' => $this->valor_gasto,
            //'valor_disponivel' => $this->valor_disponivel,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'j17_contproj_projetos.nomeprojeto', $this->nomeprojeto])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.nome', $this->nomerubrica])
            ->andFilterWhere(['<=', 'j17_contproj_rubricasdeprojetos.valor_disponivel', $this->valor_disponivel]);;
        return $dataProvider;
    }


    public function searchCapital($params)
    {
        $projeto_id = Yii::$app->request->get('idProjeto');
        $query = ContProjRubricasdeProjetos::find()->select("j17_contproj_projetos.nomeprojeto AS nomeprojeto,
        j17_contproj_rubricas.nome AS nomerubrica,j17_contproj_rubricasdeprojetos.*")
            ->leftJoin("j17_contproj_projetos","j17_contproj_projetos.id=j17_contproj_rubricasdeprojetos.projeto_id")
            ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricas.id=j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id=$projeto_id AND j17_contproj_rubricas.tipo = 'Capital'");
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

        $dataProvider->sort->attributes['nomeprojeto'] = [
            'asc' => ['nomeprojeto' => SORT_ASC],
            'desc' => ['nomeprojeto' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['nomerubrica'] = [
            'asc' => ['nomerubrica' => SORT_ASC],
            'desc' => ['nomerubrica' => SORT_DESC],
        ];
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'nomeprojeto' => $this->nomeprojeto,
            //'nomerubrica' => $this->nomerubrica,
            'projeto_id' => $this->projeto_id,
            'rubrica_id' => $this->rubrica_id,
            'valor_total' => $this->valor_total,
            'valor_gasto' => $this->valor_gasto,
            'valor_disponivel' => $this->valor_disponivel,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'j17_contproj_projetos.nomeprojeto', $this->nomeprojeto])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.nome', $this->nomerubrica]);
        return $dataProvider;
    }


    public function searchCusteio($params)
    {
        $projeto_id = Yii::$app->request->get('idProjeto');
        $query = ContProjRubricasdeProjetos::find()->select("j17_contproj_projetos.nomeprojeto AS nomeprojeto,
        j17_contproj_rubricas.nome AS nomerubrica,j17_contproj_rubricasdeprojetos.*")
            ->leftJoin("j17_contproj_projetos","j17_contproj_projetos.id=j17_contproj_rubricasdeprojetos.projeto_id")
            ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricas.id=j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id=$projeto_id AND j17_contproj_rubricas.tipo = 'Custeio'");
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

        $dataProvider->sort->attributes['nomeprojeto'] = [
            'asc' => ['nomeprojeto' => SORT_ASC],
            'desc' => ['nomeprojeto' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['nomerubrica'] = [
            'asc' => ['nomerubrica' => SORT_ASC],
            'desc' => ['nomerubrica' => SORT_DESC],
        ];
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'nomeprojeto' => $this->nomeprojeto,
            //'nomerubrica' => $this->nomerubrica,
            'projeto_id' => $this->projeto_id,
            'rubrica_id' => $this->rubrica_id,
            'valor_total' => $this->valor_total,
            'valor_gasto' => $this->valor_gasto,
            'valor_disponivel' => $this->valor_disponivel,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'j17_contproj_projetos.nomeprojeto', $this->nomeprojeto])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.nome', $this->nomerubrica]);
        return $dataProvider;
    }


    public function searchDetalhado($params)
    {
        $projeto_id = Yii::$app->request->get('idProjeto');
        $query = ContProjRubricasdeProjetos::find()->select("j17_contproj_receitas.descricao AS nomeprojeto,
        j17_contproj_despesas.descricao AS nomerubrica")
            ->leftJoin("j17_contproj_receitas","j17_contproj_rubricasdeprojetos.id = j17_contproj_receitas.rubricasdeprojetos_id")
            ->leftJoin("j17_contproj_despesas","j17_contproj_rubricasdeprojetos.id = j17_contproj_despesas.rubricasdeprojetos_id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id=$projeto_id AND j17_contproj_rubricasdeprojetos.id");
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

        return $dataProvider;
    }


}
