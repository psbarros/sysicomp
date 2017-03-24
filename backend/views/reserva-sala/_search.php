<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReservaSalaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reserva-sala-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'dataReserva') ?>

    <?= $form->field($model, 'sala') ?>

    <?= $form->field($model, 'idSolicitante') ?>

    <?= $form->field($model, 'atividade') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'dataInicio') ?>

    <?php // echo $form->field($model, 'dataTermino') ?>

    <?php // echo $form->field($model, 'horaInicio') ?>

    <?php // echo $form->field($model, 'horaTermino') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
