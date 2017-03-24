<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CautelaAvulsa */

if ($model->StatusCautelaAvulsa == 1){
	$titulo = "Em aberto";
}
else if ($model->StatusCautelaAvulsa == 2){
	$titulo = "Concluída";
}
else if ($model->StatusCautelaAvulsa == 3){
	$titulo = "Em atraso";
}

$this->title = 'Cautela Avulsa';
$this->params['breadcrumbs'][] = ['label' => 'Cautela Avulsas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cautela-avulsa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
