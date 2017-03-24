<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Banca */

$this->title = $model->banca_id;
$this->params['breadcrumbs'][] = ['label' => 'Bancas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'banca_id' => $model->banca_id, 'membrosbanca_id' => $model->membrosbanca_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'banca_id' => $model->banca_id, 'membrosbanca_id' => $model->membrosbanca_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'banca_id',
            'membrosbanca_id',
            'funcao:ntext',
            'passagem',
        ],
    ]) ?>

</div>
