<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRegistraDatas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-registra-datas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'evento')->textInput(['maxlength' => true]) ?>

    <!--<?= $form->field($model, 'data')->textInput() ?>-->

    <div class="row">
        <?= $form->field($model, 'data', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
            'language' => Yii::$app->language,
            'options' => ['placeholder' => 'Selecione uma Data ...',],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ])->label("<font color='#FF0000'>*</font> <b>Data :</b>")
        ?>
    </div>

    <!--<?= $form->field($model, 'projeto_id')->dropDownList($projetos, ['prompt' => 'Selecione um Projeto']) ?>-->

    <?= $form->field($model, 'projeto_id')->hiddenInput(['value' => $idProjeto])->label(false) ?>

    <?= $form->field($model, 'observacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Registrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Cancelar',['index','idProjeto'=>$idProjeto], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
