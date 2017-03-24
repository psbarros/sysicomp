<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DescarteEquipamento */

$this->title = 'Descarte Equipamento';
$this->params['breadcrumbs'][] = ['label' => 'Descarte Equipamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="descarte-equipamento-create">

    <h1><?= Html::encode($item->NomeEquipamento) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    	'equipamento'=>$item,
    ]) ?>

</div>
