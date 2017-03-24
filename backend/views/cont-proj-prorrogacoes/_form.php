<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProrrogacoes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><b>Dados</b></h3>
    </div>
    <div class="panel-body">
        <div class="cont-proj-prorrogacoes-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'projeto_id')->hiddenInput(['value' => $idProjeto])->label(false) ?>

            <div class="row">
                <?= $form->field($model, 'data_fim_alterada', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'language' => 'pt-BR',
                    'options' => ['placeholder' => 'Selecione a Data Final Alterada ...',],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label("<font color='#FF0000'>*</font> <b>Nova Data Final</b>")
                ?>
            </div>

            <?= $form->field($model, 'descricao')->textarea(['rows' => 4,]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Cancelar', ['index', 'idProjeto' => $idProjeto,], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>