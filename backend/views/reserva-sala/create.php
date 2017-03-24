<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ReservaSala */

$this->title = 'Criar Reserva de Sala';
$this->params['breadcrumbs'][] = ['label' => 'Reserva Salas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reserva-sala-create">

<p><?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['reserva-sala/calendario', 'idSala' => $model->sala], ['class' => 'btn btn-warning']) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
