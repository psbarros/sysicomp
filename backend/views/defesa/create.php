<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Defesa */

if ($tipodefesa == 1){
	$titulo = "Qualificação";
}
else if ($tipodefesa == 2){
	$titulo = "Qualificação 1";
}
else if ($tipodefesa == 3){
	$titulo = "Dissertação";
}
else if ($tipodefesa == 4){
	$titulo = "Qualificação 2";
}
else{
	$titulo = "Tese";
}


$this->title = 'Criar Defesa - '.$titulo. ' - de '.$model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Defesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="defesa-create">

	<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['aluno/view_orientado', 'id' => $_GET["aluno_id"] ], ['class' => 'btn btn-warning']) ?>    
	</p>

    <?= $this->render('_form', [
        'model' => $model,
        'membrosBancaInternos' => $membrosBancaInternos,
        'membrosBancaExternos' => $membrosBancaExternos,
        'membrosBancaSuplentes' => $membrosBancaSuplentes,
        'tipodefesa' => $tipodefesa,
    ]) ?>

</div>
