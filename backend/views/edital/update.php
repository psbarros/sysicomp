<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Edital */

$this->title = 'Alterar Edital: ' . ' ' . $model->numero;
$this->params['breadcrumbs'][] = ['label' => 'Editais', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->numero, 'url' => ['view', 'id' => $model->numero]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="edital-update">
	<p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ', ['edital/index', 'id' => $model->numero], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
        'read' => true,
    ]) ?>

</div>
