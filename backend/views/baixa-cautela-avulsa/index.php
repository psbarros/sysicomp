<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BaixaCautelaAvulsaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Baixa Cautela Avulsas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="baixa-cautela-avulsa-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Baixa Cautela Avulsa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idBaixaCautelaAvulsa',
            'idCautelaAvulsa',
            'Recebedor',
            'DataDevolucao',
            'Equipamento',
            // 'ObservacaoBaixaCautela',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
