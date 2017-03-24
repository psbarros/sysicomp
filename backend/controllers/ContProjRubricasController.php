<?php

namespace backend\controllers;

use Yii;
use backend\models\ContProjRubricas;
use backend\models\ContProjRubricasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjRubricasController implements the CRUD actions for ContProjRubricas model.
 */
class ContProjRubricasController extends Controller
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
     * Lists all ContProjRubricas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjRubricasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContProjRubricas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCancelar(){
        $this->mensagens('error', 'Rubricas',  'Operação cancelada!');
        return $this->redirect(['index']);
    }


    /**
     * Creates a new ContProjRubricas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContProjRubricas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->mensagens('success', 'Rubricas',  'Rubrica cadastrado com sucesso!');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ContProjRubricas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->mensagens('success', 'Rubricas',  'Dados atualizados com sucesso!');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ContProjRubricas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$detalhe=false)
    {
        $model = $this->findModel($id);
        try{
            $model->delete();
            $this->mensagens('success', 'Exclusão da Rubrica',  'Rubrica excluida com sucesso!');
        }catch (\yii\base\Exception $e){
            $this->mensagens('error', 'Exclusão da Rubrica', 'Rubrica não pode ser excluido!');
            if($detalhe){
                return $this->redirect(['view','id' => $model->id]);
            }else{
                return $this->redirect(['index']);
            }
            // $this->goBack();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the ContProjRubricas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjRubricas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjRubricas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

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
