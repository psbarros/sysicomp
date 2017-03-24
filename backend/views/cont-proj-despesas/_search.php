<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjDespesasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-despesas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'rubricasdeprojetos_id') ?>

    <?= $form->field($model, 'descricao') ?>

    <?= $form->field($model, 'valor_despesa') ?>

    <?= $form->field($model, 'tipo_pessoa') ?>

    <?php // echo $form->field($model, 'data_emissao') ?>

    <?php // echo $form->field($model, 'ident_nf') ?>

    <?php // echo $form->field($model, 'nf') ?>

    <?php // echo $form->field($model, 'ident_cheque') ?>

    <?php // echo $form->field($model, 'data_emissao_cheque') ?>

    <?php // echo $form->field($model, 'valor_cheque') ?>

    <?php // echo $form->field($model, 'favorecido') ?>

    <?php // echo $form->field($model, 'cnpj_cpf') ?>

    <?php // echo $form->field($model, 'comprovante') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
