<?php

namespace backend\controllers;

use Yii;
use app\models\AgendarDefesa;
use app\models\Aluno;
use app\models\AlunoSearch;
use app\models\AgendarDefesasSearch;
use yii\web\Controller;
use yii\db\IntegrityException;
use app\models\BancaControleDefesas;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use \yii\helpers\ArrayHelper;
use app\models\BancaSearch;
use app\models\BancaControleDefesasSearch;
/**
use yii\filters\VerbFilter;
 * AgendarDefesaController implements the CRUD actions for AgendarDefesa model.
 */
class AgendarDefesaController extends Controller
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
     * Lists all AgendarDefesa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgendarDefesasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AgendarDefesa model.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionView($idDefesa, $aluno_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($idDefesa, $aluno_id),
        ]);
    }

    /**
     * Creates a new AgendarDefesa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AgendarDefesa();
        $bancas =  ArrayHelper::getColumn(BancaControleDefesas::find()->where('status_banca=1')->all(), 'id');
        

        if ($model->load(Yii::$app->request->post())  )
        {
            $idAluno = Yii::$app->request->Post('idAluno');


            if($idAluno!=null) 
            {
                $model->aluno_id=$idAluno;
                $model->save();
                return $this->redirect(['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]);
            }
            else
            {
                return $this-> redirect('alunonaoencontrado');
            }
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
                'idAluno' => "",
                'bancas' => $bancas,
            ]);
        }
    }

    public function actionAlunonaoencontrado()
    {
        return $this->render('alunonaoencontrado');
    }

    /**
     * Updates an existing AgendarDefesa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionUpdate($idDefesa, $aluno_id)
    {
        $model = $this->findModel($idDefesa, $aluno_id);
        $alunoModel= Aluno::find('nome')->where(['id' => $aluno_id]);
        $model->aluno_id=$alunoModel;


        if ($model->load(Yii::$app->request->post())) {
            $idAluno = Yii::$app->request->Post('idAluno');
            $model->aluno_id=$idAluno;
            $model->save();
            return $this->redirect(['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'idAluno' =>"",
            ]);
        }
    }

    /**
     * Deletes an existing AgendarDefesa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionDelete($idDefesa, $aluno_id)
    {
        $this->findModel($idDefesa, $aluno_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AgendarDefesa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return AgendarDefesa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idDefesa, $aluno_id)
    {
        if (($model = AgendarDefesa::findOne(['idDefesa' => $idDefesa, 'aluno_id' => $aluno_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAutocompletealuno($term){
        $listaAlunos = Aluno::find()->where(["like","upper(nome)",strtoupper($term)])->all();

        $codigos = [];

        foreach ($listaAlunos as $alunos)
        {
            $codigos[] = ['label'=>$alunos['nome'],'value'=>$alunos['nome'], 'id'=>$alunos['id']
            ]; //build an array
        }

        echo json_encode($codigos);
    }
}