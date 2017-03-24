<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjDespesas */
/* @var $form yii\widgets\ActiveForm */
$idProjeto = Yii::$app->request->get('idProjeto');
$tipos = ['Física' => 'Física', 'Jurídica' => 'Jurídica'];
$tiposNF = ['Cupom Fiscal' => 'Cupom Fiscal',
    'DARF' => 'DARF',
    'Invoice' => 'Invoice',
    'Nota Fiscal'=>'Nota Fiscal',
    'Nota de Empenho'=>'Nota de Empenho',
    'Recibo'=> 'Recibo',
    'Tarifa Bancaria'=>'Tarifa Bancaria'];
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Dados</b></h3>
    </div>
    <div class="panel-body">
        <div class="cont-proj-despesas-form">

            <?php $form = ActiveForm::begin(([
                'options' => ['enctype' => 'multipart/form-data']
            ])); ?>

            <?php
                //if (!isset($update)) {
                    echo $form->field($model, 'rubricasdeprojetos_id')->dropDownList($rubricasDeProjeto, ['prompt' => ' ']);
                //}
            ?>

            <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'quantidade')->textInput(['type' => 'number', 'value'=>1]) ?>

            <?= $form->field($model, 'valor_unitario')->widget(\kartik\money\MaskMoney::classname(), [
                'value'=>0.00,
                'pluginOptions' => [
                    'value'=>0.00,
                    'prefix' => 'R$ ',
                    'suffix' => '',
                    'allowNegative' => false
                ]
            ]); ?>

            <?= $form->field($model, 'tipo_pessoa')->dropDownList($tipos, ['prompt' => ' ']) ?>

            <!--<?= $form->field($model, 'data_emissao')->textInput() ?>-->

            <div class="row">
                <?= $form->field($model, 'data_emissao', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'language' => 'pt-BR',
                    'options' => ['placeholder' => 'Selecione a Data...',],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label("<font color='#FF0000'>*</font> <b>Data da emissão</b>")
                ?>
            </div>

            <?= $form->field($model, 'ident_nf')->dropDownList($tiposNF, ['prompt' => ' ']) ?>

            <?= $form->field($model, 'nf')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'ident_cheque')->textInput(['maxlength' => true]) ?>

            <!--<?= $form->field($model, 'data_emissao_cheque')->textInput() ?>-->

            <div class="row">
                <?= $form->field($model, 'data_emissao_cheque', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'language' => 'pt-BR',
                    'options' => ['placeholder' => 'Selecione a Data...',],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label("<font color='#FF0000'>*</font> <b>Data da emissão do cheque</b>")
                ?>
            </div>


            <?= $form->field($model, 'valor_cheque')->widget(\kartik\money\MaskMoney::classname(), [
                'pluginOptions' => [
                    'prefix' => 'R$ ',
                    'suffix' => '',
                    'allowNegative' => false
                ]
            ]); ?>

            <?= $form->field($model, 'favorecido')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'cnpj_cpf')->textInput(['maxlength' => true]) ?>

            <div class="row">
                <?= $form->field($model, 'comprovanteArquivo', ['options' => ['class' => 'col-md-6']])
                    ->fileInput(['accept' => '.pdf']) ?>
            </div>

            </br>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Cancelar', ['index', 'idProjeto' => $idProjeto,], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>