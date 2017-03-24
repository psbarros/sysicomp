<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

//teste

/* @var $this yii\web\View */
/* @var $model app\models\DescarteEquipamento */

$this->title = $model->descarteTemEquipamento->NomeEquipamento; //$model->idDescarte;
$this->params['breadcrumbs'][] = ['label' => 'Descarte Equipamentos', 'url' => ['equipamento/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="descarte-equipamento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['equipamento/index'], ['class' => 'btn btn-warning']) ?>
        <?php // echo Html::a('Update', ['update', 'id' => $model->idDescarte], ['class' => 'btn btn-primary']) ?>
        <?php /*echo Html::a('Delete', ['delete', 'id' => $model->idDescarte], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>

	<div class="panel panel-default" style="width:80%">
	<div class="panel-heading">
    	<h3 class="panel-title"><b>Dados Respons&aacute;vel:</b></h3>
    </div>
    <div class="panel-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NomeResponsavel',
        	[
        		'attribute'=>'TelefoneResponsavel',
        		'format'=>'text',
        		'value' =>$model->telefoneFormatado,
        	],
            'Email:email',
        ],
    ]) ?>
    </div>
    </div>
    <div class="panel panel-default" style="width:80%">
    <div class="panel-heading">
    	<h3 class="panel-title"><b>Dados Descarte:</b></h3>
    </div>
    <div class="panel-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idDescarte',
        	'documento',
        	'dataDescarte',
            'ObservacoesDescarte',
        	[
        		'attribute' => 'documentoImagem',
        		//'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
        		'format'=>['image', ['width'=>100, 'height'=>100]],
        		//'value' => "<a href='".$model->ImagemEquipamento."' target = '_blank'> Foto  </a>"
        		'visible'=>((trim($model->documentoImagem)!='')?true:false)
        	],
        ],
    ]) ?>
    
    </div>    
    </div>

</div>
