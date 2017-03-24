<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricas */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Alteração Orçamentária</b></h3>
    </div>
    <div class="panel-body">
        <div class="cont-proj-transferencias-saldo-rubricas-form" xmlns="http://www.w3.org/1999/html">

            <?php $form = ActiveForm::begin(); ?>

            <!--<?= $form->field($model, 'projeto_id')->textInput() ?>-->

            <?= $form->field($model, 'projeto_id')->hiddenInput(['value' => $idProjeto])->label(false) ?>

            <?= $form->field($model, 'rubrica_origem')->dropDownList($rubricas, ['prompt' => 'Selecione uma rubrica']); ?>

            <?= $form->field($model, 'rubrica_destino')->dropDownList($rubricas, ['prompt' => 'Selecione uma rubrica']); ?>

            <!--<?= $form->field($model, 'valor')->textInput() ?>-->

            <div class="row">
                <?= $form->field($model, 'data', ['options' => ['class' => 'col-md-4']])->widget(\kartik\date\DatePicker::classname(), [
                    'language' => 'pt-BR',
                    'options' => ['placeholder' => 'Selecione a data da operação...',],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label("<font color='#FF0000'>*</font> <b>Data da Operação:</b>")
                ?>
            </div>


            <?= $form->field($model, 'valor')->widget(\kartik\money\MaskMoney::classname(), [
                'value' => 0.00,
                'pluginOptions' => [
                    'value' => 0.00,
                    'prefix' => 'R$ ',
                    'suffix' => '',
                    'allowNegative' => false
                ]
            ]); ?>


            <!--<?= $form->field($model, 'data')->textInput() ?>-->


            <?= $form->field($model, 'autorizacao')->textInput(['maxlength' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Cancelar', ['cont-proj-transferencias-saldo-rubricas/cancelar', 'idProjeto' => $idProjeto], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>