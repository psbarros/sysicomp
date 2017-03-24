<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProrrogacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gerenciar Prorrogações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prorrogacao-index">

    <!--h1><?= Html::encode($this->title) ?></h1-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="fa fa-plus"></span> Nova Prorrogação', ['/aluno/prorrogacao'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idAluno',
            [
                'attribute' => 'matricula',
                'value' => function($model) {
                    return $model->aluno->matricula;
                }
            ],
            /*
            Uncomment the following lines to create a link to the view 'view' of the student
            [
                'attribute' => 'idAluno',
                'format' => 'html',
                'value' => function($model) {
                    return Html::a($model->aluno->nome, ['aluno/view', 
                                        'id' => $model->aluno->id], 
                                        [
                                            'title' => Yii::t('yii', 'Visualizar dados do aluno '.$model->aluno->nome),
                                        ]
                    );   
                }
            ],
            */
            [
                'attribute' => 'idAluno',
                'value' => function($model) {
                    return $model->aluno->nome;
                }
            ],
            /*
            Uncomment the following lines to create a link to the view 'view' of the advisor
            [
                'attribute' => 'orientador',
                'format' => 'html',
                'value' => function($model) {
                    return Html::a($model->orientador0->nome, ['user/view', 
                                        'id' => $model->orientador0->id], 
                                        [
                                            'title' => Yii::t('yii', 'Visualizar dados do orientador '.$model->orientador0->nome),
                                        ]
                    );   
                }
            ],
            */
            [
                'attribute' => 'orientador',
                'value' => function($model) {
                    return $model->orientador0->nome;
                }
            ],
            /*
            [
                'attribute' => 'dataSolicitacao',
                'value' => function($model) {
                    return date('d/m/Y', strtotime($model->dataSolicitacao));
                },
                //'headerOptions' => ['style' => 'width:15%'],
            ],
            */
            [
                'attribute' => 'dataInicio0',
                'value' => function($model) {
                    return date('d/m/Y', strtotime($model->dataInicio));
                },
            ],
            'qtdDias',
            /*
            [
                'attribute' => 'prevTermino',
                'value' => function($model) {
                    return date('d/m/Y', strtotime($model->prevTermino));
                },
            ],
            */
            /*
            [
                'attribute' => 'dataTermino',
                'value' => function($model) {
                    return date('d/m/Y', strtotime($model->dataTermino));
                },
            ],
            */
            [
                'attribute' => 'status',
                'filter'=>array (1 => 'Ativo', 0 => 'Encerrado'),
                'contentOptions' => function ($model){
                    return [
                            'style' => 'background-color: '.($model->status == 0 ? '#cc3300' : '#ccff66')
                           ];
                },
                'format' => 'html',
                'value' => function($model) {
                    if ($model->status == 0) return '<span class="glyphicon glyphicon-remove"></span> Encerrado';
                    return '<span class="glyphicon glyphicon-ok"></span> Ativo';
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template'=> '{download} {view} {update} {delete}',
                'buttons'=>
                [
                  'download' => function ($url, $model) { 
                    return Html::a('<span class="glyphicon glyphicon-download"></span>', $model->documento, [
                            'target' => '_blank',
                            'title' => Yii::t('yii', 'Download do Documento'),
                            'data-pjax'=> "0"
                        ]);
                  },
                ]                         
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
