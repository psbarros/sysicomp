<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\BaixaCautelaAvulsa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="baixa-cautela-avulsa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idCautelaAvulsa')->hiddenInput()->label(false) ?>
	<div class="panel panel-default" style="width:80%">
	<div class="panel-heading">
    	<h3 class="panel-title"><b>Disciplina Origem:</b></h3>
    </div>
	<div class="panel-body">

	<div class="row">
    <?= $form->field($model, 'Recebedor',['options'=>['class'=>'col-md-4']])->textInput(['maxlength' => true]) ?>
	</div>
	<div class="row">
    <?= $form->field($model, 'DataDevolucao',['options'=>['class'=>'col-md-3']])->widget(DatePicker::classname(), [
			    //'language' => 'pt-BR',
			    'dateFormat' => 'dd-MM-yyyy',
	]) ?>
	
    <!--<?php //echo $form->field($model, 'Equipamento',['options'=>['class'=>'col-md-3']])->textInput(['maxlength' => true]); ?>-->
	</div>
	<div class="row">
    <?= $form->field($model, 'ObservacaoBaixaCautela',['options'=>['class'=>'col-md-6']])->textInput(['maxlength' => true]) ?>
    </div>
	</div>
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
