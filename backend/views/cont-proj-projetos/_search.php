<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProjetosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-projetos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nomeprojeto') ?>

    <?= $form->field($model, 'orcamento') ?>

    <?= $form->field($model, 'saldo') ?>

    <?= $form->field($model, 'data_inicio') ?>

    <?php // echo $form->field($model, 'data_fim') ?>

    <?php // echo $form->field($model, 'data_fim_alterada') ?>

    <?php // echo $form->field($model, 'coordenador_id') ?>

    <?php // echo $form->field($model, 'agencia_id') ?>

    <?php // echo $form->field($model, 'banco_id') ?>

    <?php // echo $form->field($model, 'agencia') ?>

    <?php // echo $form->field($model, 'conta') ?>

    <?php // echo $form->field($model, 'edital') ?>

    <?php // echo $form->field($model, 'proposta') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
