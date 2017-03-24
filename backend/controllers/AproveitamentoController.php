<?php

namespace backend\controllers;

use Yii;
use app\models\Aproveitamento;
use app\models\AproveitamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Aluno;
use app\models\Disciplina;
use yii\base\Exception;
use GuzzleHttp\Psr7\Request;

/**
 * AproveitamentoController implements the CRUD actions for Aproveitamento model.
 */
class AproveitamentoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        	/*
        	'access' => [
        		'class' => \yii\filters\AccessControl::className(),
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
        	*/
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Aproveitamento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AproveitamentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//print_r(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexbyaluno($idAluno)
    {
    	$searchModel = new AproveitamentoSearch();
    	$searchModel->idAluno = $idAluno;
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['AproveitamentoSearch']['idAluno'] = $idAluno;
    	$dataProvider = $searchModel->search($queryParams);
    	
    	return $this->render('index', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'idAluno'=>$idAluno,
    	]);
    }
    /**
     * Displays a single Aproveitamento model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {	
    	$dados = Yii::$app->request->get();
    	
    	if(isset($dados['idAluno']))
    		return $this->render('view', [
    				'model' => $this->findModel($id),
    				'idAluno'=>$dados['idAluno']
    		]);
    
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Aproveitamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Aproveitamento();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionCreatebyaluno($idAluno){
    	
    	$model = new Aproveitamento();
    	
    	$aproveitamento = new Aproveitamento();
    	$discOrigem = new Disciplina();
    	$discDestino = new Disciplina();
    	 
    	    	if ($aproveitamento->load(Yii::$app->request->post()) && 
    		$discOrigem->load(['Disciplina'=>Yii::$app->request->post()['Disciplina'][1]]) &&
    		$discDestino->load(['Disciplina'=>Yii::$app->request->post()['Disciplina'][2]])) {
    		
    		//$dados = Yii::$app->request->post();
    		//Verifica se Aproveitamento(Origem, Destino) é válido.
    		//Caso contrário, direciona para a actionView e apresenta o Aproveitamento existente.
    		$codAproveitamentoExistente = Null;
    		if(Aproveitamento::getAproveitamentoOrigemDestinoExiste($aproveitamento->codDisciplinaOrigemFK, $aproveitamento->codDisciplinaDestinoFK, $idAluno) !== Null){
	    		$this->mensagens('warning', 'Aproveitamento Já Existente.', 'Este aluno já possui este aproveitamento.');
	    		$codAproveitamentoExistente[] = Aproveitamento::getAproveitamentoOrigemDestinoExiste($aproveitamento->codDisciplinaOrigemFK, $aproveitamento->codDisciplinaDestinoFK, $idAluno);
    		}else{
    			$headMsg; $bodyMsg;
	    		if(Aproveitamento::getAproveitamentoDisciplinaUsada($aproveitamento->codDisciplinaOrigemFK, $idAluno) !== Null){
		    		$headMsg[] = 'Disciplina Origem';
		    		$bodyMsgX[] = 'um aproveitamento com a disciplina origem';
		    		$codAproveitamentoExistente[] = Aproveitamento::getAproveitamentoDisciplinaUsada($aproveitamento->codDisciplinaOrigemFK, $idAluno);
	    		}
	    		if(Aproveitamento::getAproveitamentoDisciplinaUsada($aproveitamento->codDisciplinaDestinoFK, $idAluno) !== Null){
	    			$headMsg[] = 'Disciplina Destino';
	    			$bodyMsgX[] = 'um aproveitamento com a disciplina destino.';
	    			$codAproveitamentoExistente[] = Aproveitamento::getAproveitamentoDisciplinaUsada($aproveitamento->codDisciplinaDestinoFK, $idAluno);
	    		}
	    		if($codAproveitamentoExistente !== Null){
		    		$head = implode(' & ',$headMsg); 
		    		$msgX = 'Este Aluno já possui '.implode(' & ', $bodyMsgX).'.';
		    		 
		    		
		    		$this->mensagens('warning', $head, $msgX);
	    		}
	    		
    		}
    		if($codAproveitamentoExistente !== Null)
    		{	
    			$queryParams=['AproveitamentoSearch'=>['idAluno'=>$idAluno]];
    			//Redireciona para o GridView listando os Aproveitamentos em que as Disciplinas Impedidas Aparecem.
    			return $this->goToIndexAndSearch($idAluno, $codAproveitamentoExistente);
    		}
    		
    		//Verifica Existencia da Disciplina Origem
    		if(Disciplina::findOne(strtolower($aproveitamento->codDisciplinaOrigemFK))===Null){
    			$mDisciplina = new Disciplina();
    			$mDisciplina->codDisciplina = strtolower($aproveitamento->codDisciplinaOrigemFK);
    			$mDisciplina->cargaHoraria = $discOrigem->cargaHoraria;//$dados["disciplinaOrigemCargaHoraria"];
    			$mDisciplina->creditos = $discOrigem->creditos;//$dados["disciplinaOrigemCreditos"];
    			$mDisciplina->nome = $discOrigem->nome;//$dados["disciplinaOrigemNome"];
    			$mDisciplina->nomeCurso = $discOrigem->nomeCurso;
    			$mDisciplina->instituicao = $discOrigem->instituicao;
    			$mDisciplina->preRequisito = $discOrigem->preRequisito;
    			$mDisciplina->obrigatoria = $discOrigem->obrigatoria;
    			
    			if(!$mDisciplina->save()){
    				throw new NotFoundHttpException("Erro ao cadastrar disciplina $mDisciplina->codDisciplina : $mDisciplina->nome.");
    			}
    		}
    		
    		//Verifica Existencia da Disciplina Destino
    		if(Disciplina::findOne(strtolower($aproveitamento->codDisciplinaDestinoFK))===Null){
    			$mDisciplina = new Disciplina();
    			$mDisciplina->codDisciplina = strtolower($aproveitamento->codDisciplinaDestinoFK);
    			$mDisciplina->cargaHoraria = $discDestino->cargaHoraria;//$dados["disciplinaDestinoCargaHoraria"];
    			$mDisciplina->creditos = $discDestino->creditos;//$dados["disciplinaDestinoCreditos"];
    			$mDisciplina->nome = $discDestino->nome;//$dados["disciplinaDestinoNome"];
    			$mDisciplina->nomeCurso = $discDestino->nomeCurso;
    			$mDisciplina->instituicao = $discDestino->instituicao;
    			$mDisciplina->preRequisito = $discDestino->preRequisito;
    			$mDisciplina->obrigatoria = $discDestino->obrigatoria;
    			 
    			if(!$mDisciplina->save()){
    				throw new NotFoundHttpException("Erro ao cadastrar disciplina $mDisciplina->codDisciplina : $mDisciplina->nome.");
    			}
    		}
    		
    		if($aproveitamento->save()){
    			return $this->redirect(['view', 'id' => $aproveitamento->id, 'idAluno'=>$idAluno]);
    		} else {
	            /*return $this->render('create', [
	                'model' => $model,
	            ]);*/
    			throw new NotFoundHttpException('Erro ao registrar Aproveitamento. Contate o administrador do sistema.');
        	}    		
    		
    	}else    	
	    	if(Aluno::findOne($idAluno) !== Null){
	    		$model->idAluno = $idAluno;
	    		return $this->render('create', [
	    				'model' => $model,
	    				'fromAluno'=>true,
	    		]);
	    	}else{
	    		throw new NotFoundHttpException('Aluno não existente.');
	    	}
    }

    /**
     * Updates an existing Aproveitamento model.
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
    
    public function actionUpdatebyaluno($id)
    {

    	//$model = new Aproveitamento();
    	 
    	$aproveitamento = new Aproveitamento();
    	$discOrigem = new Disciplina();
    	$discDestino = new Disciplina();
    	 
    	$idAluno;
    	$aproveitamento = Aproveitamento::findOne($id); 
    	if($aproveitamento !== Null){
    		$idAluno = Aproveitamento::findOne($id)->idAluno;
    	}else{
    		throw new NotFoundHttpException('Aluno não existente.');
    	}
    	
if ($aproveitamento->load(Yii::$app->request->post()) && 
    		$discOrigem->load(['Disciplina'=>Yii::$app->request->post()['Disciplina'][1]]) &&
    		$discDestino->load(['Disciplina'=>Yii::$app->request->post()['Disciplina'][2]])) {
    	
    		$dados = Yii::$app->request->post();
    		$idAluno = $dados['Aproveitamento']['idAluno'];
    		//$aproveitamento->id = $dados['Aproveitamento']['id'];
    		//Verifica se Aproveitamento(Origem, Destino) é válido.
    		//Caso contrário, direciona para a actionView e apresenta o Aproveitamento existente.
    		$codAproveitamentoExistente = Null;
    		$codAprov = Aproveitamento::getAproveitamentoOrigemDestinoExiste($aproveitamento->codDisciplinaOrigemFK, $aproveitamento->codDisciplinaDestinoFK, $idAluno);
    		if($codAprov !== Null && $codAprov != $id){
    			$this->mensagens('warning', 'Aproveitamento Já Existente.', 'Este aluno já possui este aproveitamento.'.$codAprov."---".$id);
    			$codAproveitamentoExistente[] = Aproveitamento::getAproveitamentoOrigemDestinoExiste($aproveitamento->codDisciplinaOrigemFK, $aproveitamento->codDisciplinaDestinoFK, $idAluno);
    		}else{
    			$headMsg; $bodyMsg;
    			$codAprov = Aproveitamento::getAproveitamentoDisciplinaUsada($aproveitamento->codDisciplinaOrigemFK, $idAluno);
    			if($codAprov !== Null && $codAprov != $id){
    				$headMsg[] = 'Disciplina Origem';
    				$bodyMsgX[] = 'um aproveitamento com a disciplina origem';
    				$codAproveitamentoExistente[] = $codAprov;
    			}
    			$codAprov = Aproveitamento::getAproveitamentoDisciplinaUsada($aproveitamento->codDisciplinaDestinoFK, $idAluno);
    			if($codAprov !== Null && $codAprov != $id){
    				$headMsg[] = 'Disciplina Destino';
    				$bodyMsgX[] = 'um aproveitamento com a disciplina destino.';
    				$codAproveitamentoExistente[] = $codAprov;
    			}
    			if($codAproveitamentoExistente !== Null){
    				$head = implode(' & ',$headMsg);
    				$msgX = 'Este Aluno já possui '.implode(' & ', $bodyMsgX).'.';
    				 
    	
    				$this->mensagens('warning', $head, $msgX);
    			}
    			 
    		}
    		if($codAproveitamentoExistente !== Null)
    		{
    			$queryParams=['AproveitamentoSearch'=>['idAluno'=>$idAluno]];
    			//Redireciona para o GridView listando os Aproveitamentos em que as Disciplinas Impedidas Aparecem.
    			return $this->goToIndexAndSearch($idAluno, $codAproveitamentoExistente);
    		}
    	
    		//Verifica Existencia da Disciplina Origem
    		if(Disciplina::findOne($aproveitamento->codDisciplinaOrigemFK)===Null){
    			$mDisciplina = new Disciplina();
    			$mDisciplina->codDisciplina = strtolower($aproveitamento->codDisciplinaOrigemFK);
    			$mDisciplina->cargaHoraria = $discOrigem->cargaHoraria;//$dados["disciplinaOrigemCargaHoraria"];
    			$mDisciplina->creditos = $discOrigem->creditos;//$dados["disciplinaOrigemCreditos"];
    			$mDisciplina->nome = $discOrigem->nome;//$dados["disciplinaOrigemNome"];
    			 
    			if(!$mDisciplina->save()){
    				throw new NotFoundHttpException("Erro ao cadastrar disciplina $mDisciplina->codDisciplina : $mDisciplina->nome.");
    			}
    		}
    	
    		//Verifica Existencia da Disciplina Destino
    		if(Disciplina::findOne($aproveitamento->codDisciplinaDestinoFK)===Null){
    			$mDisciplina = new Disciplina();
    			$mDisciplina->codDisciplina = strtolower($aproveitamento->codDisciplinaDestinoFK);
    			$mDisciplina->cargaHoraria = $discDestino->cargaHoraria;//$dados["disciplinaDestinoCargaHoraria"];
    			$mDisciplina->creditos = $discDestino->creditos;//$dados["disciplinaDestinoCreditos"];
    			$mDisciplina->nome = $discDestino->nome;//$dados["disciplinaDestinoNome"];
    	
    			if(!$mDisciplina->save()){
    				throw new NotFoundHttpException("Erro ao cadastrar disciplina $mDisciplina->codDisciplina : $mDisciplina->nome.");
    			}
    		}
    		//$aproveitamento->isNewRecord = false;
    		if($aproveitamento->save()!==false){
    			return $this->redirect(['view', 'id' => $id, 'idAluno'=>$idAluno]);
    		} else {
    			/*return $this->render('create', [
    			 'model' => $model,
    			 ]);*/
    			throw new NotFoundHttpException('Erro ao atualizar Aproveitamento. Contate o administrador do sistema.');
    		}
    	
    	}else{
    			$aproveitamento = Aproveitamento::findOne($id);
    			//$model->idAluno = $apro;
    			return $this->render('update', [
    					'model' => $aproveitamento,
    					'fromAluno'=>true,
    			]);
    	}
    }

    
    public function actionUpdatebyalunov2($id)
    {
    
    	//$model = new Aproveitamento();
    
    	$aproveitamento = new Aproveitamento();
    	$discOrigem = new Disciplina();
    	$discDestino = new Disciplina();
    
    	$idAluno;
    	$aproveitamento = Aproveitamento::findOne($id);
    	if($aproveitamento !== Null){
    		$idAluno = Aproveitamento::findOne($id)->idAluno;
    	}else{
    		throw new NotFoundHttpException('Aluno não existente.');
    	}
    	 
    	if ($aproveitamento->load(Yii::$app->request->post()) &&
    			$discOrigem->load(['Disciplina'=>Yii::$app->request->post()['Disciplina'][1]]) &&
    			$discDestino->load(['Disciplina'=>Yii::$app->request->post()['Disciplina'][2]])) {
    				 
    				$dados = Yii::$app->request->post();
    				$idAluno = $dados['Aproveitamento']['idAluno'];
    				//$aproveitamento->id = $dados['Aproveitamento']['id'];
    				//Verifica se Aproveitamento(Origem, Destino) é válido.
    				//Caso contrário, direciona para a actionView e apresenta o Aproveitamento existente.
    				$codAproveitamentoExistente = Null;
    				$codAprov = Aproveitamento::getAproveitamentoOrigemDestinoExiste($aproveitamento->codDisciplinaOrigemFK, $aproveitamento->codDisciplinaDestinoFK, $idAluno);
    				if($codAprov !== Null && $codAprov != $id){
    					$this->mensagens('warning', 'Aproveitamento Já Existente.', 'Este aluno já possui este aproveitamento.'.$codAprov."---".$id);
    					$codAproveitamentoExistente[] = Aproveitamento::getAproveitamentoOrigemDestinoExiste($aproveitamento->codDisciplinaOrigemFK, $aproveitamento->codDisciplinaDestinoFK, $idAluno);
    				}else{
    					$headMsg; $bodyMsg;
    					$codAprov = Aproveitamento::getAproveitamentoDisciplinaUsada($aproveitamento->codDisciplinaOrigemFK, $idAluno);
    					if($codAprov !== Null && $codAprov != $id){
    						$headMsg[] = 'Disciplina Origem';
    						$bodyMsgX[] = 'um aproveitamento com a disciplina origem';
    						$codAproveitamentoExistente[] = $codAprov;
    					}
    					$codAprov = Aproveitamento::getAproveitamentoDisciplinaUsada($aproveitamento->codDisciplinaDestinoFK, $idAluno);
    					if($codAprov !== Null && $codAprov != $id){
    						$headMsg[] = 'Disciplina Destino';
    						$bodyMsgX[] = 'um aproveitamento com a disciplina destino.';
    						$codAproveitamentoExistente[] = $codAprov;
    					}
    					if($codAproveitamentoExistente !== Null){
    						$head = implode(' & ',$headMsg);
    						$msgX = 'Este Aluno já possui '.implode(' & ', $bodyMsgX).'.';
    							
    						 
    						$this->mensagens('warning', $head, $msgX);
    					}
    
    				}
    				if($codAproveitamentoExistente !== Null)
    				{
    					$queryParams=['AproveitamentoSearch'=>['idAluno'=>$idAluno]];
    					//Redireciona para o GridView listando os Aproveitamentos em que as Disciplinas Impedidas Aparecem.
    					return $this->goToIndexAndSearchV2($idAluno, $codAproveitamentoExistente);
    				}
    				 
    				//Verifica Existencia da Disciplina Origem
    				if(Disciplina::findOne($aproveitamento->codDisciplinaOrigemFK)===Null){
    					$mDisciplina = new Disciplina();
    					$mDisciplina->codDisciplina = strtolower($aproveitamento->codDisciplinaOrigemFK);
    					$mDisciplina->cargaHoraria = $discOrigem->cargaHoraria;//$dados["disciplinaOrigemCargaHoraria"];
    					$mDisciplina->creditos = $discOrigem->creditos;//$dados["disciplinaOrigemCreditos"];
    					$mDisciplina->nome = $discOrigem->nome;//$dados["disciplinaOrigemNome"];
    
    					if(!$mDisciplina->save()){
    						throw new NotFoundHttpException("Erro ao cadastrar disciplina $mDisciplina->codDisciplina : $mDisciplina->nome.");
    					}
    				}
    				 
    				//Verifica Existencia da Disciplina Destino
    				if(Disciplina::findOne($aproveitamento->codDisciplinaDestinoFK)===Null){
    					$mDisciplina = new Disciplina();
    					$mDisciplina->codDisciplina = strtolower($aproveitamento->codDisciplinaDestinoFK);
    					$mDisciplina->cargaHoraria = $discDestino->cargaHoraria;//$dados["disciplinaDestinoCargaHoraria"];
    					$mDisciplina->creditos = $discDestino->creditos;//$dados["disciplinaDestinoCreditos"];
    					$mDisciplina->nome = $discDestino->nome;//$dados["disciplinaDestinoNome"];
    					 
    					if(!$mDisciplina->save()){
    						throw new NotFoundHttpException("Erro ao cadastrar disciplina $mDisciplina->codDisciplina : $mDisciplina->nome.");
    					}
    				}
    				//$aproveitamento->isNewRecord = false;
    				if($aproveitamento->save()!==false){
    					return $this->redirect(['view', 'id' => $id]);
    				} else {
    					/*return $this->render('create', [
    					 'model' => $model,
    					 ]);*/
    					throw new NotFoundHttpException('Erro ao atualizar Aproveitamento. Contate o administrador do sistema.');
    				}
    				 
    			}else{
    				$aproveitamento = Aproveitamento::findOne($id);
    				//$model->idAluno = $apro;
    				return $this->render('update', [
    						'model' => $aproveitamento,
    						'fromAluno'=>false,
    				]);
    			}
    }    
    
    /**
     * Deletes an existing Aproveitamento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeletebyaluno($id, $idAluno){
    	$this->findModel($id)->delete();
    	
    	return $this->redirect(['indexbyaluno','idAluno'=>$idAluno]);
    }
    
    public function actionDeletebyalunov2($id, $idAluno){
    	$this->findModel($id)->delete();
    	 
    	return $this->redirect(['indexbyaluno']);
    }

    /**
     * Finds the Aproveitamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Aproveitamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Aproveitamento::findOne($id)) !== null) {
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
    
    public function goToIndexAndSearch($idAluno, $ids){
    	$searchModel = new AproveitamentoSearch();
    	$searchModel->idAluno = $idAluno;
    	
    	$dataProvider = $searchModel->searchIds($ids);

    	return $this->render('index', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'idAluno'=>$idAluno,
    	]);
    	
    }
    
    public function goToIndexAndSearchV2($idAluno, $ids){
    	$searchModel = new AproveitamentoSearch();
    	$searchModel->idAluno = $idAluno;
    	 
    	$dataProvider = $searchModel->searchIds($ids);
    
    	return $this->render('index', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    	 
    }
}
