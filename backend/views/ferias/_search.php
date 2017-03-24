<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FeriasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ferias-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idusuario') ?>

    <?= $form->field($model, 'nomeusuario') ?>

    <?= $form->field($model, 'emailusuario') ?>

    <?= $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'dataSaida') ?>

    <?php // echo $form->field($model, 'dataRetorno') ?>

    <?php // echo $form->field($model, 'dataPedido') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
