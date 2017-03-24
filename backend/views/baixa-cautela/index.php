<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BaixaCautelaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Baixa Cautelas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="baixa-cautela-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Baixa Cautela', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idBaixaCautela',
            'idCautela',
            //'idCautelaAvulsa',
            'Recebedor',
            'DataDevolucao',
            // 'Equipamento',
            // 'ObservacaoBaixaCautela',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
