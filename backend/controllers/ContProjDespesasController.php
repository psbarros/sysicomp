<?php

namespace backend\controllers;

use backend\models\ContProjBancos;
use backend\models\ContProjProjetos;
use backend\models\ContProjRubricas;
use backend\models\ContProjRubricasdeProjetos;
use Yii;
use backend\models\ContProjDespesas;
use backend\models\ContProjDespesasSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ContProjDespesasController implements the CRUD actions for ContProjDespesas model.
 */
class ContProjDespesasController extends Controller
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
     * Lists all ContProjDespesas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjDespesasSearch();
        $searchModelCusteio = new ContProjDespesasSearch();
        $dataProviderCapital = $searchModel->search(Yii::$app->request->queryParams,"Capital");
        $dataProviderCusteio = $searchModel->search(Yii::$app->request->queryParams,"Custeio");

        return $this->render('index', [
            'searchModel' => $searchModel,
            'searchModelCusteio' => $searchModelCusteio,
            'dataProviderCapital' => $dataProviderCapital,
            'dataProviderCusteio' => $dataProviderCusteio,
        ]);
    }

    /**
     * Displays a single ContProjDespesas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $idProjeto= Yii::$app->request->get('idProjeto');
        $rubricasdeProj = \backend\models\ContProjRubricasdeProjetos::find()->select(["j17_contproj_rubricasdeprojetos.id",
            "CONCAT_WS(' - ',j17_contproj_rubricas.tipo,j17_contproj_rubricas.nome, j17_contproj_rubricasdeprojetos.descricao ) 
                AS descricao"])
            ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricasdeprojetos.rubrica_id = j17_contproj_rubricas.id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id = $idProjeto")->all();
        $rubricasDeProjeto = \yii\helpers\ArrayHelper::map($rubricasdeProj, 'id', 'descricao');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'rubricasDeProjeto'=> $rubricasDeProjeto,
        ]);
    }


    public function cadastrarDespesa(ContProjDespesas $model){

        $model->valor_despesa = $model->quantidade * $model->valor_unitario;
        $model->data_emissao = date('Y-m-d', strtotime($model->data_emissao));
        $model->data_emissao_cheque = date('Y-m-d', strtotime($model->data_emissao_cheque));

        $rubrica = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubricasdeprojetos_id")->one();
        $rubrica->valor_disponivel =  $rubrica->valor_disponivel - $model->valor_despesa;
        $rubrica->valor_gasto =  $rubrica->valor_gasto + $model->valor_despesa;

        $projeto = ContProjProjetos::find()->select("*")->where("id=$rubrica->projeto_id")->one();
        $projeto->saldo =  $projeto->saldo - $model->valor_despesa;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($projeto->save(false) && $model->save(false) && $rubrica->save(false)) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
        $transaction->rollBack();
        return false;
    }


    /**
     * Creates a new ContProjDespesas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $idProjeto= Yii::$app->request->get('idProjeto');
        $model = new ContProjDespesas();
        $model->comprovanteArquivo = UploadedFile::getInstance($model, 'comprovanteArquivo');
        if($model->comprovanteArquivo) {
            $model->comprovante = "uploads/despesas/".date('dmYhms')."_".$model->comprovanteArquivo->name;
            $model->comprovanteArquivo->saveAs($model->comprovante);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->cadastrarDespesa($model);
            $this->mensagens('success', 'Despesa', 'Despesa cadastrada com sucesso!');
            return $this->redirect(['index', 'idProjeto' => $idProjeto]);
        } else {
            $rubricasdeProj = ContProjRubricasdeProjetos::find()->select(["j17_contproj_rubricasdeprojetos.id",
                "CONCAT_WS(': ',j17_contproj_rubricas.tipo,j17_contproj_rubricas.nome, j17_contproj_rubricasdeprojetos.valor_total) 
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
     * Updates an existing ContProjDespesas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $idProjeto= Yii::$app->request->get('idProjeto');
        $model  = $this->findModel($id);
        $model->data_emissao_cheque = date("d-m-Y", strtotime($model->data_emissao_cheque));
        $model->data_emissao = date("d-m-Y", strtotime($model->data_emissao));
        $model2 = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $this->atualizarDespesa($model2,$model);
            return $this->redirect(['index',
                'id' => $model->id,
                'idProjeto'=>$idProjeto,
            ]);

        } else {
            $rubricasdeProj = ContProjRubricasdeProjetos::find()->select(["j17_contproj_rubricasdeprojetos.id",
                "CONCAT_WS(':',j17_contproj_rubricas.tipo,j17_contproj_rubricas.nome, j17_contproj_rubricasdeprojetos.valor_total) 
                AS descricao"])
                ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricasdeprojetos.rubrica_id = j17_contproj_rubricas.id")
                ->where("j17_contproj_rubricasdeprojetos.projeto_id = $idProjeto")->all();
            $rubricasDeProjeto = ArrayHelper::map($rubricasdeProj, 'id', 'descricao');

            return $this->render('update', [
                'model' => $model,
                'rubricasDeProjeto'=>$rubricasDeProjeto,
            ]);
        }
    }


    public function atualizarDespesa(ContProjDespesas $model2,ContProjDespesas $model){

        $model->valor_despesa = $model->quantidade * $model->valor_unitario;
        $model->data_emissao = date('Y-m-d', strtotime($model->data_emissao));
        $model->data_emissao_cheque = date('Y-m-d', strtotime($model->data_emissao_cheque));

        $transaction = Yii::$app->db->beginTransaction();
        try {

            $rubrica = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubricasdeprojetos_id")->one();
            $rubrica->valor_disponivel =  $rubrica->valor_disponivel + $model2->valor_despesa;
            $rubrica->valor_disponivel =  $rubrica->valor_disponivel - $model->valor_despesa;

            $rubrica->valor_gasto =  $rubrica->valor_gasto - $model2->valor_despesa;
            $rubrica->valor_gasto =  $rubrica->valor_gasto + $model->valor_despesa;

            $projeto = ContProjProjetos::find()->select("*")->where("id=$rubrica->projeto_id")->one();
            $projeto->saldo =  $projeto->saldo + $model2->valor_despesa;
            $projeto->saldo =  $projeto->saldo - $model->valor_despesa;

            if ($projeto->save(false) && $model->save(false) && $rubrica->save(false)) {
                $transaction->commit();
                return true;
            }

        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }

        $transaction->rollBack();
        return false;
    }



    public function deletar(ContProjDespesas $model){

        $rubricaProjeto = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubricasdeprojetos_id")->one();
        $rubricaProjeto->valor_disponivel =  $rubricaProjeto->valor_disponivel + $model->valor_despesa;
        $rubricaProjeto->valor_gasto =  $rubricaProjeto->valor_gasto - $model->valor_despesa;

        $projeto = ContProjProjetos::find()->select("*")->where("id=$rubricaProjeto->projeto_id")->one();
        $projeto->saldo = $projeto->saldo + $model->valor_despesa;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ( $rubricaProjeto->save(false) && $projeto->save(false) && $model->delete()) {
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
     * Deletes an existing ContProjDespesas model.
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
                $this->mensagens('success', 'Exclusão da Despesa', 'Despesa excluída com sucesso!');
            }else{
                throw new Exception('error!');
            }
        } catch (\yii\base\Exception $e) {
            $this->mensagens('error', 'Exclusão da Despesa', 'Despesa não pode ser excluida!');
            if ($detalhe) {
                return $this->redirect(['view', 'id' => $model->id,'idProjeto'=> $idProjeto]);
            } else {
                return $this->redirect(['index','idProjeto'=> $idProjeto]);
            }
        }
        return $this->redirect(['index','idProjeto'=> $idProjeto]);
    }

    /**
     * Finds the ContProjDespesas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjDespesas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjDespesas::findOne($id)) !== null) {
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
