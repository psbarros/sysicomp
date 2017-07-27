<?php

namespace backend\controllers;

use app\models\Candidato;
use backend\models\ContProjProjetos;
use backend\models\Projetos;
use Yii;
use backend\models\ContProjRegistraDatas;
use backend\models\ContProjRegistraDatasSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjRegistraDatasController implements the CRUD actions for ContProjRegistraDatas model.
 */
class ContProjRegistraDatasController extends Controller
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

    public function actionListadatas()
    {

        $ultima_visualizacao = Yii::$app->user->identity->visualizacao_candidatos;
        $projetos = ContProjProjetos::find()->select("*")->where("coordenador_id=" . Yii::$app->user->identity->id)->all();
        for ($i = 0; $i < count($projetos); $i++) {
            $id[] = $projetos[$i]->id;
        }
        if (!empty($id)) {
            $ids=join("','",$id);
            $candidato = ContProjRegistraDatas::find()->where("data = '" . date('Y-m-d') . "' AND projeto_id IN ('$ids')")
                ->orderBy("data")->all();

            //$candidato = ContProjRegistraDatas::find()->all();
            //->where("inicio > '".$ultima_visualizacao."'")
            for ($i = 0; $i < count($candidato); $i++) {
                $a = date("d/m/Y", strtotime($candidato[$i]->data));
                echo "<li><a href='#'>";
                echo "<div class='pull-left'>
                    <img src='/img/candidato.png' class='img-circle'
                    alt='user image'/>
                    </div>";
                echo ("<p>" . "Evento: " .mb_strimwidth($candidato[$i]->observacao,0,15,"...")) . "<br>";
                echo ("<p>" . "Evento: " .$candidato[$i]->evento) . "<br>";
                echo ("Data: " . $a) . "</p></a></li>";
            }
        }

    }

    public function actionQuantidadedatas()
    {
        $ultima_visualizacao = Yii::$app->user->identity->visualizacao_candidatos;
        $projetos = ContProjProjetos::find()->select("*")->where("coordenador_id=" . Yii::$app->user->identity->id)->all();
        for ($i = 0; $i < count($projetos); $i++) {
            $id[] = $projetos[$i]->id;
        }
        if (!empty($id)) {
            $ids=join("','",$id);
            $candidato = ContProjRegistraDatas::find()->where("data = '" . date('Y-m-d') . "' AND projeto_id IN ('$ids')")
                ->orderBy("data")->all();
            echo count($candidato);
        }
    }

    public function actionZerarnotificacaodatas()
    {
        $usuario = new User();
        $usuario = $usuario->findIdentity(Yii::$app->user->identity->id);
        $usuario->visualizacao_candidatos = date("Y-m-d H:i:s");
        $usuario->save();
    }

    /**
     * Lists all ContProjRegistraDatas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjRegistraDatasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$projetos = new ArrayDataProvider([
        //    'allModels' => Projetos::find()->all(),
        //]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContProjRegistraDatas model.
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
     * Creates a new ContProjRegistraDatas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $idProjeto = Yii::$app->request->get('idProjeto');
        $model = new ContProjRegistraDatas();
        $projetos = ArrayHelper::map(ContProjProjetos::find()->orderBy('nomeprojeto')->all(), 'id', 'nomeprojeto');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data = date('Y-m-d', strtotime($model->data));
            $model->save();
            return $this->redirect(['index', 'id' => $model->id,'idProjeto' => $idProjeto]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'projetos' => $projetos,
                'idProjeto' => $idProjeto,
            ]);
        }
    }

    /**
     * Updates an existing ContProjRegistraDatas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $idProjeto= Yii::$app->request->get('idProjeto');
        $model = $this->findModel($id);
        $projetos = ArrayHelper::map(ContProjProjetos::find()->orderBy('nomeprojeto')->all(), 'id', 'nomeprojeto');
        $model->data = date('d-m-Y', strtotime($model->data));
        if ($model->load(Yii::$app->request->post()) ) {
            $model->data = date('Y-m-d', strtotime($model->data));
            $model->save();
            return $this->redirect(['index',
                'id' => $model->id,
                'idProjeto'=>$idProjeto,
                ]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'projetos' => $projetos,
            ]);
        }
    }

    /**
     * Deletes an existing ContProjRegistraDatas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $detalhe = false)
    {
        $idProjeto = Yii::$app->request->get("idProjeto");

        $model = $this->findModel($id);
        try {
            $model->delete();
            $this->mensagens('success', 'Excluir lembrete', 'Excluido com sucesso!');
        } catch (\yii\base\Exception $e) {
            $this->mensagens('error', 'Excluir lembrete', 'Não pode ser excluido!');
            if ($detalhe) {
                return $this->redirect(['view', 'id' => $model->id, 'idProjeto' => $idProjeto]);
            } else {
                return $this->redirect(['index', 'idProjeto' => $idProjeto]);
            }
        }
        return $this->redirect(['index', 'idProjeto' => $idProjeto]);

    }

    /**
     * Finds the ContProjRegistraDatas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjRegistraDatas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjRegistraDatas::findOne($id)) !== null) {
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
