<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AgendarDefesaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agendar Defesas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agendar-defesa-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Agendar Defesa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idDefesa',
            'titulo',
            'tipoDefesa',
            'data',
            'conceito',
            // 'horario',
            // 'local',
            // 'resumo:ntext',
            // 'numDefesa',
            // 'examinador:ntext',
            // 'emailExaminador:ntext',
            // 'reservas_id',
            // 'banca_id',
            // 'aluno_id',
            // 'previa',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
