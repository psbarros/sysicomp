<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BancaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bancas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar', ['banca/index'], ['class' => 'btn btn-warning']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'banca_id',
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

            'template' => '{View} {Update} {Delete}',
            'buttons'  => [
                             'View'   => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['viewsemdefesa', 'banca_id' => $model->banca_id, 'membrosbanca_id'=> $model->membrosbanca_id ], [
                                   'title' => Yii::t('yii', 'Visualizar Detalhes'),
                                    ]); 
                              },
                            'Delete' => function ($url, $model){
                      return  Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'banca_id' => $model->banca_id , 'membrosbanca_id' => $model->membrosbanca_id], [
                                'data' => [
                                    'confirm' => 'Remover a defesa \''.$model->titulo.'\'?',
                                    'method' => 'post',
                                ], 'title' => Yii::t('yii', 'Remover Defesa'),
                        ]);
                    },
            


            

            ],
        ]
        ],
    ]); ?>
</div>
