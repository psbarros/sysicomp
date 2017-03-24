<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BancaControleDefesas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banca-controle-defesas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php  //echo $form->field($model, 'status_banca')->textInput() ?>

    <div style="color:red; float:left">*</div><?= $form->field($model, 'justificativa')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Indeferir e Salvar' : 'Indeferir e Salvar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
