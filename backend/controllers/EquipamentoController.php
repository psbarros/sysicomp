<?php

namespace backend\controllers;

use Yii;
use backend\models\Equipamento;
use app\models\EquipamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\db\Exception;


/**
 * EquipamentoController implements the CRUD actions for Equipamento model.
 */
class EquipamentoController extends Controller
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
     * Lists all Equipamento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EquipamentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Equipamento model.
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
     * Creates a new Equipamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Equipamento();

        //if($tipoEquipamento == "Disponível"){
        if($model->StatusEquipamento == "Disponível"){
            $StatusEquipamento = 1;
        }
        else if($model->StatusEquipamento= "Em uso"){
            $StatusEquipamento = 2;
        }
        else if (  $model->StatusEquipamento = "Descartado"){
            $StatusEquipamento = 3;
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $arq = UploadedFile::getInstance($model, 'ImagemEquipamento');
            if($arq!==null){
            $arquivo = $model->idEquipamento.'-'.$model->NomeEquipamento;
            $arquivo = 'repositorio/equipamentos/'.$arquivo.'.'.$arq->extension;
            $model -> ImagemEquipamento = $arquivo;
            $arq->saveAs($arquivo);
            
            $model->save();
            }
            //$model->url = 'repositorio/'.$arquivo.'.'.$model->ImagemEquipamento->extension;

/*
            if (!$model->save()) {
                print_r($model->getErrors());
                return;
            }
*/
            return $this->redirect(['view', 'id' => $model->idEquipamento]);
            

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Updates an existing Equipamento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);
        
		//$model = new Equipamento();
        if ($model->load(Yii::$app->request->post())) {

            //$model->file = UploadedFile::getInstance($model, 'file');
            //if ($model->file) {
            //    $imagepath = 'equipamento';
            //    $model->ImagemEquipamento= $imagepath .rand(10,100).$model->file->name;
            //}

            $arq = UploadedFile::getInstance($model, 'ImagemEquipamento');
            $oldName = Equipamento::findOne($id);
            $newName = $model->ImagemEquipamento;
            if($arq !== null){
            	$arquivo = $model->idEquipamento.'-'.$model->NomeEquipamento;
            	$arquivo = 'repositorio/equipamentos/'.$arquivo.'.'.$arq->extension;
                $model -> ImagemEquipamento = $arquivo;
                $arq->saveAs($arquivo);
            }else{
            	$model -> ImagemEquipamento = $oldName->ImagemEquipamento;
            }
			
            if ($model->save()) {
                if($model->file){
                    $model->file->saveAs($model->ImagemEquipamento);
                }
            } else {
                print_r($model->getErrors());
                return;
            }

            return $this->redirect(['view', 'id' => $model->idEquipamento]);
            //return $this->redirect(['view', 'id' => $model->idEquipamento]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing Equipamento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	
    	try {
        	$this->findModel($id)->delete();
    	}catch (yii\db\IntegrityException $e){
        	//Adiciona flash do erro
    		$this->mensagens('warning', "Equipamento Não pode ser Deletado", "Este equipamento é referenciado em outro documento.");
    		return $this->redirect(['view', 'id'=>$id]);
    	}
    	
    	return $this->redirect(['index']);
    }

    /**
     * Finds the Equipamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Equipamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Equipamento::findOne($id)) !== null) {
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
