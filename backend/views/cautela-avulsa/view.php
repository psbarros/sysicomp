<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\MaskedInput;
use app\models\CautelaAvulsa;
/* @var $this yii\web\View */
/* @var $model app\models\CautelaAvulsa */

$this->title = $model->NomeResponsavel;
$this->params['breadcrumbs'][] = ['label' => 'Cautela Avulsas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->title = "Responsável: ".$this->title;
?>
<div class="cautela-avulsa-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['cautela-avulsa/index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Atualizar', ['update', 'idCautelaAvulsa' => $model->idCautelaAvulsa, 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> Gerar Pdf', ['cautela-avulsa/gerapdfunico', 'id' => $model->idCautelaAvulsa], ['target' => '_blank', 'class' => 'btn btn-info']) ?>
        
        <?php
        	if($model->StatusCautelaAvulsa !== CautelaAvulsa::getStatusConcluida()){
        	echo  Html::a('Dar Baixa Cautela', ['baixa-cautela-avulsa/create', 'id' => $model->idCautelaAvulsa], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Você tem certeza que deseja dar baixa nesta Cautela?',
                'method' => 'post',
                //$flagCautelaAvulsa = 1,

                //return ('BaixaCautelaController', [ 'flag' => $flagCautelaAvulsa]),

               // $this->redirect(array('descarte-equipamento/create', 'id' => $model->idEquipamento)),
            ],
        ]); }elseif($model->baixaReversivel){
       		echo Html::a('Reverter Baixa', ['baixa-cautela-avulsa/revert', 'idCautelaAvulsa' => $model->idCautelaAvulsa], [
       				'class' => 'btn btn-success',
       				'data' => [
       						'confirm' => 'Você tem certeza que deseja Reverter esta Baixa?',
       						'method' => 'post',
       				],
       		]);
        }?>        
        <?= Html::a('Remover', ['delete', 'idCautelaAvulsa' => $model->idCautelaAvulsa], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Você tem certeza que deseja apagar este item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

<div class="panel panel-default" style="width:60%">
<div class="panel-heading">
                <h3 class="panel-title"><b>Dados Cautela Avulsa:</b></h3>
</div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idCautelaAvulsa',
            [
            	'attribute'=>'id',
            	'value'=>(($model->cautelaAvulsaTemUsuario != null)? $model->cautelaAvulsaTemUsuario->nome : "Usuário Não Encontrado"),
            ],
            'NomeResponsavel',
            'Email:email',
            'ValidadeCautela',
            [
            	'attribute'=>'TelefoneResponsavel',
            	'format'=>'text',
            	'value' =>$model->telefoneFormatado,
            ],
            'ObservacoesDescarte',
        	'StatusCautelaAvulsa',
        	[
        	'attribute' => 'ImagemCautela',
        		//'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
    	    	'format'=>['image', ['width'=>100, 'height'=>100]],
	        	//'value' => "<a href='".$model->ImagemCautela."' target = '_blank'> Foto  </a>"
        		'visible'=>((trim($model->ImagemCautela)!='')?true:false)
        	],
        ],
    ]) ?>
</div>

    <?php
    	if($model->StatusCautelaAvulsa === CautelaAvulsa::getStatusConcluida()){
    		echo '<div class="panel panel-default" style="width:60%">
    		<div class="panel-heading">
    		<h3 class="panel-title"><b>Dados Baixa:</b></h3>
    		</div>';    		
    		
	    	echo DetailView::widget([
	        'model' => $model->cautelaAvulsaTemBaixa,
	        'attributes' => [
	            //'idBaixaCautelaAvulsa',
	            //'idCautelaAvulsa',
	            'Recebedor',
	            'DataDevolucao',
	           // 'Equipamento',
	            'ObservacaoBaixaCautela',
	        ],
	    ]);
	    	echo "</div>";
    	} ?>


</div>
