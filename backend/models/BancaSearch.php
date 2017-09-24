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
            [['nome','funcao', 'passagem','titulo', 'nome_aluno'], 'safe'],
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

    if($idBanca!=0){
       $query = Banca::find()->select("j17_banca_has_membrosbanca.banca_id as banca_id , j17_banca_has_membrosbanca.membrosbanca_id as membrosbanca_id, j17_membrosbanca.nome as nome, j17_banca_has_membrosbanca.funcao as funcao, j17_banca_has_membrosbanca.passagem as passagem, j17_defesa.titulo as titulo, j17_aluno.nome as nome_aluno, j17_membrosbanca.filiacao as membro_filiacao ")->where("j17_banca_has_membrosbanca.banca_id = ".$idBanca)
            ->innerJoin("j17_membrosbanca","j17_membrosbanca.id = j17_banca_has_membrosbanca.membrosbanca_id")->innerJoin("j17_defesa","j17_defesa.banca_id = j17_banca_has_membrosbanca.banca_id")->innerJoin("j17_aluno","j17_aluno.id = j17_defesa.aluno_id")->orderBy(['banca_id'=>SORT_DESC]);
    }else{
            $query = Banca::find()->select("j17_banca_has_membrosbanca.banca_id as banca_id , j17_banca_has_membrosbanca.membrosbanca_id as membrosbanca_id, j17_membrosbanca.nome as nome, j17_banca_has_membrosbanca.funcao as funcao, j17_banca_has_membrosbanca.passagem as passagem, j17_defesa.titulo as titulo, j17_aluno.nome as nome_aluno")->where("j17_banca_has_membrosbanca.funcao = 'P'")
                ->innerJoin("j17_membrosbanca","j17_banca_has_membrosbanca.membrosbanca_id = j17_membrosbanca.id")->innerJoin("j17_defesa","j17_defesa.banca_id = j17_banca_has_membrosbanca.banca_id")->innerJoin("j17_aluno","j17_aluno.id = j17_defesa.aluno_id")->orderBy(['banca_id'=>SORT_DESC]);           

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

        // grid filtering conditions
            $query->andFilterWhere([
            'banca_id' => $this->banca_id,
            'membrosbanca_id' => $this->membrosbanca_id,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
        ->andFilterWhere(['like', 'titulo', $this->titulo])
        ->andFilterWhere(['like', 'nome_aluno', $this->nome_aluno])
        ->andFilterWhere(['like', 'funcao', $this->funcao])
            ->andFilterWhere(['like', 'passagem', $this->passagem]);


        return $dataProvider;
    }

    public function searchMembros($params,$idMembrosBanca)
    {

             $query = Banca::find()->select("j17_banca_has_membrosbanca.banca_id as banca_id , j17_banca_has_membrosbanca.membrosbanca_id as membrosbanca_id, j17_membrosbanca.nome as nome, j17_banca_has_membrosbanca.funcao as funcao, j17_banca_has_membrosbanca.passagem as passagem, j17_membrosbanca.filiacao as membro_filiacao ")->where("j17_banca_has_membrosbanca.banca_id
              = ".$idMembrosBanca)
            ->innerJoin("j17_membrosbanca","j17_membrosbanca.id = j17_banca_has_membrosbanca.membrosbanca_id")->orderBy(['banca_id'=>SORT_DESC]);

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

        $query->andFilterWhere(['like', 'nome', $this->nome])
        ->andFilterWhere(['like', 'funcao', $this->funcao])
            ->andFilterWhere(['like', 'passagem', $this->passagem]);

        return $dataProvider;
    }

}
