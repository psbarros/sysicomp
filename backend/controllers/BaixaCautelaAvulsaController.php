<?php

namespace backend\controllers;

use Yii;
use app\models\BaixaCautelaAvulsa;
use app\models\CautelaAvulsa;
use app\models\BaixaCautelaAvulsaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BaixaCautelaAvulsaController implements the CRUD actions for BaixaCautelaAvulsa model.
 */
class BaixaCautelaAvulsaController extends Controller
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
     * Lists all BaixaCautelaAvulsa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BaixaCautelaAvulsaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BaixaCautelaAvulsa model.
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
     * Creates a new BaixaCautelaAvulsa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {

        $cautelaAvulsa = CautelaAvulsa::findOne($id);
        
        $model = new BaixaCautelaAvulsa();

        $model->idCautelaAvulsa = $id;
        
        if ($model->load(Yii::$app->request->post())){
        	try{
        	 if($model->save()){
	            $cautelaAvulsa->StatusCautelaAvulsa = CautelaAvulsa::getStatusConcluida();
	            $cautelaAvulsa->save();
	            return $this->redirect(['view', 'id' => $model->idBaixaCautelaAvulsa]);
        	 }
        	}catch (yii\db\Exception $Erro){
        		//Esperado ser violação de chave unique (idCautelaAvulsa)
        		$this->mensagens('warning', 'Cautela Inativa', 'Já foi dado baixa para esta cautela anteriormente.');
        		return $this->redirect(['cautela-avulsa/view2', 'id' => $id]);        		
        	}        		
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BaixaCautelaAvulsa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	print_r($model->DataDevolucao);
            return $this->redirect(['view', 'id' => $model->idBaixaCautelaAvulsa]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BaixaCautelaAvulsa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionRevert($idCautelaAvulsa){
    	
    	$cautela = CautelaAvulsa::findOne($idCautelaAvulsa);
    	
    	if(!$cautela->baixaReversivel){
    		//Double-Check para Reversão da Cautela..
    		$this->mensagens('warning', 'Reversão NÃO realizada', 'Esta baixa não pode ser revertida. Contate o admnistrador.');
    		return $this->redirect(['cautela-avulsa/view2', 'id'=>$idCautelaAvulsa]);
    	}else{
    		if($cautela->cautelaAvulsaTemBaixa->delete() !== false){
    			//Atribui o Status adequado..
    			$cautela->StatusCautelaAvulsa = $cautela->ajustaStatus;
    			
    			if($cautela->save()){
    				$this->mensagens('success', 'Revertido', 'A cautela agora está '.$cautela->StatusCautelaAvulsa);
    			}else{
    				$this->mensagens('danger', "Erro na Reversão", "Não foi possível reverter a baixa desta cautela. Contate o administrador.");	
    				return $this->redirect(['cautela-avulsa/view2', 'id'=>$idCautelaAvulsa]);
    			}
    		}else{
    			$this->mensagens('danger', 'Reversão NÃO realizada', 'Há um problema para reverter deletar esta baixa. Contate o admnistrador.');
    			return $this->redirect(['cautela/view', 'id'=>$idCautela]);
    		}
    		
    	}
    	
    	return $this->redirect(['cautela-avulsa/view2', 'id'=>$idCautelaAvulsa]);
    }
    /**
     * Finds the BaixaCautelaAvulsa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaixaCautelaAvulsa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BaixaCautelaAvulsa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
