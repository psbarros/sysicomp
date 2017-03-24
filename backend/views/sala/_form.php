<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="sala-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
    	<?= $form->field($model, 'nome', ['options' => ['class' => 'col-md-4']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Nome:</b>") ?>
	</div>
	<div class="row">
    	<?= $form->field($model, 'numero', ['options' => ['class' =>  'col-md-4']])->textInput()?>
	</div>
	<div class="row">
    	<?= $form->field($model, 'localizacao', ['options' => ['class' =>  'col-md-4']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Localização da Sala:</b>") ?>
	</div>
    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
