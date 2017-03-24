<?php

namespace backend\controllers;

use backend\models\ContProjProjetos;
use backend\models\ContProjRubricasdeProjetos;
use Yii;
use backend\models\ContProjReceitas;
use backend\models\ContProjReceitasSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjReceitasController implements the CRUD actions for ContProjReceitas model.
 */
class ContProjReceitasController extends Controller
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
     * Lists all ContProjReceitas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjReceitasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $receita= $this->calcularRreceita();
        return $this->render('index', [
            'receita' => $receita,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function calcularRreceita(){
        $receita = 0;

        return $receita;
    }
    /**
     * Displays a single ContProjReceitas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $nomeProjeto = Yii::$app->request->get('nomeProjeto');
        $idProjeto = Yii::$app->request->get('idProjeto');
        $data = Yii::$app->request->get('data');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'idProjeto'=> $idProjeto,
            'nomeProjeto' => $nomeProjeto,
        ]);
    }

    /**
     * Creates a new ContProjReceitas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function titulo()
    {
        return $this->descricao;
    }

    public function cadastrarReceita(ContProjReceitas $model){

        $model->data = date('Y-m-d', strtotime($model->data));

        $rubrica = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubricasdeprojetos_id")->one();
        $rubrica->valor_disponivel =  $rubrica->valor_disponivel + $model->valor_receita;

        $projeto = ContProjProjetos::find()->select("*")->where("id=$rubrica->projeto_id")->one();
        $projeto->saldo =  $projeto->saldo + $model->valor_receita;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($projeto->save() && $model->save() && $rubrica->save()) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
        $transaction->rollBack();
        return false;
    }



    public function actionCreate()
    {
        $model = new ContProjReceitas();

        $idProjeto= Yii::$app->request->get('idProjeto');

        if ($model->load(Yii::$app->request->post()) && $this->cadastrarReceita($model) ) {
            $this->mensagens('success', 'Receita', 'Receita cadastrada com sucesso!');
            return $this->redirect(['index', 'id' => $model->id,'idProjeto' => $idProjeto]);
        } else {
            $rubricasdeProj = ContProjRubricasdeProjetos::find()->select(["j17_contproj_rubricasdeprojetos.id",
                "CONCAT_WS(':',j17_contproj_rubricas.tipo,j17_contproj_rubricas.nome, j17_contproj_rubricasdeprojetos.valor_total) 
                AS descricao"])
                ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricasdeprojetos.rubrica_id = j17_contproj_rubricas.id")
                ->where("j17_contproj_rubricasdeprojetos.projeto_id = $idProjeto")->all();
            $rubricasdeProjeto = ArrayHelper::map($rubricasdeProj, 'id', 'descricao');
            return $this->render('create', [
                'rubricasdeProjeto' => $rubricasdeProjeto,
                'model' => $model,
                'idProjeto' => $idProjeto,
            ]);
        }
    }

    /**
     * Updates an existing ContProjReceitas model.
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

    public function deletar($model){

        $rubricaProjeto = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubricasdeprojetos_id")->one();
        $rubricaProjeto->valor_disponivel =  $rubricaProjeto->valor_disponivel - $model->valor_receita;

        $projeto = ContProjProjetos::find()->select("*")->where("id=$rubricaProjeto->projeto_id")->one();
        $projeto->saldo = $projeto->saldo - $model->valor_receita;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ( $rubricaProjeto->save() && $projeto->save() && $model->delete()) {
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
     * Deletes an existing ContProjReceitas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$detalhe=false)
    {
        $idProjeto = Yii::$app->request->get('idProjeto');
        $model = $this->findModel($id);
        try {
            if($this->deletar($model)) {
                $this->mensagens('success', 'Exclusão da Receita', 'Receita excluída com sucesso!');
            }else{
                throw new Exception('error!');
            }
        } catch (\yii\base\Exception $e) {
            $this->mensagens('error', 'Exclusão da Receita', 'Receita não pode ser excluida!');
            if ($detalhe) {
                return $this->redirect(['view', 'id' => $model->id,'idProjeto'=> $idProjeto]);
            } else {
                return $this->redirect(['index','idProjeto'=> $idProjeto]);
            }
        }
        return $this->redirect(['index','idProjeto'=> $idProjeto]);


    }

    /**
     * Finds the ContProjReceitas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjReceitas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjReceitas::findOne($id)) !== null) {
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
