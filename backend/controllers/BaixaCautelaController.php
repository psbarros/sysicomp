<?php

namespace backend\controllers;

use Yii;
use app\models\BaixaCautela;
use backend\models\Cautela;
use app\models\BaixaCautelaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Equipamento;

/**
 * BaixaCautelaController implements the CRUD actions for BaixaCautela model.
 */
class BaixaCautelaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BaixaCautela models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaixaCautelaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BaixaCautela model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BaixaCautela model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idCautela)
    {
        $cautela = Cautela::findOne($idCautela);
		$equipamento = Equipamento::findOne($cautela->idEquipamento);
        $model = new BaixaCautela();
        
        $model->idCautela = $idCautela;
        
        if ($model->load(Yii::$app->request->post())) {
        	$cautela = Cautela::findOne($model->idCautela);
        	$equipamento = Equipamento::findOne($cautela->idEquipamento);
        	
        	if($model->save()){
        	
	            $cautela->StatusCautela = Cautela::getStatusConcluida();
	            if($cautela->save()){
	            }else{
	            	$this->mensagens('danger', 'Cautela Inconistente', 'Contate o administrador.');
	            	return $this->redirect(['view', 'id' => $model->idBaixaCautela]);
	            }
	            
	            $equipamento->StatusEquipamento = Equipamento::getStatusDisponivel();
	            if($equipamento->save()){
	            }else{
	            	$this->mensagens('danger', 'Equipamento Inconsistente', 'Contate o administrador.');
	            	return $this->redirect(['view', 'id' => $model->idBaixaCautela]);
	            }
	            
	            //Salvou tudo que tinha de salvar...
	            $this->mensagens('success', 'Baixa Realizada', 'Baixa Realizada com Sucesso.');
            
        	}else{
        		$this->mensagens('warning', 'Erro ao dar baixa', 'Contate o administrador.');
        		return $this->redirect(['view', 'id' => $model->idBaixaCautela]);
        	}
			//print_r($model->DataDevolucao);
            return $this->redirect(['view', 'id' => $model->idBaixaCautela]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'item' => $cautela,
            ]);
        }
    }

    /**
     * Updates an existing BaixaCautela model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	//print_r($model->DataDevolucao);
            return $this->redirect(['view', 'id' => $model->idBaixaCautela]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BaixaCautela model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BaixaCautela model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaixaCautela the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaixaCautela::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionRevert($idCautela){
    	
    	$cautela = Cautela::findOne($idCautela);
    	//2.1.1 atribuir ao equipamento o estado ‘Em uso’
    	//2.1.2 reverter a baixa implica Deletar o registro da baixa
    	//2.1.3 atribuir status ‘Em aberto’ ou ‘Em atraso’ da cautela referenciada, de acordo
    	
    	//Aqui, caso o usuário não dê F5 no browser no dia seguinte.. ou inconsistencias de migracao de banco..
    	if(!$cautela->baixaReversivel){
    		$this->mensagens('warning', 'Reversão NÃO realizada', 'Esta cautela não pode ser revertida. Contate o admnistrador.');
    		return $this->redirect(['cautela/view', 'id'=>$idCautela]);
    	}else{
    		if($cautela->cautelaTemBaixa->delete() !== false){
    			//Atribui o Status adequado..
    			$cautela->StatusCautela = $cautela->ajustaStatus;
    			$cautela->cautelatemequipamento->StatusEquipamento = Equipamento::getStatusEmUso();
    			if($cautela->cautelatemequipamento->save() && $cautela->save()){
    				$this->mensagens('success', 'Revertido', 'A cautela agora está '.$cautela->StatusCautela);
    			}else{
    				$this->mensagens('danger', "Erro na Reversão", "Contate o administrador. Há uma inconsistêcia nos dados.");	
    				return $this->redirect(['cautela/view', 'id'=>$idCautela]);
    			}
    		}else{
    			$this->mensagens('danger', 'Reversão NÃO realizada', 'Há um problema para reverter deletar esta baixa. Contate o admnistrador.');
    			return $this->redirect(['cautela/view', 'id'=>$idCautela]);
    		}
    		
    	}
    	
    	return $this->redirect(['cautela/view', 'id'=>$idCautela]);
    }
    
    /* Envio de mensagens para views
     Tipo: success, danger, warning*/
    protected function mensagens($tipo, $titulo, $mensagem){
    	Yii::$app->session->setFlash($tipo, [
    			'type' => $tipo,
    			'icon' => 'home',
    			'duration' => 5000,
    			'message' => $mensagem,
    			'title' => $titulo,
    			'positonY' => 'top',
    			'positonX' => 'center',
    			'showProgressbar' => true,
    	]);
    }
}
