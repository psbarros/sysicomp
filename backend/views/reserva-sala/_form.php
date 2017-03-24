<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;


$tipos = ['Aula' => 'Aula', 'Defesa' => 'Defesa', 'Exame' => 'Exame', 'Reunião' => 'Reunião'];

$horarios = ["" => "", "07:29" => "07:29", "07:59" => "07:59", "08:29" => "08:29", "08:59" => "08:59", "09:29" => "09:29", "09:59" => "09:59", "10:29" => "10:29", 
            "10:59" => "10:59", "11:29" => "11:29", "11:59" => "11:59", "12:29" => "12:29", "12:59" => "12:59", "13:29" => "13:29", "13:59" => "13:59",
            "14:29" => "14:29", "14:59" => "14:59", "15:29" => "15:29", "15:59" => "15:59", "16:29" => "16:29", "16:59" => "16:59", "17:29" => "17:29", 
            "17:59" => "17:59", "18:29" => "18:29", "18:59" => "18:59", "19:29" => "19:29", "19:59" => "19:59", "20:29" => "20:29", "20:59" => "20:59",
            "21:29" => "21:29", "21:59" => "21:59", "22:29" => "22:29", "22:59" => "22:59"];

?>

<div class="reserva-sala-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?= $form->field($model, 'atividade', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Atividade:</b>") ?>
    </div>
     <div class="row">
        <?= $form->field($model, 'tipo', ['options' => ['class' => 'col-md-4']])->dropDownList($tipos, ['prompt' => 'Selecione um tipo'])->label("<font color='#FF0000'>*</font> <b>Tipo:</b>") ?>
    </div>
    <div class="row">

        <?= $form->field($model, 'horaInicio', ['options' => ['class' => 'col-md-3']])->widget(DateControl::classname(), [
            'language' => 'pt-BR',
            'name'=>'kartik-date',
	     'options' => [
                'pluginOptions' => [
                    'minuteStep' => 30,
                ],
            ],
            'value' => date(''),
            'type'=>DateControl::FORMAT_TIME,
            'displayFormat' => 'php: H:i',
        ])->label("<font color='#FF0000'>*</font> <b>Hora de Início:</b>") ?>

        <?= $form->field($model, 'horaTermino', ['options' => ['class' => 'col-md-3']])->dropDownList($horarios)->label("<font color='#FF0000'>*</font> <b>Hora de Término  :</b>") ?>

    </div>
    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
