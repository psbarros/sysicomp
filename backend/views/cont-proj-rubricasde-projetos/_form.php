<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$idProjeto = Yii::$app->request->get('idProjeto');
/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Dados da Rubrica</b></h3>
    </div>
    <div class="panel-body">
        <div class="cont-proj-rubricasde-projetos-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'projeto_id')->hiddenInput(['value' => $idProjeto])->label(false) ?>

            <!--<?= $form->field($model, 'rubrica_id')->textInput() ?>-->

            <?php if (!isset($update)) {
                echo $form->field($model, 'rubrica_id')->dropDownList($rubricas, ['prompt' => 'Selecione uma rubrica']);
            } ?>

            <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'ordem_bancaria')->textInput(['maxlength' => true]) ?>

            <!--<?= $form->field($model, 'valor_total')->textInput() ?>-->

            <?= $form->field($model, 'valor_total')->widget(\kartik\money\MaskMoney::classname(), [
                'pluginOptions' => [
                    'value' => 0.00,
                    'prefix' => 'R$ ',
                    'suffix' => '',
                    'allowNegative' => false
                ]
            ]); ?>
            <!--<?= $form->field($model, 'valor_gasto')->textInput(['value' => "0"]) ?>-->

            <?php if (!isset($update)) {
                echo $form->field($model, 'valor_disponivel')->widget(\kartik\money\MaskMoney::classname(), [
                    'pluginOptions' => [
                        'value' => 0.00,
                        'prefix' => 'R$ ',
                        'suffix' => '',
                        'allowNegative' => false
                    ]
                ]);
            } ?>



            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Cancelar',['index', 'idProjeto'=>$idProjeto], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>