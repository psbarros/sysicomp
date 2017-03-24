<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Banca */

$this->title = 'Update Banca: ' . $model->banca_id;
$this->params['breadcrumbs'][] = ['label' => 'Bancas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->banca_id, 'url' => ['view', 'banca_id' => $model->banca_id, 'membrosbanca_id' => $model->membrosbanca_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banca-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
