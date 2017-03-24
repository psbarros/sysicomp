<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjDespesas;

/**
 * ContProjDespesasSearch represents the model behind the search form about `backend\models\ContProjDespesas`.
 */
class ContProjDespesasSearch extends ContProjDespesas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rubricasdeprojetos_id'], 'integer'],
            [['descricao', 'tipo_pessoa', 'data_emissao', 'ident_nf', 'nf', 'ident_cheque',
               'nomeRubrica', 'data_emissao_cheque', 'favorecido', 'cnpj_cpf', 'comprovante'], 'safe'],
            [['valor_despesa', 'valor_cheque'], 'number'],
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
    public function search($params,$tipo)
    {
        $projeto_id = Yii::$app->request->get('idProjeto');
        $query = ContProjDespesas::find()->select("j17_contproj_despesas.*, j17_contproj_rubricas.codigo as codigo, 
        j17_contproj_rubricas.nome as nomeRubrica, j17_contproj_rubricas.tipo as tipo ")
        ->leftJoin("j17_contproj_rubricasdeprojetos","j17_contproj_despesas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
        ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricasdeprojetos.rubrica_id = j17_contproj_rubricas.id")
        ->where("j17_contproj_rubricasdeprojetos.projeto_id = $projeto_id AND j17_contproj_rubricas.tipo = '$tipo'")->orderBy("tipo");;

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

        $dataProvider->sort->attributes['tipo'] = [
            'asc' => ['j17_contproj_rubricas.tipo' => SORT_ASC],
            'desc' => ['j17_contproj_rubricas.tipo' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nomeRubrica'] = [
            'asc' => ['j17_contproj_rubricas.nome' => SORT_ASC],
            'desc' => ['j17_contproj_rubricas.nome' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['codigo'] = [
            'asc' => ['j17_contproj_rubricas.codigo' => SORT_ASC],
            'desc' => ['j17_contproj_rubricas.codigo' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rubricasdeprojetos_id' => $this->rubricasdeprojetos_id,
            'valor_despesa' => $this->valor_despesa,
            'data_emissao' => $this->data_emissao,
            'data_emissao_cheque' => $this->data_emissao_cheque,
            'valor_cheque' => $this->valor_cheque,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'tipo_pessoa', $this->tipo_pessoa])
            ->andFilterWhere(['like', 'ident_nf', $this->ident_nf])
            ->andFilterWhere(['like', 'nf', $this->nf])
            ->andFilterWhere(['like', 'ident_cheque', $this->ident_cheque])
            ->andFilterWhere(['like', 'favorecido', $this->favorecido])
            ->andFilterWhere(['like', 'cnpj_cpf', $this->cnpj_cpf])
            ->andFilterWhere(['like', 'comprovante', $this->comprovante])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.nome', $this->nomeRubrica]);

        return $dataProvider;
    }

    public function searchByRubrica($params, $rubrica)
    {
        $projeto_id = Yii::$app->request->get('idProjeto');
        $query = ContProjDespesas::find()->select("j17_contproj_despesas.data_emissao AS data, j17_contproj_despesas.descricao AS descricao, 
            j17_contproj_despesas.valor_despesa AS total")
            ->leftJoin("j17_contproj_rubricasdeprojetos","j17_contproj_despesas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
            ->where("j17_contproj_rubricasdeprojetos.id =$rubrica")->orderBy("data");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['total'] = [
            'asc' => ['j17_contproj_despesas.valor_despesa' => SORT_ASC],
            'desc' => ['j17_contproj_despesas.valor_despesa' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['data'] = [
            'asc' => ['j17_contproj_despesas.data_emissao' => SORT_ASC],
            'desc' => ['j17_contproj_despesas.data_emissao' => SORT_DESC],
        ];
        return $dataProvider;
    }


}
