<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjReceitas */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Dados da Receita</b></h3>
    </div>
    <div class="panel-body">
        <div class="cont-proj-receitas-form">

            <?php $form = ActiveForm::begin(); ?>

            <!--<?= $form->field($model, 'rubricasdeprojetos_id')->textInput() ?>-->
            <?= $form->field($model, 'rubricasdeprojetos_id')->dropDownList($rubricasdeProjeto, ['prompt' => 'Selecione uma rubrica']) ?>

            <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'valor_receita')->widget(\kartik\money\MaskMoney::classname(), [
                'pluginOptions' => [
                    'value' => 0.00,
                    'prefix' => 'R$ ',
                    'suffix' => '',
                    'allowNegative' => false
                ]
            ]); ?>

            <!--<?= $form->field($model, 'data')->textInput() ?>-->

            <?= $form->field($model, 'ordem_bancaria')->textInput(['maxlength' => true]) ?>

            <div class="row">
                <?= $form->field($model, 'data', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'language' => 'pt-BR',
                    'options' => ['placeholder' => 'Selecione a Data Inicial ...',],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label("<font color='#FF0000'>*</font> <b>Data:</b>")
                ?>
            </div>

            <div class="form-group">
                </br>
                <?= Html::submitButton($model->isNewRecord ? 'Cadastar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Cancelar', ['index', 'idProjeto' => $idProjeto,], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>