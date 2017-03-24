<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Aproveitamento;
use yii\jui\AutoComplete;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\models\Aluno;
/* @var $this yii\bckend\View */
/* @var $searchModel backend\models\AproveitamentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(isset($idAluno))
	$this->title = 'Aproveitamentos de Disciplina de: '.$searchModel->idAlunoFK0->nome;
else
	$this->title = 'Aproveitamentos de Disciplina';
$this->params['breadcrumbs'][] = 'Aproveitamentos';
?>
<div class="aproveitamento-index">

    <!-- <h1><?php //echo Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    	<?php
    	if(isset($idAluno)){
    		$searchModel->flagFromAluno = true;
    		echo Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['aluno/view','id'=>$idAluno], ['class' => 'btn btn-warning']);
    		echo '&nbsp';
        	echo Html::a('Novo Aproveitamento', ['createbyaluno','idAluno'=>$idAluno], ['class' => 'btn btn-success']);
        	
        	
    	}else{
    		$searchModel->flagFromAluno = false;
    		//Escolha de projeto, não criar aproveitamento a partir do grid gera. Pode vir a ser alterado.
        	//echo Html::a('Novo Aproveitamento', ['create'], ['class' => 'btn btn-success']);
    		echo Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['site/index'], ['class' => 'btn btn-warning']);
    	}
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

        	[
        		//'label'=>'Aluno',
        		'attribute'=>'nomeAluno',//situacao',//
        		'value'=>'idAlunoFK0.nome',
        		'visible'=>!isset($idAluno),
        		'filter'=> AutoComplete::widget([
						        'model' => $searchModel,
						        'attribute' => 'nomeAluno',
						        'clientOptions' => [
						            'source' => URL::to(['aluno/autocompletealuno']),
						        ],
						    ]),
        	],
            'id',
            'codDisciplinaOrigemFK',
            'codDisciplinaDestinoFK',
            'nota:decimal',
            'frequencia:decimal',
            'situacao',

            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{view} {delete} {update}',
            	'buttons'=>[
            			'delete' =>(isset($idAluno))? 
            			function ($url, $model, $flagFromAluno=true) {
            			return Html::a('<span class="glyphicon glyphicon-trash"></span>', [(($flagFromAluno)?'deletebyaluno':'deletebyalunov2'), 'id' => $model->id, 'idAluno' => $model->idAluno], [
            					'data' => [
            							'confirm' => 'Remover o aproveitamento \''.$model->id.'\'?',
            							'method' => 'post',
            					],
            					'title' => Yii::t('yii', 'Remover Aproveitamento'),
            			]);
            			} 
            			:
            			function ($url, $model, $flagFromAluno=false) {
            				return Html::a('<span class="glyphicon glyphicon-trash"></span>', [(($flagFromAluno)?'deletebyaluno':'deletebyalunov2'), 'id' => $model->id, 'idAluno' => $model->idAluno], [
            						'data' => [
            								'confirm' => 'Remover o aproveitamento \''.$model->id.'\'?',
            								'method' => 'post',
            						],
            						'title' => Yii::t('yii', 'Remover Aproveitamento'),
            				]);
            			}
            			,
            			'view' => function ($url, $model){
            			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id, ((isset($idAluno))?'idAluno':'numb') => $model->idAluno], [
            					'title' => Yii::t('yii', 'Visualizar Aproveitamento'),
            			]);
            			},
            			'update' => function ($url, $model){
            			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', [((isset($idAluno))?'updatebyaluno':'updatebyalunov2'), 'id' => $model->id, 'idAluno' => $model->idAluno], [
            					'title' => Yii::t('yii', 'Editar Aproveitamento'),
            			]);
            			},
            	]
    		],
        ],
    ]); ?>
</div>
