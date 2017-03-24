<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-rubricasde-projetos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'projeto_id') ?>

    <?= $form->field($model, 'rubrica_id') ?>

    <?= $form->field($model, 'descricao') ?>

    <?= $form->field($model, 'valor_total') ?>

    <?php // echo $form->field($model, 'valor_gasto') ?>

    <?php // echo $form->field($model, 'valor_disponivel') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
