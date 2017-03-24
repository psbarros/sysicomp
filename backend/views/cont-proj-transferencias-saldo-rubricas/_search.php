<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-transferencias-saldo-rubricas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'projeto_id') ?>

    <?= $form->field($model, 'rubrica_origem') ?>

    <?= $form->field($model, 'rubrica_destino') ?>

    <?= $form->field($model, 'valor') ?>

    <?php // echo $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'autorizacao') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
