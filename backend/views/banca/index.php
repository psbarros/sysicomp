<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BancaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Banca';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Criar Nova Banca' ,['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-ok"></span> Bancas sem Defesa ', ['banca/indexsemdefesa'], ['class' => 'btn btn-warning']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'banca_id',
            'titulo',
            'nome',
          /*  [
            'attribute' => 'funcao',
            'label' => "Funcao",
            'format' => "html",
            'value' => function ($model){
                if ($model->funcao === 'P'){
                    return "Presidente";
                }
                else if ($model->funcao === 'I') {
                    return "Membro Interno";
                }
                else if ($model->funcao === 'S') {
                    return "Suplente";
                }else{
                    return "Membro externo";
                }
            },
          ], */
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{View} {update} {delete}',
            'buttons'  => [
                             'View'   => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'banca_id' => $model->banca_id, 'membrosbanca_id'=> $model->membrosbanca_id ], [
                                   'title' => Yii::t('yii', 'Visualizar Detalhes'),
                                    ]);
                              },
                              'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'banca_id' => $model->banca_id], [
                                   'title' => Yii::t('yii', 'Editar Banca'),
                                    ]);
                              },
            ],
        ]
        ],
    ]); ?>
</div>
