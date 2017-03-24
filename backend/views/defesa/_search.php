<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DefesaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="defesa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idDefesa') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'tipoDefesa') ?>

    <?= $form->field($model, 'data') ?>

    <?= $form->field($model, 'conceito') ?>

    <?php // echo $form->field($model, 'horario') ?>

    <?php // echo $form->field($model, 'local') ?>

    <?php // echo $form->field($model, 'resumo') ?>

    <?php // echo $form->field($model, 'numDefesa') ?>

    <?php // echo $form->field($model, 'examinador') ?>

    <?php // echo $form->field($model, 'emailExaminador') ?>

    <?php // echo $form->field($model, 'reservas_id') ?>

    <?php // echo $form->field($model, 'banca_id') ?>

    <?php // echo $form->field($model, 'aluno_id') ?>

    <?php // echo $form->field($model, 'previa') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
