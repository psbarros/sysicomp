<?php

namespace backend\controllers;

use Yii;
use app\models\Banca;
use yii\filters\AccessControl;
use app\models\BancaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BancaController implements the CRUD actions for Banca model.
 */
class BancaController extends Controller
{
    /**
     * @inheritdoc
     */
   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->checarAcesso('secretaria');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'deletesecretaria' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all Banca models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BancaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,0);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banca model.
     * @param integer $banca_id
     * @param integer $membrosbanca_id
     * @return mixed
     */
    public function actionView($banca_id, $membrosbanca_id)
    {
        $model = $this->findModel($banca_id, $membrosbanca_id);
        $model_banca = new BancaSearch();
        $dataProvider = $model_banca->search(Yii::$app->request->queryParams,$model->banca_id);
        return $this->render('view', [
            'model' => $model,
            'dataProvider'=> $dataProvider,
        ]);
    }

    /**
     * Creates a new Banca model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Banca();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'banca_id' => $model->banca_id, 'membrosbanca_id' => $model->membrosbanca_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Banca model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $banca_id
     * @param integer $membrosbanca_id
     * @return mixed
     */
    public function actionUpdate($banca_id, $membrosbanca_id)
    {
        $model = $this->findModel($banca_id, $membrosbanca_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'banca_id' => $model->banca_id, 'membrosbanca_id' => $model->membrosbanca_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Banca model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $banca_id
     * @param integer $membrosbanca_id
     * @return mixed
     */
    public function actionDelete($banca_id, $membrosbanca_id)
    {
        $this->findModel($banca_id, $membrosbanca_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Banca model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $banca_id
     * @param integer $membrosbanca_id
     * @return Banca the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($banca_id, $membrosbanca_id)
    {
        if (($model = Banca::findOne(['banca_id' => $banca_id, 'membrosbanca_id' => $membrosbanca_id])) !== null) {
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
