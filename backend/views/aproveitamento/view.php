<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Aproveitamento */

$this->title = "Aproveitamento: ".strtoupper($model->codDisciplinaOrigemFK)." como ".strtoupper($model->codDisciplinaDestinoFK);
$this->params['breadcrumbs'][] = ['label' => 'Aproveitamentos', 'url' => ['indexbyaluno', 'idAluno'=>$model->idAluno]];
$this->params['breadcrumbs'][] = strtoupper($model->codDisciplinaOrigemFK)." como ".strtoupper($model->codDisciplinaDestinoFK);
?>
<div class="aproveitamento-view">

    <!-- <h1><?php //echo Html::encode($this->title); ?></h1> -->

    <p>
    	<?php
    		if(isset($idAluno)){
    		 echo Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['aproveitamento/indexbyaluno','idAluno'=>$idAluno], ['class' => 'btn btn-warning']);
    		 echo '&nbsp';
    		 echo Html::a('<span class="glyphicon glyphicon-edit"></span> Editar  ', ['updatebyaluno', 'id' => $model->id], ['class' => 'btn btn-primary']);
    		 echo '&nbsp';
    		 echo Html::a('<span class="fa fa-trash-o"></span> Excluir', ['deletebyaluno', 'id' => $model->id, 'idAluno' => $model->idAluno], [
    		 		'class' => 'btn btn-danger',
    		 		'data' => [
    		 				'confirm' => 'Remover o aproveitamento \''.$model->id.'\'?',
    		 				'method' => 'post',
    		 		],
    		 ]);
    		}else{
    		 echo Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['aproveitamento/index'], ['class' => 'btn btn-warning']);
    		 echo '&nbsp';
    		 echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
    		 echo '&nbsp';
	         echo Html::a('Delete', ['delete', 'id' => $model->id], [
	            'class' => 'btn btn-danger',
	            'data' => [
	                'confirm' => 'Are you sure you want to delete this item?',
	                'method' => 'post',
	            ],
	        ]); }?>
    </p>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados da Disciplina Origem</b></h3>
        </div>
        <div class="panel-body">
    <?= DetailView::widget([
        'model' => $model->codDisciplinaOrigemFK0,
        'attributes' => [
        	'codDisciplina',
        	'nome',
        	'creditos',
        	'cargaHoraria',
        	'instituicao',
        	'nomeCurso',
        	'preRequisito',
        	[
            	'attribute'=>'obrigatoria',
            	'value'=>$model->codDisciplinaOrigemFK0->obrigatoriaLabel
    		]
        ],
    ]) ?>
        </div>
	</div>
	
	<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados da Disciplina Destino</b></h3>
        </div>
        <div class="panel-body">
    <?= DetailView::widget([
        'model' => $model->codDisciplinaDestinoFK0,
        'attributes' => [
            'codDisciplina',
        	'nome',
        	'creditos',
        	'cargaHoraria',
        	'instituicao',
        	'nomeCurso',
        	'preRequisito',
        	[
        		'attribute'=>'obrigatoria',
        		'value'=>$model->codDisciplinaDestinoFK0->obrigatoriaLabel
        	]
        ],
    ]) ?>
        </div>
	</div>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados do Aproveitamento</b></h3>
        </div>
        <div class="panel-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	'nota',
        	'frequencia',
        	'situacao',
        ],
    ]) ?>
        </div>
	</div>

</div>
