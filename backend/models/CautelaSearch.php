<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Cautela;
use yii\base\Object;
use backend\models\Equipamento;
use backend\models\ContProjProjetos;

/**
 * CautelaSearch represents the model behind the search form of `backend\models\Cautela`.
 */
class CautelaSearch extends Cautela
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCautela', 'idEquipamento', 'idProjeto'], 'integer'],
            [['NomeResponsavel', 'OrigemCautela', 'DataDevolucao', 'Email', 'ValidadeCautela', 'TelefoneResponsavel', 'ImagemCautela', 'Equipamento', 'StatusCautela', 'nomeProjeto', 'dataInicial'], 'safe'],
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
        $query = Cautela::find();//->with(['cautelatemprojeto']);

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
            'idCautela' => $this->idCautela,
            //'idEquipamento' => $this->idEquipamento,
            'idProjeto' => $this->idProjeto,
        ]);

        $query->andFilterWhere(['like', 'NomeResponsavel', $this->NomeResponsavel])
            ->andFilterWhere(['like', 'OrigemCautela', $this->OrigemCautela])
            ->andFilterWhere(['like', 'DataDevolucao', $this->DataDevolucao])
            ->andFilterWhere(['like', 'Email', $this->Email])
            ->andFilterWhere(['like', 'ValidadeCautela', $this->ValidadeCautela])
            ->andFilterWhere(['like', 'TelefoneResponsavel', $this->TelefoneResponsavel])
            ->andFilterWhere(['like', 'ImagemCautela', $this->ImagemCautela])
            //->andFilterWhere(['like', 'Equipamento', $this->Equipamento])
            ->andFilterWhere(['like', 'StatusCautela', $this->StatusCautela])
            ->andFilterWhere(['like', 'dataInicial', $this->dataInicial]);
        	
        	//->andFilterWhere(['like', 'nomeEquipamento', $this->nomeEquipamento])
        	if(trim($this->nomeProjeto)!=='')
        	$query->joinWith('cautelatemprojeto', true, 'INNER JOIN')->andFilterWhere(['like', 'nomeProjeto', $this->Equipamento])->all();
        	
        	//(['like', 'ContProjProjetos.nomeProjeto', $this->nomeProjeto]);
         //Filtro de Equipamentos pelo Nome
        if(trim($this->Equipamento)!==''){
        	$equips = Equipamento::find()->where(['like', 'NomeEquipamento',$this->Equipamento])->all();
        	$idsEquip = [];
	        foreach($equips as $eq){
	        	$idsEquip[] = $eq->idEquipamento;
	        }
	    	$query->andFilterWhere(['idEquipamento' => $idsEquip]);
        }
        /*
        //Filtro de Projetos pelo Nome
        if(trim($this->nomeProjeto)!==''){
        	$projs = ContProjProjetos::find()->where(['like', 'nomeprojeto',$this->nomeProjeto])->all();
        	$idsProj = [];
        	foreach($projs as $proj){
        		$idsProj[] = $proj->id;
        	}
        	$query->andFilterWhere(['idProjeto' => $idsProj]);
        }*/
        
        return $dataProvider;
    }
}
