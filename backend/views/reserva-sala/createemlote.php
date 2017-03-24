<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sala */

$this->title = 'Reserva em  Lote';
$this->params['breadcrumbs'][] = ['label' => 'Reserva de Salas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sala-create">
	<p>
		<?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['index'], ['class' => 'btn btn-warning']) ?>   
	</p>
	
    <?= $this->render('_formEmLote', [
        'model' => $model,
        'arraySalas' => $arraySalas,
    ]) ?>

</div>
