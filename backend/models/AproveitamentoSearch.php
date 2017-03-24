<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Aproveitamento;
use app\models\Aluno;
/**
 * AproveitamentoSearch represents the model behind the search form of `app\models\Aproveitamento`.
 */
class AproveitamentoSearch extends Aproveitamento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idAluno'], 'integer'],
            [['codDisciplinaOrigemFK', 'codDisciplinaDestinoFK', 'situacao', 'nomeAluno'], 'safe'],
            [['nota', 'frequencia'], 'number'],
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
        $query = Aproveitamento::find();

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
            'nota' => $this->nota,
            'frequencia' => $this->frequencia,
            'idAluno' => $this->idAluno,
        ]);

        $query->andFilterWhere(['like', 'codDisciplinaOrigemFK', strtolower($this->codDisciplinaOrigemFK)])
            ->andFilterWhere(['like', 'codDisciplinaDestinoFK', strtolower($this->codDisciplinaDestinoFK)])
            ->andFilterWhere(['like', 'situacao', strtolower($this->situacao)]);
        
        //	->andFilterWhere(['like', 'lower(nomeAluno)', strtolower($this->nomeAluno)]);
        if(!empty($this->nomeAluno)){
        	$alunos = Aluno::find()->where(['like', 'lower(nome)',strtolower($this->nomeAluno)])->all();
	        if(count($alunos) > 0){
	        	$idAlunos;
	        	foreach ($alunos as $aluno){
	        		$idAlunos[] = $aluno->id;
	        	}
	        	$query->andFilterWhere(['idAluno'=> $idAlunos]);
	        }
        }
        	

        return $dataProvider;
    }

    public function searchIds($ids)
    {
    	$query = Aproveitamento::find();
    
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);

    	if(count($ids)==1)
    		$query->orFilterWhere([ 'id'=> $ids[0]])
    		->andFilterWhere(['idAluno'=>$this->idAluno]);
    	else
    	foreach($ids as $id){
	    	$query->orFilterWhere([ 'id'=> $id])
	    	->andFilterWhere(['idAluno'=>$this->idAluno]);
    	}

    	return $dataProvider;
    }
    
}
