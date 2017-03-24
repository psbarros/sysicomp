<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BaixaCautela */

$this->title = 'Update Baixa Cautela: ' . $model->idBaixaCautela;
$this->params['breadcrumbs'][] = ['label' => 'Baixa Cautelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idBaixaCautela, 'url' => ['view', 'id' => $model->idBaixaCautela]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="baixa-cautela-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
