<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EquipamentoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipamento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idEquipamento') ?>

    <?= $form->field($model, 'NomeEquipamento') ?>

    <?= $form->field($model, 'Nserie') ?>

    <?= $form->field($model, 'NotaFiscal') ?>

    <?= $form->field($model, 'Localizacao') ?>

    <?php  echo $form->field($model, 'StatusEquipamento') ?>

    <?php  //echo $form->field($model, 'OrigemEquipamento') ?>

    <?php // echo $form->field($model, 'ImagemEquipamento') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
