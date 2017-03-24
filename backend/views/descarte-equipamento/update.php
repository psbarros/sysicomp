<?php

use yii\helpers\Html;

//teste

/* @var $this yii\web\View */
/* @var $model app\models\DescarteEquipamento */

$this->title = 'Update Descarte Equipamento: ' . $model->idDescarte;
$this->params['breadcrumbs'][] = ['label' => 'Descarte Equipamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idDescarte, 'url' => ['view', 'id' => $model->idDescarte]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="descarte-equipamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
