<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\bootstrap\Button;
use kartik\select2\Select2;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;

$this->registerJs("

    var curso_aluno = ".$aluno->curso.";

    if (curso_aluno == 1) {
        $('#bloco-local').css('display','block');
        $('#bloco-banca').css('display','block');
        $('#bloco-examinador').css('display','none');
    } else {
        $('#bloco-local').css('display','none');
        $('#bloco-banca').css('display','none');
        $('#bloco-examinador').css('display','block');
        $('#defesa-tipodefesa').change(function() {
            if ($(this).val() >= 4) {
                $('#bloco-local').css('display','block');
                $('#bloco-banca').css('display','block');
                $('#bloco-examinador').css('display','none');
            } else {
                $('#bloco-local').css('display','none');
                $('#bloco-banca').css('display','none');
                $('#bloco-examinador').css('display','block');
            }
        });
    }
");

?>

<?php $form = ActiveForm::begin(); ?>

<div class="defesa-form">

<div class="panel panel-default" style="width:80%">

    <div class="panel-heading">
        <h3 class="panel-title"><b>Dados sobre a Defesa</b></h3>
    </div>

    <div class="panel-body">
        <div class="row">

            <?= $form->field($model, 'tipoDefesa', ['options' => ['class' => 'col-md-8']])->dropDownList($defesastipos,  ['options' => $defesas_aluno_array]]) ?>

            <?= $form->field($model, 'titulo', ['options' => ['class' => 'col-md-8']])->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'data', ['options' => ['class' => 'col-md-8']])->widget(DatePicker::classname(), [
                'language' => Yii::$app->language,
                'options' => ['placeholder' => 'Selecione a Data da Defesa ...',],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true
                ]
            ])->label("<font color='#FF0000'>*</font> <b>Data da Defesa: </b>")
            ?>

            <?= $form->field($model, 'resumo',['options' => ['class' => 'col-md-8']])->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'previa',['options' => ['class' => 'col-md-8']])->FileInput(['accept' => '.pdf'])->label("Prévia (PDF)"); ?>

            <div id="bloco-examinador">
        		<?= $form->field($model, 'examinador',['options' => ['class' => 'col-md-8']])->textInput(['maxlength' => true,]) ?>
        		<?= $form->field($model, 'emailExaminador',['options' => ['class' => 'col-md-8']])->textInput(['maxlength' => true]) ?>
            </div>

            <?= $form->field($model, 'horario',['options' => ['class' => 'col-md-8']])->widget(DateControl::classname(), [
                'language' => 'pt-BR',
                'name'=>'kartik-date',
                'value'=>time(),
                'type'=>DateControl::FORMAT_TIME,
                'displayFormat' => 'php: H:i',
            ]) ?>


            <div id="bloco-local">
                <?= $form->field($model, 'local',['options' => ['class' => 'col-md-8']])->textInput(['maxlength' => true]) ?>
            </div>

            <div id="bloco-banca">
                <?= $form->field($model, 'presidente',['options' => ['class' => 'col-md-8']])->widget(Select2::classname(), [
                    'data' => $membrosBancaInternos,
                    'value' => $model->membrosBancaInternos,
                    'language' => 'pt-BR',
                    'options' => [
                    'placeholder' => 'Selecione um presidente ...', 'multiple' => false,],
                ]); ?>

                <?= $form->field($model, 'membrosBancaInternos',['options' => ['class' => 'col-md-8']])->widget(Select2::classname(), [
                    'data' => $membrosBancaInternos,
                    'value' => $model->membrosBancaInternos,
                    'language' => 'pt-BR',
                    'options' => [
                    'placeholder' => 'Selecione os membros internos ...', 'multiple' => true,],
                ]); ?>

                <?= $form->field($model, 'membrosBancaExternos',['options' => ['class' => 'col-md-8']])->widget(Select2::classname(), [
                    'data' => $membrosBancaExternos,
                    'value' => $model->membrosBancaExternos,
                    'language' => 'pt-BR',
                    'options' => [
                    'id' => 'idsMembrosBancaInternos',
                    'placeholder' => 'Selecione os membros externos ...', 'multiple' => true,],
                ]); ?>

                <?= $form->field($model, 'membrosBancaSuplentes',['options' => ['class' => 'col-md-8']])->widget(Select2::classname(), [
                    'data' => $membrosBancaSuplentes,
                    'value' => $model->membrosBancaSuplentes,
                    'language' => 'pt-BR',
                    'options' => [
                    'placeholder' => 'Selecione os membros suplentes ...', 'multiple' => true,],
                ]); ?>
            </div>

            <br><br>
            <div class="form-group col-md-8">
                <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Salvar Alterações', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

        </div> <!-- row -->
    </div> <!-- panel-body -->
</div> <!-- panel -->

</div>

<?php ActiveForm::end(); ?>
