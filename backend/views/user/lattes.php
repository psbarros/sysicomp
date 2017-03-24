<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Upload Currículo Lattes';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><b>Currículo Lattes</b></h3>
		</div>
		<div class="panel-body">
			<?= $form->field($model, 'lattesFile', ['options' => ['class' => 'col-md-6']])->fileInput(['accept' => '.xml'])->label("<div><b>Curriculum Lattes em XML:</b></div>") ?>
			<?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

<?php ActiveForm::end() ?>