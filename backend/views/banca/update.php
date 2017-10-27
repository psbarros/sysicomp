<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Banca */

$this->title = 'Editar Banca ';
$this->params['breadcrumbs'][] = ['label' => 'Bancas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->banca_id, 'url' => ['view','banca_id' => $model->banca_id, 'membrosbanca_id' => $model->membrosbanca_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banca-update">



    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>
