<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CautelaAvulsa */

$this->title = 'Cautela Avulsa: ' . $model->idCautelaAvulsa . ' - ' . $model->NomeResponsavel;
$this->params['breadcrumbs'][] = ['label' => 'Cautela Avulsas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idCautelaAvulsa, 'url' => ['view', 'idCautelaAvulsa' => $model->idCautelaAvulsa, 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cautela-avulsa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
