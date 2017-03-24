<?php

namespace backend\controllers;

use backend\models\ContProjProjetos;
use Yii;
use backend\models\ContProjProrrogacoes;
use backend\models\ContProjProrrogacoesSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjProrrogacoesController implements the CRUD actions for ContProjProrrogacoes model.
 */
class ContProjProrrogacoesController extends Controller
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
     * Lists all ContProjProrrogacoes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjProrrogacoesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContProjProrrogacoes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function dataFinalAlterada($idProjeto, ContProjProrrogacoes $model){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->data_fim_alterada = date('Y-m-d', strtotime($model->data_fim_alterada));
            $projeto = ContProjProjetos::find()->select("*")->where("id=$idProjeto")->one();
            $projeto->data_fim_alterada = $model->data_fim_alterada;
            //$projeto->data_fim=  $model->data_fim_alterada;
            $projeto->save(false);
            $model->save(false);
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * Creates a new ContProjProrrogacoes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $idProjeto = Yii::$app->request->get('idProjeto');
        $model = new ContProjProrrogacoes();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data_fim_alterada = date('Y-m-d', strtotime($model->data_fim_alterada));
            $this->dataFinalAlterada($idProjeto,$model);
            $this->mensagens('success', 'Data Final', 'Nova data final cadastrada com sucesso!');
            return $this->redirect(['index', 'idProjeto' => $idProjeto]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'idProjeto'=> $idProjeto,
            ]);
        }
    }

    /**
     * Updates an existing ContProjProrrogacoes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $idProjeto = Yii::$app->request->get('idProjeto');
        $model = $this->findModel($id);
        $model->data_fim_alterada = date('d-m-Y', strtotime($model->data_fim_alterada));
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->dataFinalAlterada($idProjeto,$model);
            $this->mensagens('success', 'Data Final', 'Data final editada com sucesso!');
            return $this->redirect(['index','idProjeto'=> $idProjeto]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'idProjeto'=> $idProjeto,
            ]);
        }
    }

    /**
     * Deletes an existing ContProjProrrogacoes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    function debug_to_console( $data ) {

        if ( is_array( $data ) )
            $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
        else
            $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

        echo $output;
    }


    public function actionDelete($id)
    {
        $idProjeto = Yii::$app->request->get('idProjeto');
        $this->findModel($id)->delete();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $data_fim_alterada = ContProjProrrogacoes::find()->select("data_fim_alterada")->max('data_Fim_alterada');
            $this->debug_to_console($data_fim_alterada);
            if ($data_fim_alterada == null) {
                $projeto = ContProjProjetos::find()->select("*")->where("id=$idProjeto")->one();
                $projeto->data_fim_alterada = $projeto->data_fim;

            } else {
                $projeto = ContProjProjetos::find()->select("*")->where("id=$idProjeto")->one();
                $projeto->data_fim_alterada = $data_fim_alterada;
            }
            $projeto->save(false);
            $transaction->commit();
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $transaction->rollBack();
        }
        $this->mensagens('success', 'Exclusão da Data', 'Exclusão bem sucedida!');
        return $this->redirect(['index','idProjeto'=> $idProjeto]);
    }

    /**
     * Finds the ContProjProrrogacoes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjProrrogacoes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjProrrogacoes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function mensagens($tipo, $titulo, $mensagem)
    {
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
