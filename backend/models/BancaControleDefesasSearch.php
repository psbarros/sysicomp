<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BancaControleDefesas;

/**
 * BancaControleDefesasSearch represents the model behind the search form about `app\models\BancaControleDefesas`.
 */
class BancaControleDefesasSearch extends BancaControleDefesas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_banca'], 'integer'],
            [['justificativa', 'aluno_nome'], 'safe'],
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

        if(!isset($_GET["status"]) ){
            $where = "status_banca is null";
        }
        else if ($_GET["status"] == 1){
            $where = "status_banca = 1";
        }
        else if ($_GET["status"] == 0){
            $where = "status_banca = 0";
        }

        $query = BancaControleDefesas::find()->select("bcd.*, d.*, a.nome as aluno_nome, l.sigla as linhaSigla, if(a.curso = 1, ('Mestrado'),('Doutorado')) as cursoAluno")->where($where)->alias("bcd")->innerJoin("j17_defesa as d","d.banca_id = bcd.id")->innerJoin("j17_aluno as a","d.aluno_id = a.id")
            ->innerJoin("j17_linhaspesquisa as l","l.id = a.area");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->attributes['aluno_nome'] = [
        'asc' => ['aluno_nome' => SORT_ASC],
        'desc' => ['aluno_nome' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['linhaSigla'] = [
        'asc' => ['linhaSigla' => SORT_ASC],
        'desc' => ['linhaSigla' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['cursoAluno'] = [
        'asc' => ['cursoAluno' => SORT_ASC],
        'desc' => ['cursoAluno' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status_banca' => $this->status_banca,
        ]);

        $query->andFilterWhere(['like', 'justificativa', $this->justificativa]);

        return $dataProvider;
    }
}
