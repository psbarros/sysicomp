<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\MembrosBanca */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="membros-banca-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <?= $form->field($model, 'nome', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Nome:</b>")  ?>
    </div>
    <div class="row">
        <?= $form->field($model, 'email', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>E-mail:</b>")  ?>
    </div>
    <div class="row">
        <?= $form->field($model, 'filiacao', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Filiação:</b>")  ?>
    </div>
    <div class="row">
        <?= $form->field($model, 'telefone', ['options' => ['class' => 'col-md-3']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Telefone:</b>")  ?>

        <?= $form->field($model, 'cpf', ['options' => ['class' => 'col-md-3']])->widget(MaskedInput::className(), ['mask' => '999.999.999-99'])->label("<font color='#FF0000'>*</font><b>CPF:</b>") ?> 
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Criar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
