<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Disciplina;
use yii\jui\AutoComplete;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;
/* @var $this yii\backend\View */
/* @var $model backend\models\Aproveitamento */
/* @var $form yii\widgets\ActiveForm */
?>

<?php	
	$discOrigem = new Disciplina();
	$discDestino = new Disciplina();	
	if(isset($dOrigem)){
		$discOrigem->nome = $dOrigem->nome;
		$discOrigem->creditos = $dOrigem->creditos;
		$discOrigem->cargaHoraria = $dOrigem->cargaHoraria;
	}
	if(isset($dDestino)){
		$discDestino->nome = $dDestino->nome;
		$discDestino->creditos = $dDestino->creditos;
		$discDestino->cargaHoraria = $dDestino->cargaHoraria;
	}
?>

<div class="aproveitamento-form">
	

    <?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default" style="width:80%">
	<div class="panel-heading">
                <h3 class="panel-title"><b>Disciplina Origem:</b></h3>
            </div>
	  <div class="panel-body">

		<div class="row">
		    <?=     
		    $form->field($model, 'codDisciplinaOrigemFK',['options'=>['class'=>'col-md-3']])->widget(AutoComplete::classname(), [
		    				'clientOptions' => [
		    						'source' => URL::to(['disciplina/autocompletedisciplina']),
		    						'minLength'=>3,
		    						'select' => new JsExpression("function( event, ui ) {
								        //console.log(ui);
								        $('#disciplina-1-nome').val(ui.item.nome);
								        $('#disciplina-1-creditos').val(ui.item.creditos);
		    							$('#disciplina-1-cargahoraria').val(ui.item.cargaHoraria);
		    							$('#disciplina-1-instituicao').val(ui.item.instituicao);
		    							$('#disciplina-1-nomecurso').val(ui.item.nomeCurso);
		    							$('#disciplina-1-prerequisito').val(ui.item.preRequisito);
		    							$('#disciplina-1-obrigatoria').val(ui.item.obrigatoria);
								      }")
		    				],
		    				'options'=>[
		    						'maxLength'=>10,
		    						'style'=>[
		    								'width'=>'100px',
								    ],
		    				]
		    		])->label("<font color='#FF0000'>*</font> <b>Cód. Disciplina:</b>"); ?>
	</div>
	<div class="row">
	<?=	$form->field($discOrigem, '[1]nome',['options'=>['class'=>'col-md-5']])->textInput(['maxlenght'=>true])->label("<font color='#FF0000'>*</font> <b>Nome Disciplina:</b>"); ?>
	</div>

	<div class="row">
	<?=	$form->field($discOrigem, '[1]creditos',['options'=>['class'=>'col-md-2']])->textInput(['maxlength'=>true])->widget(MaskedInput::className(), [
                    'mask' => '99', 'clientOptions'=>['alias'=>'numeric']])->label("<font color='#FF0000'>*</font> <b>Créditos:</b>"); ?>
	<?=	$form->field($discOrigem, '[1]cargaHoraria',['options'=>['class'=>'col-md-3']])->widget(MaskedInput::className(), [
                    'mask' => '999', 'clientOptions'=>['alias'=>'numeric']])->textInput(['maxlength'=>true])->label("<font color='#FF0000'>*</font> <b>Carga Horária (H):</b>"); ?>
	</div>
	<div class="row">
	 <?= $form->field($discOrigem, '[1]instituicao', ['options'=>['class'=>'col-md-5']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Instituição:</b>"); ?>
	</div>
	
	<div class="row">
	 <?= $form->field($discOrigem, '[1]nomeCurso', ['options'=>['class'=>'col-md-5']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Nome Curso:</b>"); ?>
	</div>

    <div class="row">
    <?= $form->field($discOrigem, '[1]obrigatoria', ['options'=>['class'=>'col-md-2']])->dropDownList( [1=>"SIM", 0=>"NÃO"])->label("<font color='#FF0000'>*</font> <b>Obrigatória:</b>"); ?>
    
    <?= $form->field($discOrigem, '[1]preRequisito', ['options'=>['class'=>'col-md-3']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Pre-Requisito:</b>"); ?>
    </div>
	
   </div>
  </div>
  <div class="panel panel-default" style="width:80%">
	<div class="panel-heading">
                <h3 class="panel-title"><b>Disciplina Destino:</b></h3>
            </div>
	  <div class="panel-body">
	<div class="row">
	<?=     
    $form->field($model, 'codDisciplinaDestinoFK',['options'=>['class'=>'col-md-4']])->widget(AutoComplete::classname(), [
    				'clientOptions' => [
    						'source' => URL::to(['disciplina/autocompletedisciplina']),
    						'minLength'=>3,
    						'select' => new JsExpression("function( event, ui ) {
								        //console.log(ui);
								        $('#disciplina-2-nome').val(ui.item.nome);
								        $('#disciplina-2-creditos').val(ui.item.creditos);
		    							$('#disciplina-2-cargahoraria').val(ui.item.cargaHoraria);
    									$('#disciplina-2-instituicao').val(ui.item.instituicao);
		    							$('#disciplina-2-nomecurso').val(ui.item.nomeCurso);
		    							$('#disciplina-2-prerequisito').val(ui.item.preRequisito);
		    							$('#disciplina-2-obrigatoria').val(ui.item.obrigatoria);
								      }")
    				],
    				'options'=>[
    						'maxLength'=>10,
    						'style'=>[
		    						 'width'=>'100px',
								    ],
    				]
    		])->label("<font color='#FF0000'>*</font> <b>Cód. Disciplina Destino:</b>"); ?>
	</div>
	<div class="row">
	<?= $form->field($discDestino, '[2]nome', ['options'=>['class'=>'col-md-5']])->textInput(['maxlength'=>true])->label("<font color='#FF0000'>*</font> <b>Nome Disciplina:</b>")?>
	</div>
	<div class="row">
	<?= $form->field($discDestino, '[2]creditos', ['options'=>['class'=>'col-md-2']])->textInput()->widget(MaskedInput::className(), [
                    'mask' => '99', 'clientOptions'=>['alias'=>'numeric']])->label("<font color='#FF0000'>*</font> <b>Créditos:</b>") ?>
	<?= $form->field($discDestino, '[2]cargaHoraria', ['options'=>['class'=>'col-md-3']])->textInput()->widget(MaskedInput::className(), [
                    'mask' => '999', 'clientOptions'=>['alias'=>'numeric']])->label("<font color='#FF0000'>*</font> <b>Carga Horária (H):</b>") ?>
	</div>
	
	<div class="row">
	 <?= $form->field($discOrigem, '[2]instituicao', ['options'=>['class'=>'col-md-5']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Instituição:</b>"); ?>
	</div>
	
	<div class="row">
	 <?= $form->field($discOrigem, '[2]nomeCurso', ['options'=>['class'=>'col-md-5']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Nome Curso:</b>"); ?>
	</div>	
	
	<div class="row">
    <?= $form->field($discOrigem, '[2]obrigatoria', ['options'=>['class'=>'col-md-2']])->dropDownList( [1=>"SIM", 0=>"NÃO"])->label("<font color='#FF0000'>*</font> <b>Obrigatória:</b>"); ?>
    
    <?= $form->field($discOrigem, '[2]preRequisito', ['options'=>['class'=>'col-md-3']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Pre-Requisito:</b>"); ?>
    </div>
	
	</div>
	</div>
	<div class="panel panel-default" style="width:80%">
		<div class="panel-heading">
                <h3 class="panel-title"><b>Dados:</b></h3>
            </div>
	  <div class="panel-body">
	<div class="row">
    <?= $form->field($model, 'nota', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->nota)],'options'=>['class'=>'col-md-2']])->textInput()->widget(MaskedInput::className(), [
    		'mask' => '99.99', 'clientOptions'=>['alias'=>'numeric']])->label("<font color='#FF0000'>*</font> <b>Nota:</b>"); ?>
    <?= $form->field($model, 'frequencia', ['options'=>['class'=>'col-md-2']])->textInput()->widget(MaskedInput::className(), [
                    'mask' => '999.99', 'clientOptions'=>['alias'=>'numeric']])->label("<font color='#FF0000'>*</font> <b>Frequência (%)</b>"); ?>

    <?= $form->field($model, 'situacao', ['options'=>['class'=>'col-md-4']])->textInput(['maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Situação:</b>"); ?>
	</div>
	<div class="row">
    <?= $form->field($model, 'idAluno')->hiddenInput(['value'=>$model->idAluno])->label(false) ?>
	</div>
	<div class="row">
    <?= $form->field($model, 'id')->hiddenInput(['value'=>$model->id])->label(false) ?>
	</div>
  </div>
  </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Salvar' : 'Alterar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
