<?php

namespace backend\controllers;

use backend\models\ContProjReceitas;
use backend\models\ContProjRubricas;
use backend\models\ContProjRubricasdeProjetos;
use backend\models\ContProjRubricasdeProjetosSearch;
use Yii;
use backend\models\ContProjTransferenciasSaldoRubricas;
use backend\models\ContProjTransferenciasSaldoRubricasSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjTransferenciasSaldoRubricasController implements the CRUD actions for ContProjTransferenciasSaldoRubricas model.
 */
class ContProjTransferenciasSaldoRubricasController extends Controller
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
     * Lists all ContProjTransferenciasSaldoRubricas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $idProjeto = Yii::$app->request->get("idProjeto");
        $searchModel = new ContProjTransferenciasSaldoRubricasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idProjeto' => $idProjeto,
        ]);
    }


    public function actionCancelar($idProjeto){
        $this->mensagens('error', 'Transferencia de Saldo entre Rubricas',  'A operação foi cancelado!');
        return $this->redirect(['index','idProjeto' => $idProjeto]);
    }

    /**
     * Displays a single ContProjTransferenciasSaldoRubricas model.
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
     * Creates a new ContProjTransferenciasSaldoRubricas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $idProjeto = Yii::$app->request->get('idProjeto');
        $model = new ContProjTransferenciasSaldoRubricas();
        $rubricas = ArrayHelper::map(ContProjRubricasdeProjetos::find()->where("projeto_id=$idProjeto")->all(), 'id', 'descricao');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data = date('Y-m-d', strtotime($model->data));
            if($this->cadastrar($model)) {
                $this->mensagens('success', 'Transferência de Saldo', 'Nova Transferência cadastrada com sucesso!');
                return $this->redirect(['index',
                    'id' => $model->id,
                    'idProjeto' => $idProjeto
                ]);
            }else{
                $this->mensagens('error', 'Transferência de Saldo', 'Transferência não pode ser concluida!');
                return $this->render('create', [
                    'idProjeto'=>$idProjeto,
                    'model' => $model,
                    'rubricas' => $rubricas,
                ]);
            }
        } else {
            $searchModelRubricas = new ContProjRubricasdeProjetosSearch();
            $dataProviderRubricas = $searchModelRubricas->search(Yii::$app->request->queryParams);

            return $this->render('create', [
                'idProjeto'=>$idProjeto,
                'model' => $model,
                'rubricas' => $rubricas,
                'dataProviderRubricas'=>$dataProviderRubricas,

            ]);
        }
    }

    public function cadastrar($model){

        $origem = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubrica_origem")->one();
        $destino = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubrica_destino")->one();

        $origem->valor_disponivel = $origem->valor_disponivel - $model->valor;
        $destino->valor_disponivel = $destino->valor_disponivel + $model->valor;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($origem->save(false) && $destino->save(false) && $model->save(false)) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }

    public function deletar($model){

        $origem = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubrica_origem")->one();
        $destino = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubrica_destino")->one();

        $origem->valor_disponivel = $origem->valor_disponivel + $model->valor;
        $destino->valor_disponivel = $destino->valor_disponivel - $model->valor;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($origem->save() && $destino->save() && $model->delete()) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * Updates an existing ContProjTransferenciasSaldoRubricas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ContProjTransferenciasSaldoRubricas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$detalhe=false)
    {

        $model = $this->findModel($id);
        try {
            if($this->deletar($model)) {
                $this->mensagens('success', 'Exclusão de Transferência de Saldo', 'Transferência excluída com sucesso!');
            }else{
                throw new Exception('error!');
            }
        } catch (\yii\base\Exception $e) {
            $this->mensagens('error', 'Exclusão de Transferência de Saldo', 'Transferência não pode ser excluida!');
            if ($detalhe) {
                return $this->redirect(['view', 'id' => $model->id,'idProjeto'=> $model->projeto_id]);
            } else {
                return $this->redirect(['index','idProjeto'=> $model->projeto_id]);
            }
        }
        return $this->redirect(['index','idProjeto'=> $model->projeto_id]);
    }

    /**
     * Finds the ContProjTransferenciasSaldoRubricas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjTransferenciasSaldoRubricas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjTransferenciasSaldoRubricas::findOne($id)) !== null) {
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
