<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Disciplina */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disciplina-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="panel panel-default" style="width:80%">
	 <div class="panel-heading">
      <h3 class="panel-title"><b>Dados da Disciplina:</b></h3>
     </div>
	  <div class="panel-body">

		<div class="row">
    <?= $form->field($model, 'codDisciplina', ['options'=>['class'=>'col-md-3']])->textInput(['maxlength' => true]) ?>
		</div>
		<div class="row">
    <?= $form->field($model, 'nome', ['options'=>['class'=>'col-md-5']])->textInput(['maxlength' => true]) ?>

		</div>
		<div class="row">
    <?= $form->field($model, 'creditos',['options'=>['class'=>'col-md-2']])->textInput()->widget(MaskedInput::className(), [
                    'mask' => '99', 'clientOptions'=>['alias'=>'numeric']]) ?>

    <?= $form->field($model, 'cargaHoraria',['options'=>['class'=>'col-md-3']])->textInput()->widget(MaskedInput::className(), [
                    'mask' => '999', 'clientOptions'=>['alias'=>'numeric']]) ?>
        </div>

    <div class="row">
    <?= $form->field($model, 'instituicao', ['options'=>['class'=>'col-md-5']])->textInput(['maxlength' => true]) ?>
    </div>
    
    <div class="row">
    <?= $form->field($model, 'nomeCurso', ['options'=>['class'=>'col-md-5']])->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row">
    <?= $form->field($model, 'obrigatoria', ['options'=>['class'=>'col-md-2']])->dropDownList( [1=>"SIM", 0=>"NÃO"]) ?>
    
    <?= $form->field($model, 'preRequisito', ['options'=>['class'=>'col-md-3']])->textInput(['maxlength' => true]) ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Alterar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
   </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
