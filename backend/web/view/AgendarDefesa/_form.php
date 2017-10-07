<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AgendarDefesa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agendar-defesa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipoDefesa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'conceito')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'resumo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'numDefesa')->textInput() ?>

    <?= $form->field($model, 'examinador')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'emailExaminador')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'reservas_id')->textInput() ?>

    <?= $form->field($model, 'banca_id')->textInput() ?>

    <?= $form->field($model, 'aluno_id')->textInput() ?>

    <?= $form->field($model, 'previa')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
