<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use kartik\widgets\DatePicker;
use yii\widgets\MaskedInput;
use kartik\widgets\Select2;

$divRow = "<div class='row'>";
$divFechar = "</div>";

?>

<div class="aluno-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <?= $form->field($model, 'idiomaExameProf' , ['options' => ['class' => 'col-md-3']] )->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Idioma:</b>") ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'dataExameProf', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
                'language' => Yii::$app->language,
                'options' => ['placeholder' => 'Selecione a Data do Exame ...',],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true
                ]
            ])->label("<font color='#FF0000'>*</font> <b>Data do Exame:</b>")
            ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'conceitoExameProf', ['options' => ['class' => 'col-md-3']])->dropDownlist(['Aprovado' => 'Aprovado', 'Reprovado' => 'Reprovado'], ['prompt' => 'Selecione um Conceito...'])->label("<font color='#FF0000'>*</font> <b>Conceito Obtido:</b>"); ?>
        </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>