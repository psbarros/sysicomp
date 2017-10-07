<?php

namespace backend\controllers;

use Yii;
use app\models\Banca;
use app\models\BancaControleDefesas;
use app\models\MembrosBanca;
use yii\helpers\ArrayHelper;
use app\models\Defesa;
use app\models\Aluno;
use backend\models\FormBancas;
use yii\filters\AccessControl;
use app\models\BancaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\MembrosBancaSearch;

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
                            return Yii::$app->user->identity->checarAcesso('professor');
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

    public function actionIndexsemdefesa()
    {
        $searchModel = new BancaSearch();
        $dataProvider = $searchModel->searchSemDefesa(Yii::$app->request->queryParams);

        return $this->render('indexsemdefesa', [
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

    public function actionViewsemdefesa($banca_id, $membrosbanca_id)
    {
        $model = $this->findModel($banca_id, $membrosbanca_id);
        $model_banca = new BancaSearch();
        $dataProvider = $model_banca->searchMembros(Yii::$app->request->queryParams,$model->banca_id);
        return $this->render('viewsemdefesa', [
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
        $model = new FormBancas();
        $items = ArrayHelper::map(MembrosBanca::find()->all(), 'id', 'nome');

        if ($model->load(Yii::$app->request->post())) {

            if($model->membrosbanca_id_1!=$model->membrosbanca_id_2 && $model->membrosbanca_id_1!=$model->membrosbanca_id_3 && $model->membrosbanca_id_1!=$model->membrosbanca_id_4 && $model->membrosbanca_id_1!=$model->membrosbanca_id_5 && 
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_3 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_4 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_3!=$model->membrosbanca_id_4 || ($model->membrosbanca_id_3==NULL)) &&
                ($model->membrosbanca_id_3!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_3==NULL)) && 
                ($model->membrosbanca_id_4!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_4==NULL))){


            $model1= new Banca();
            $model1->funcao='P';
            $model_status= new BancaControleDefesas();
            $model_status->save(false);
            $model1->banca_id=$model_status->id;
            $model1->membrosbanca_id=$model->membrosbanca_id_1;
            $model1->save();
            
            $count=0;
            if($model->membrosbanca_id_1){$count++;}
            if($model->membrosbanca_id_2){$count++;}
            if($model->membrosbanca_id_3){$count++;}
            if($model->membrosbanca_id_4){$count++;}
            if($model->membrosbanca_id_5){$count++;}

           if(($model->tipobanca==1 && $count<3) || ($model->tipobanca==2 && $count<5)){

                $this->mensagens('danger', 'Banca não Criada!', 'numero de membros inválido!! Mestrado no minimo 3 membros e Doutorado No minimo 5 Membros');
                return $this->render('create', [
                'model' => $model,
                'items' => $items,
            ]);

            } 
            
            if($model->membrosbanca_id_2){
                   $model2= new Banca();
                   $model2->funcao=$model->funcao2;
                   $model2->banca_id=$model_status->id;
                   $model2->membrosbanca_id=$model->membrosbanca_id_2;
                   $model2->save();

            }

            if($model->membrosbanca_id_3){
                   $model3= new Banca();
 
                   $model3->funcao=$model->funcao3;
                   $model3->banca_id=$model_status->id;
                   $model3->membrosbanca_id=$model->membrosbanca_id_3;
                   $model3->save();

            }


            if($model->membrosbanca_id_4){
                   $model4= new Banca();

                   $model4->funcao=$model->funcao4;
                   $model4->banca_id=$model_status->id;
                   $model4->membrosbanca_id=$model->membrosbanca_id_4;
                   $model4->save();

            }

            if($model->membrosbanca_id_5){
                   $model5= new Banca();
                   
                   $model5->funcao=$model->funcao5;
                   $model5->banca_id=$model_status->id;
                   $model5->membrosbanca_id=$model->membrosbanca_id_5;
                   $model5->save();

            }
            
            $model_banca = new BancaSearch();
            $dataProvider = $model_banca->searchMembros(Yii::$app->request->queryParams,$model1->banca_id);
             return $this->render('view', [
            'model' => $model1,
            'dataProvider'=> $dataProvider,
        ]);
        }else{
                                        $this->mensagens('danger', 'Banca não Criada!', 'Os membros devem ser diferentes um do outro!!');
                return $this->render('create', [
                'model' => $model,
                'items' => $items,
            ]);

        }


        } else {
            return $this->render('create', [
                'model' => $model,
                'items' => $items,
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
        $model = Banca::findAll(['banca_id'=>$banca_id]);


         $items = ArrayHelper::map(MembrosBanca::find()->all(), 'id', 'nome');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'banca_id' => $model->banca_id, 'membrosbanca_id' => $model->membrosbanca_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'items'=> $items,
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
