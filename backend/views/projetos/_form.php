<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Projetos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projetos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idProfessor')->textInput() ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'inicio')->textInput() ?>

    <?= $form->field($model, 'fim')->textInput() ?>

    <?= $form->field($model, 'papel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'financiadores')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'integrantes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
