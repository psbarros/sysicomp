<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\bootstrap\Button;
use kartik\select2\Select2;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;

//tipodefesa = 2 representa o curso de doutorado e defesa na qualificacao 1
if ($tipodefesa == 2){
    $required = 0;
}
else {
    $required = 1;
}

?>

<input type="hidden" id = "membrosObrigatorios" value = <?php echo $required; ?> />

<div class="defesa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data', ['options' => []])->widget(DatePicker::classname(), [
        'language' => Yii::$app->language,
        'options' => ['placeholder' => 'Selecione a Data da Defesa ...',],
        'pluginOptions' => [
            'format' => 'dd-mm-yyyy',
            'todayHighlight' => true
        ]
    ])->label("<font color='#FF0000'>*</font> <b>Data da Defesa: </b>")
?>


    <?= $form->field($model, 'resumo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'previa')->FileInput(['accept' => '.pdf'])->label("Prévia (PDF)"); ?>

    <?php if ($tipodefesa == 2){ ?>

		<?= $form->field($model, 'examinador')->textInput(['maxlength' => true,]) ?>

		<?= $form->field($model, 'emailExaminador')->textInput(['maxlength' => true]) ?>

    <?php } 
	
	else { ?>

    
    <?= $form->field($model, 'horario')->widget(DateControl::classname(), [
    'language' => 'pt-BR',
    'name'=>'kartik-date', 
    'value'=>time(),
    'type'=>DateControl::FORMAT_TIME,
    'displayFormat' => 'php: H:i',
    ]) ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'presidente')->widget(Select2::classname(), [
                'data' => $membrosBancaInternos,
                'value' => $model->membrosBancaInternos,
                'language' => 'pt-BR',
                'options' => [
                'placeholder' => 'Selecione um presidente ...', 'multiple' => false,],
            ]);

            ?>

            <?= $form->field($model, 'membrosBancaInternos')->widget(Select2::classname(), [
                'data' => $membrosBancaInternos,
                'value' => $model->membrosBancaInternos,
                'language' => 'pt-BR',
                'options' => [
                'placeholder' => 'Selecione os membros internos ...', 'multiple' => true,],
            ]);

            ?>

            <?= $form->field($model, 'membrosBancaExternos')->widget(Select2::classname(), [
                'data' => $membrosBancaExternos,
                'value' => $model->membrosBancaExternos,
                'language' => 'pt-BR',
                'options' => [
                'id' => 'idsMembrosBancaInternos',
                'placeholder' => 'Selecione os membros externos ...', 'multiple' => true,],
            ]);

            ?>

            <?= $form->field($model, 'membrosBancaSuplentes')->widget(Select2::classname(), [
                'data' => $membrosBancaSuplentes,
                'value' => $model->membrosBancaSuplentes,
                'language' => 'pt-BR',
                'options' => [
                'placeholder' => 'Selecione os membros suplentes ...', 'multiple' => true,],
            ]);

            ?>

    <?php } ?>

<br><br>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Salvar Alterações', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
