<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Cautela;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\BaixaCautela */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="baixa-cautela-form">
 	<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['cautela/view', 'id'=>$model->idCautela], ['class' => 'btn btn-warning']) ?>
    <?php $form = ActiveForm::begin(); ?>
	<?php $cautela = Cautela::findOne($model->idCautela);?>
    

    <!--<?php //= $form->field($model, 'idCautelaAvulsa')->textInput() ?>-->
	<div class="panel panel-default" style="width:70%">
	<div class="panel-heading">
    	<h3 class="panel-title"><b>Dados Equipamento:</b></h3>
    </div>
    <div class="panel-body">    
	<div class="row">
	<?= $form->field($model, 'idCautela')->hiddenInput()->label(false)?>
	<?= $form->field($cautela, 'NomeResponsavel', ['options' => ['class' => 'col-md-4']])->textInput(['readOnly'=>true]) ?>
	</div>
	<div class="row">
    <?= $form->field($model, 'Recebedor', ['options' => ['class' => 'col-md-4']])->textInput(['maxlength' => true]) ?>
	</div>
	<div class="row">
    <?= $form->field($model, 'DataDevolucao', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
                    'language' => 'pt-BR',
                    'options' => ['placeholder' => 'Selecione a Data de Devolução ...',],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ]) ?>
	</div>
    <!--<?php //echo $form->field($model, 'Equipamento')->textInput(['maxlength' => true]) ?>-->
	<div class="row">
    <?= $form->field($model, 'ObservacaoBaixaCautela', ['options' => ['class' => 'col-md-4']])->textarea(['maxlength' => true]) ?>
    </div>
    </div>
	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Dar Baixa' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
