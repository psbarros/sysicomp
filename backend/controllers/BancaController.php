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
use app\models\BancaControleDefesasSearch;
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
        $count=0;
        if ($model->load(Yii::$app->request->post())) {
            if($model->membrosbanca_id_1!=$model->membrosbanca_id_2 && $model->membrosbanca_id_1!=$model->membrosbanca_id_3 && $model->membrosbanca_id_1!=$model->membrosbanca_id_4 && $model->membrosbanca_id_1!=$model->membrosbanca_id_5 &&
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_3 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_4 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_3!=$model->membrosbanca_id_4 || ($model->membrosbanca_id_3==NULL)) &&
                ($model->membrosbanca_id_3!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_3==NULL)) &&
                ($model->membrosbanca_id_4!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_4==NULL))){

            if($model->membrosbanca_id_1){$count++;}
            if($model->membrosbanca_id_2){$count++;}
            if($model->membrosbanca_id_3){$count++;}
            if($model->membrosbanca_id_4){$count++;}
            if($model->membrosbanca_id_5){$count++;}
           if((($model->tipobanca==1 || $model->tipobanca==2 || $model->tipobanca==3 )&& $count<3) || ($model->tipobanca==4 && $count<5)){
                $this->mensagens('danger', 'Banca não Criada!', 'numero de membros inválido!! Mestrado no minimo 3 membros , Doutorado Q1 e Q2  no minimo 3 membros e Defesa de Doutorado  No minimo 5 Membros');
                return $this->render('create', [
                'model' => $model,
                'items' => $items,
            ]);
            }else{
            $model1= new Banca();
            $model1->funcao='P';
            $model_status= new BancaControleDefesas();
            $model_status->save(false);
            $model1->banca_id=$model_status->id;
            $model1->membrosbanca_id=$model->membrosbanca_id_1;
            $model1->save();
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
            $this->mensagens('success', 'Banca Criada com sucesso!','Cadastro da banca realizado com sucesso!');
             return $this->render('view', [
            'model' => $model1,
            'dataProvider'=> $dataProvider,
        ]);
       }
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
    public function actionUpdate($banca_id)
    {
        $modelsb = Banca::findAll($banca_id);
        $model= new FormBancas();
        $items = ArrayHelper::map(MembrosBanca::find()->all(), 'id', 'nome');

        foreach($modelsb as $modelb){
            if($modelb->funcao=='P'){
                   $model->membrosbanca_id_1=$modelb->membrosbanca_id;
                   $model->funcao1=$modelb->funcao;
            }else{
                   if(!$model->membrosbanca_id_2){
                   $model->membrosbanca_id_2=$modelb->membrosbanca_id;
                   $model->funcao2=$modelb->funcao;
                   }else{
                                       if(!$model->membrosbanca_id_3){
                                       $model->membrosbanca_id_3=$modelb->membrosbanca_id;
                                       $model->funcao3=$modelb->funcao;
                                       }else{
                                                           if(!$model->membrosbanca_id_4){
                                                           $model->membrosbanca_id_4=$modelb->membrosbanca_id;
                                                           $model->funcao4=$modelb->funcao;
                                                           }else{
                                                                               if(!$model->membrosbanca_id_5){
                                                                                $model->membrosbanca_id_5=$modelb->membrosbanca_id;
                                                                                $model->funcao5=$modelb->funcao;
                                                                                }
                                                           }
                                       }
                 }
            }
        }
        if ($model->load(Yii::$app->request->post())){
            if($model->membrosbanca_id_1!=$model->membrosbanca_id_2 && $model->membrosbanca_id_1!=$model->membrosbanca_id_3 && $model->membrosbanca_id_1!=$model->membrosbanca_id_4 && $model->membrosbanca_id_1!=$model->membrosbanca_id_5 &&
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_3 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_4 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_2!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_2==NULL)) &&
                ($model->membrosbanca_id_3!=$model->membrosbanca_id_4 || ($model->membrosbanca_id_3==NULL)) &&
                ($model->membrosbanca_id_3!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_3==NULL)) &&
                ($model->membrosbanca_id_4!=$model->membrosbanca_id_5 || ($model->membrosbanca_id_4==NULL))){
            $count=0;
            if($model->membrosbanca_id_1){$count++;}
            if($model->membrosbanca_id_2){$count++;}
            if($model->membrosbanca_id_3){$count++;}
            if($model->membrosbanca_id_4){$count++;}
            if($model->membrosbanca_id_5){$count++;}
           if((($model->tipobanca==1 || $model->tipobanca==2 || $model->tipobanca==3 )&& $count<3) || ($model->tipobanca==4 && $count<5)){
                $this->mensagens('danger', 'Banca não Criada!', 'numero de membros inválido!! Mestrado no minimo 3 membros , Doutorado Q1 e Q2  no minimo 3 membros e Defesa de Doutorado  No minimo 5 Membros');
                return $this->render('create', [
                'model' => $model,
                'items' => $items,
            ]);
            }
            $model_statusDelete=  new BancaControleDefesas();
            $model_statusDelete = BancaControleDefesas::deleteAll(['id' => $banca_id]);
            $modelDelete = Banca::deleteAll(['banca_id' => $banca_id]);
            $model1= new Banca();
            $model1->funcao='P';
            $model_status= new BancaControleDefesas();
            $model_status->save(false);
            $model1->banca_id=$model_status->id;
            $model1->membrosbanca_id=$model->membrosbanca_id_1;
            $model1->save();
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
                $this->mensagens('danger', 'Banca não Atualizada!', 'Os membros devem ser diferentes um do outro!!');
                return $this->render('update', [
                'model' => $model,
                'items' => $items,
            ]);
      }
        } else {
            return $this->render('update',['model' => $model, 'items'=> $items]);
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
        $model_status=  new BancaControleDefesas();
        $model_status = BancaControleDefesas::deleteAll(['id' => $banca_id]);
        $model = Banca::deleteAll(['banca_id' => $banca_id]);
        return $this->redirect(['indexsemdefesa']);
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
    public function actionAprovar($banca_id){



        $model = BancaControleDefesas::findOne($banca_id);
        $model->status_banca = 1;

        $model->save(false);

        $this->mensagens('success', 'Avaliação de Banca',  'A banca escolhida foi deferida com sucesso');

        return $this->redirect(['bancasemavaliacao']);

    }
    public function actionIndeferir($banca_id)
    {
      $model = BancaControleDefesas::findOne($banca_id);

      $model->status_banca = 0;

        if ($model->load(Yii::$app->request->post())) {


            $model->save(false);
            $this->mensagens('success', 'Avaliação de Banca',  'A banca escolhida foi indeferida com sucesso');


            return $this->redirect(['bancasemavaliacao']);

        } else {
            return $this->render('justificativa',[
                'model' => $model,
            ]);
        }
    }


    public function actionBancasemavaliacao()
    {
        $searchModel = new BancaSearch();
        $dataProvider = $searchModel->searchSemAvaliacao(Yii::$app->request->queryParams);
        return $this->render('bancasemavaliacao', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new BancaControleDefesas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
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
