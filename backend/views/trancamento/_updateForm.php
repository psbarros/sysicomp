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

    <?php $form = ActiveForm::begin([
        'options' => [ 'enctype' => 'multipart/form-data']
    ]); ?>
       <div class="row">
            <?= $form->field($model, 'dataSolicitacao0', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
                'language' => Yii::$app->language,
                'options' => ['placeholder' => 'Selecione uma data',],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true
                ]
            ])->label("<font color='#FF0000'>*</font> <b>Data de Solicitação:</b>")
            ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'dataInicio0', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
                'language' => Yii::$app->language,
                'options' => ['placeholder' => 'Selecione uma data',],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true
                ]
            ])->label("<font color='#FF0000'>*</font> <b>Data de Início:</b>")
            ?>
        </div>
        <div class="row">
            <?= $form->field($model, 'prevTermino0', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
                'language' => Yii::$app->language,
                'options' => ['placeholder' => 'Selecione uma data',],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true
                ]
            ])->label("<font color='#FF0000'>*</font> <b>Previsão de Término:</b>")
            ?>
        </div>
		<div class="row">
            <?= $form->field($model, 'justificativa' , ['options' => ['class' => 'col-md-3']] )->textArea(['rows' => '6'])->label("<font color='#FF0000'>*</font> <b>Justificativa:</b>") ?>
        </div>		
        <!--div class="row">
			<?= $form->field($model, 'documento', ['options' => ['class' => 'col-md-3'], ] )->fileInput()->label("<font color='#FF0000'>*</font> <b>Documento (PDF):</b>"); ?>
        </div-->
        <div class="row">
        <?= $form->field($model, 'status', ['options' => ['class' => 'col-md-3']])
                    ->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => 'Ativo',
                            'offText' => 'Encerrado'
                    ]]) 
        ?>

        <?= $form->field($model, 'tipo', ['options' => ['class' => 'col-md-3']])
                    ->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => 'Sim',
                            'offText' => 'Não'
                    ]])->label('Suspensão') 
        ?>
        </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>