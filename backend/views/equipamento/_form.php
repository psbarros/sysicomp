<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Equipamento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipamento-form">

	<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['equipamento/index'], ['class' => 'btn btn-warning']) ?>

    <?php $form = ActiveForm::begin(); ?>
    
	<div class="panel panel-default" style="width:80%">
	<div class="panel-heading">
    	<h3 class="panel-title"><b>Dados Equipamento:</b></h3>
    </div>
	<div class="panel-body">    
	<div class="row">
    <?= $form->field($model, 'NomeEquipamento', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true]) ?>
	</div>
	<div class="row">
    <?= $form->field($model, 'Nserie', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NotaFiscal', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true]) ?>
    </div>
	<div class="row">
    <?= $form->field($model, 'OrigemEquipamento', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'idProjeto', ['options' => ['class' => 'col-md-3']])->dropDownList(ArrayHelper::map(backend\models\ContProjProjetos::find()->all(),'id','nomeprojeto')) ?>
    
    <?= $form->field($model, 'Localizacao', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true]) ?>
	</div>
	<div class="row">
	<?= $form->field($model, 'StatusEquipamento', ['options' => ['class' => 'col-md-3']])->dropDownList($model->statusPermitidos) ?>
	
    <?= $form->field($model, 'ImagemEquipamento', ['options' => ['class' => 'col-md-3']])->fileInput() ?>
    </div>
	</div>
	</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
