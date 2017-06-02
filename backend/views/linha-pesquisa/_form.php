<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\ColorInput;

?>

<div class="row">
    <div class="col-lg-8">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title"><b>Dados da Nova Linha de Pesquisa</b></h3>
            </div>

            <div class="panel-body">

                <?php $form = ActiveForm::begin(); ?>

                <div class="row">
                    <?= $form->field($model, 'nome', ['options' => ['class' => 'col-md-5']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Nome</b>") ?>
                </div>
                <div class="row">
                    <?= $form->field($model, 'sigla', ['options' => ['class' => 'col-md-5']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Sigla</b>") ?>
                </div>
                <div class="row">
                    <?= $form->field($model, 'icone', ['options' => ['class' => 'col-md-5']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Ícone</b>") ?>
                </div>

                <div class="row">
                    <?= $form->field($model, 'cor', ['options' => ['class' => 'col-md-5']])->widget(ColorInput::classname(), [
                        'options' => ['placeholder' => 'Selecione uma cor ...'],
                        'useNative' => true,
                        ]);
                    ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Alterar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

</div>
