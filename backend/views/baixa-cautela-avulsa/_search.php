<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BaixaCautelaAvulsaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="baixa-cautela-avulsa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idBaixaCautelaAvulsa') ?>

    <?= $form->field($model, 'idCautelaAvulsa') ?>

    <?= $form->field($model, 'Recebedor') ?>

    <?= $form->field($model, 'DataDevolucao') ?>

    <?= $form->field($model, 'Equipamento') ?>

    <?php // echo $form->field($model, 'ObservacaoBaixaCautela') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
