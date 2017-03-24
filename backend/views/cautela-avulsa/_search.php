<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CautelaAvulsaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cautela-avulsa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idCautelaAvulsa') ?>

    <?php // //$form->field($model, 'id') ?>

    <?= $form->field($model, 'NomeResponsavel') ?>

    <?= $form->field($model, 'Email') ?>

    <?= $form->field($model, 'ValidadeCautela') ?>

    <?php // echo $form->field($model, 'TelefoneResponsavel') ?>

    <?php // echo $form->field($model, 'ObservacoesDescarte') ?>

    <?php // echo $form->field($model, 'ImagemCautela') ?>

    <?php echo $form->field($model, 'StatusCautelaAvulsa') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
