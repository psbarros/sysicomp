<?php

namespace backend\controllers;

use app\models\UploadLattesForm;
use backend\models\ContProjProjetos;
use backend\models\ContProjReceitas;
use backend\models\ContProjRubricas;
use Yii;
use backend\models\ContProjRubricasdeProjetos;
use backend\models\ContProjRubricasdeProjetosSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjRubricasdeProjetosController implements the CRUD actions for ContProjRubricasdeProjetos model.
 */
class ContProjRubricasdeProjetosController extends Controller
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
     * Lists all ContProjRubricasdeProjetos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $idProjeto = Yii::$app->request->get("idProjeto");
        $searchModel = new ContProjRubricasdeProjetosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idProjeto' => $idProjeto,
        ]);
    }


    public function actionConsultar()
    {
        $rubricas = ArrayHelper::map(ContProjRubricas::find()->all(), 'id', 'nome');
        $model = new ContProjRubricasdeProjetos();
        if ($model->load(Yii::$app->request->post())) {
            $searchModel = new ContProjRubricasdeProjetosSearch();
            $dataProvider = $searchModel->searchByRubrica(Yii::$app->request->queryParams, $model);
            return $this->render('ConsultarView', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'rubricas' => $rubricas,
            ]);
        } else {
            return $this->render('consultarSaldo', [
                'model' => $model,
                'rubricas' => $rubricas,
            ]);
        }
    }

    public function actionCancelar($idProjeto)
    {
        $this->mensagens('error', 'Cadastro da Rubrica', 'O cadastro da Rubrica foi cancelado!');
        return $this->redirect(['index', 'idProjeto' => $idProjeto]);
    }

    /**
     * Displays a single ContProjRubricasdeProjetos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $idProjeto = Yii::$app->request->get("idProjeto");
        $nomeProjeto = Yii::$app->request->get("nomeProjeto");
        return $this->render('view', [
            'model' => $this->findModel($id),
            'idProjeto' => $idProjeto,
            'nomeProjeto' => $nomeProjeto,
        ]);
    }

    public function cadastrarReceita(ContProjRubricasdeProjetos $model)
    {

        if ($model->valor_disponivel > 0) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $projeto = ContProjProjetos::find()->select("*")->where("id=$model->projeto_id")->one();
                $projeto->saldo = $projeto->saldo + $model->valor_disponivel;
                $projeto->save(false);
                $receita = new ContProjReceitas();
                $receita->rubricasdeprojetos_id = $model->id;
                $receita->ordem_bancaria = $model->ordem_bancaria;
                $receita->descricao = $model->descricao;
                $receita->valor_receita = $model->valor_disponivel;
                $receita->data = date("Y:m:d");
                $receita->save(false);
                $transaction->commit();
                return true;

            } catch (Exception $e) {
                Yii::error($e->getMessage());
                $transaction->rollBack();
                return false;
            }

        }
        return $model->save();
    }


    /**
     * Creates a new ContProjRubricasdeProjetos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new ContProjRubricasdeProjetos();
        $idProjeto = Yii::$app->request->get('idProjeto');
        $rubricas = ArrayHelper::map(ContProjRubricas::find()->all(), 'id', 'nome');
        $model->valor_gasto = 0.00;
        $model->valor_disponivel = 0.00;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($this->cadastrarReceita($model)) {
                $this->mensagens('success', 'Cadastrar Rubrica', 'Rubrica cadastrada com sucesso!');
                return $this->redirect(['index', 'id' => $model->id, 'idProjeto' => $idProjeto,]);
            } else {
                $this->mensagens('error', 'Cadastrar Rubrica', 'Problemas ocorreram na hora do cadastro da Rubrica!');
                return $this->render('create', [
                    'model' => $model,
                    'rubricas' => $rubricas,
                    'idProjeto' => $idProjeto,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'rubricas' => $rubricas,
                'idProjeto' => $idProjeto,
            ]);
        }
    }


    public function atualizarReceita(ContProjRubricasdeProjetos $model, ContProjReceitas $receita)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $receita->descricao = $model->descricao;
            $receita->ordem_bancaria = $model->ordem_bancaria;
            $receita->save(false);
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;
        }

    }

    /**
     * Updates an existing ContProjRubricasdeProjetos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $receita = ContProjReceitas::find()->where("rubricasdeprojetos_id=$model->id")->one();
        $model->ordem_bancaria =  $receita->ordem_bancaria;
        $idProjeto = Yii::$app->request->get("idProjeto");
        $rubricas = ArrayHelper::map(ContProjRubricas::find()->orderBy('')->all(), 'id', 'nome');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->atualizarReceita($model,$receita);
            $this->mensagens('success', 'Atualizar Informações da Rubrica', 'Dados da Rubrica foram atualizados com sucesso!');
            return $this->redirect(['index', 'id' => $model->id, 'idProjeto' => $idProjeto,]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'rubricas' => $rubricas,
                'idProjeto' => $idProjeto,
            ]);
        }
    }


    /**
     * Deletes an existing ContProjRubricasdeProjetos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $detalhe = false)
    {
        $model = $this->findModel($id);
        try {
            $model->delete();
            $this->mensagens('success', 'Excluir Rubrica', 'Rubrica excluido com sucesso!');
        } catch (\yii\base\Exception $e) {
            $this->mensagens('error', 'Excluir Rubrica', 'Projeto não pode ser excluido pois existem rubricas associadas ao projeto!');
            if ($detalhe) {
                return $this->redirect(['view', 'id' => $model->id, 'idProjeto' => $model->projeto_id]);
            } else {
                return $this->redirect(['index', 'idProjeto' => $model->projeto_id]);
            }
        }
        return $this->redirect(['index', 'idProjeto' => $model->projeto_id]);

    }

    /**
     * Finds the ContProjRubricasdeProjetos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjRubricasdeProjetos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = ContProjRubricasdeProjetos::findOne($id)) !== null) {
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
