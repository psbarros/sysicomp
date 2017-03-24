<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);

$this->title = 'Salas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sala-index">

    <p><?= Html::a('<span class="fa fa-plus"></span> Nova Sala', ['sala/create'], ['class' => 'btn btn-success']) ?></p>
        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            'numero',
            'localizacao',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
