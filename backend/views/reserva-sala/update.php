<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReservaSala */

$this->title = 'Alterar Reserva: ' . $model->atividade;
$this->params['breadcrumbs'][] = ['label' => 'Reserva Salas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->salaDesc->nome, 'url' => ['calendario', 'idSala' => $model->sala]];
$this->params['breadcrumbs'][] = ['label' => $model->atividade, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Alterar';
?>
<div class="reserva-sala-update">
	<p><?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['view', 'id' => $model->id], ['class' => 'btn btn-warning']) ?></p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
