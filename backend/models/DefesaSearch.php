<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Defesa;

/**
 * DefesaSearch represents the model behind the search form about `app\models\Defesa`.
 */
class DefesaSearch extends Defesa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idDefesa', 'numDefesa', 'reservas_id', 'banca_id', 'aluno_id'], 'integer'],
            [['titulo', 'tipoDefesa', 'data', 'conceito', 'horario', 'local', 'resumo', 'examinador', 'emailExaminador', 'previa', 'nome_aluno', 'curso_aluno', 'idProfessor'], 'safe'],
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
        $query = Defesa::find()->select("j17_aluno.nome as nome_aluno, j17_aluno.curso as curso_aluno , j17_defesa.*")->innerJoin("j17_aluno"," j17_aluno.id = j17_defesa.aluno_id")->orderBy("data DESC");
        
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

        $dataProvider->sort->attributes['nome_aluno'] = [
        'asc' => ['nome_aluno' => SORT_ASC],
        'desc' => ['nome_aluno' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['curso_aluno'] = [
        'asc' => ['curso_aluno' => SORT_ASC],
        'desc' => ['curso_aluno' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'idDefesa' => $this->idDefesa,
            'numDefesa' => $this->numDefesa,
			'j17_aluno.curso' => $this->curso_aluno,
			'tipoDefesa' => $this->tipoDefesa,
            'reservas_id' => $this->reservas_id,
            'banca_id' => $this->banca_id,
            'aluno_id' => $this->aluno_id,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'j17_aluno.nome', $this->nome_aluno])
			->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'conceito', $this->conceito])
            ->andFilterWhere(['like', 'horario', $this->horario])
            ->andFilterWhere(['like', 'local', $this->local])
            ->andFilterWhere(['like', 'resumo', $this->resumo])
            ->andFilterWhere(['like', 'examinador', $this->examinador])
            ->andFilterWhere(['like', 'emailExaminador', $this->emailExaminador]);

        return $dataProvider;
    }

        public function searchPendentes($params)
    {
        $query = Defesa::find()->select("j17_aluno.nome as nome_aluno, j17_aluno.curso as curso_aluno , j17_defesa.*")->innerJoin("j17_aluno"," j17_aluno.id = j17_defesa.aluno_id")->innerJoin("j17_banca_controledefesas as bc","bc.id =  j17_defesa.banca_id")->where("conceito is null AND bc.status_banca = 1");
        
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

        $dataProvider->sort->attributes['nome_aluno'] = [
        'asc' => ['nome_aluno' => SORT_ASC],
        'desc' => ['nome_aluno' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['curso_aluno'] = [
        'asc' => ['curso_aluno' => SORT_ASC],
        'desc' => ['curso_aluno' => SORT_DESC],
        ];

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
    
    public function searchByBancas($params, $bancasIds)
    {
    	$query = Defesa::find()->select("j17_aluno.nome as nome_aluno, j17_aluno.curso as curso_aluno , j17_defesa.*")->innerJoin("j17_aluno"," j17_aluno.id = j17_defesa.aluno_id")->orderBy("data DESC");
    	
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
    	
    	$dataProvider->sort->attributes['nome_aluno'] = [
    			'asc' => ['nome_aluno' => SORT_ASC],
    			'desc' => ['nome_aluno' => SORT_DESC],
    	];
    	
    	$dataProvider->sort->attributes['curso_aluno'] = [
    			'asc' => ['curso_aluno' => SORT_ASC],
    			'desc' => ['curso_aluno' => SORT_DESC],
    	];
    	
    	// grid filtering conditions
    	$query->andFilterWhere([
    			'idDefesa' => $this->idDefesa,
    			'numDefesa' => $this->numDefesa,
    			'j17_aluno.curso' => $this->curso_aluno,
    			'tipoDefesa' => $this->tipoDefesa,
    			'reservas_id' => $this->reservas_id,
    			'banca_id' => $this->banca_id,
    			'aluno_id' => $this->aluno_id,
    	]);
    	
    	$query->andFilterWhere(['like', 'titulo', $this->titulo])
    	->andFilterWhere(['like', 'j17_aluno.nome', $this->nome_aluno])
    	->andFilterWhere(['like', 'data', $this->data])
    	->andFilterWhere(['like', 'conceito', $this->conceito])
    	->andFilterWhere(['like', 'horario', $this->horario])
    	->andFilterWhere(['like', 'local', $this->local])
    	->andFilterWhere(['like', 'resumo', $this->resumo])
    	->andFilterWhere(['like', 'examinador', $this->examinador])
    	->andFilterWhere(['like', 'emailExaminador', $this->emailExaminador])
    	->andFilterWhere(['banca_id'=>$bancasIds]);
    	
    	return $dataProvider;
    }
}
