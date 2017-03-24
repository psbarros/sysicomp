<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\backend\View */
/* @var $model backend\models\AproveitamentoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="aproveitamento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'codDisciplinaOrigemFK') ?>

    <?= $form->field($model, 'codDisciplinaDestinoFK') ?>

    <?= $form->field($model, 'nota') ?>

    <?= $form->field($model, 'frequencia') ?>

    <?php // echo $form->field($model, 'situacao') ?>

    <?php // echo $form->field($model, 'idAluno') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
