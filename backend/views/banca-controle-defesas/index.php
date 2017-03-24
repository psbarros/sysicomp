<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BancaControleDefesasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista de Banca de Defesa ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-controle-defesas-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Banca Controle Defesas', ['create'], ['class' => 'btn btn-success']) 
        ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
            'attribute' => 'status_banca',
            'label' => "Status",
            'format' => "html",
            'value' => function ($model){
                if ($model->status_banca === 1){
                    return "Aprovado";
                }
                else if ($model->status_banca === 0) {
                    return "Reprovado";
                }
                else{
                    return "Não Avaliado";
                }
            },
            ],
            'aluno_nome',
            'cursoAluno',
            'linhaSigla',

            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{view}',
                ],
        ],
    ]); ?>
</div>
