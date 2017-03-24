<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Banca */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banca-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'membrosbanca_id')->textInput() ?>

    <?= $form->field($model, 'funcao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'passagem')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
