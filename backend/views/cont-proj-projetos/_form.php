<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProjetos */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Dados da Receita</b></h3>
    </div>
    <div class="panel-body">
        <div class="cont-proj-projetos-form">


            <?php $form = ActiveForm::begin(([
                'options' => ['enctype' => 'multipart/form-data']
            ])); ?>

            <?= $form->field($model, 'nomeprojeto')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'orcamento')->widget(\kartik\money\MaskMoney::classname(), [
                'pluginOptions' => [
                    'prefix' => 'R$ ',
                    'suffix' => '',
                    'allowNegative' => false
                ]
            ]); ?>

            <!--<?= $form->field($model, 'saldo')->textInput() ?>-->

            <!--<?= $form->field($model, 'data_inicio')->textInput() ?>-->

            <div class="row">
                <?= $form->field($model, 'data_inicio', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'language' => 'pt-BR',
                    'options' => ['placeholder' => 'Selecione a Data Inicial ...',],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label("<font color='#FF0000'>*</font> <b>Data Inicial:</b>")
                ?>
            </div>

            <!--<?= $form->field($model, 'data_fim')->textInput() ?>-->

            <div class="row">
                <?= $form->field($model, 'data_fim', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'attribute' => 'date_fim',
                    'language' => 'pt-BR',
                    'options' => ['placeholder' => 'Selecione a Data Final ...',],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label("<font color='#FF0000'>*</font> <b>Data Final:</b>")
                ?>
            </div>

            <!--<?= $form->field($model, 'data_fim_alterada')->textInput() ?>-->

            <!--<div class="row">
        <?= $form->field($model, 'data_fim_alterada', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                'language' => 'pt-BR',
                'options' => ['placeholder' => 'Selecione a Data Final Alterada ...',],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true
                ]
            ])->label("<font color='#FF0000'>*</font> <b>Data Final Alterada:</b>")
            ?>
    </div>-->

            <?= $form->field($model, 'coordenador_id')->dropDownList($coordenadores, ['prompt' => 'Selecione um Coordenador']) ?>

            <?= $form->field($model, 'agencia_id')->dropDownList($agencias, ['prompt' => 'Selecione uma Agência de Fomento']) ?>

            <?= $form->field($model, 'banco_id')->dropDownList($bancos, ['prompt' => 'Selecione um Banco']) ?>

            <?= $form->field($model, 'agencia')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'conta')->textInput(['maxlength' => true]) ?>

            <!--<?= $form->field($model, 'edital')->textInput(['maxlength' => true]) ?>-->
            <!--<?= $form->field($model, 'proposta')->textInput(['maxlength' => true]) ?>-->

            <?= $form->field($model, 'editalArquivo', ['options' => ['class' => 'col-md-6']])->fileInput(['accept' => '.pdf'])->label("Edital do Projeto: ") ?>


            <?= $form->field($model, 'propostaArquivo', ['options' => ['class' => 'col-md-6']])->fileInput(['accept' => '.pdf'])->label("Proposta do Projeto: ") ?>

            <!--<?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>-->

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>