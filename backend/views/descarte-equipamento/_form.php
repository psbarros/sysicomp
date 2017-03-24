<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\widgets\DatePicker;

//teste

/* @var $this yii\web\View */
/* @var $model app\models\DescarteEquipamento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="descarte-equipamento-form">

	<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['equipamento/view','id'=>$model->idEquipamento], ['class' => 'btn btn-warning']) ?>
    <?php $form = ActiveForm::begin(); ?>

	<div class="panel panel-default" style="width:80%">
	<div class="panel-heading">
    	<h3 class="panel-title"><b>Dados Respons&aacute;vel:</b></h3>
    </div>

	<div class="panel-body">    
	<div class="row">
    <?= $form->field($model, 'NomeResponsavel', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true]) ?>
	</div>
	<div class="row">
	<?= $form->field($model, 'TelefoneResponsavel', ['options' => ['class' => 'col-md-3']])->widget(MaskedInput::className(), [
                            'mask' => '(99) 99999-9999']) ?>
    <?= $form->field($model, 'Email', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true]) ?>
	</div>
	</div>
	<div class="panel-heading">
    	<h3 class="panel-title"><b>Dados Equipamento:</b></h3>
    </div>
    <div class="panel-body">
    <div class="row">
    <?= $form->field($equipamento, 'NomeEquipamento', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true, 'readOnly'=>true]) ?>
	</div>
	<div class="row">
    <?= $form->field($equipamento, 'Nserie', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true, 'readOnly'=>true]) ?>

    <?= $form->field($equipamento, 'NotaFiscal', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true, 'readOnly'=>true]) ?>
    </div>
    </div>
	<div class="panel-heading">
    	<h3 class="panel-title"><b>Dados Descarte:</b></h3>
    </div>
	<div class="panel-body">
	<div class="row">
	<?= $form->field($model, 'dataDescarte', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
	                    'language' => 'pt-BR',
	                    'options' => ['placeholder' => 'Selecione a Data do Descarte ...', 'value'=>date('d-m-Y')],
	                    'pluginOptions' => [
	                        'format' => 'dd-mm-yyyy',
	                        'todayHighlight' => true
	                    ]
	                ]) ?>
	</div>
	<div class="row">
	<?= $form->field($model, 'documento', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => true]) ?>
	</div>
	<div class="row">
    <?= $form->field($model, 'ObservacoesDescarte', ['options' => ['class' => 'col-md-6']])->textarea(['maxlength' => true]) ?>
	</div>
	<div class="row">
	<?= $form->field($model, 'documentoImagem',['options'=>['class'=>'col-md-3']])->fileInput(['maxlength' => true]) ?>
	</div>
	</div>
	</div>
	<?php $form->field($model, 'idEquipamento')->hiddenInput()->label(false); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Descartar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
