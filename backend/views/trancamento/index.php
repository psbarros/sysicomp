<?php

/**
 * This is the view 'index' for 'Trancamento'
 * 
 * @author Pedro Frota <pvmf@icomp.ufam.edu.br>
 * 
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TrancamentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gerenciar Trancamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trancamento-index">

    <!--h1><?php /*Html::encode($this->title) */?></h1-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=  Html::a('<span class="fa fa-plus"></span> Novo Trancamento', ['/aluno/trancamento'], ['class' => 'btn btn-success']) ?>
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
            //'justificativa',
            // 'documento:ntext',
            [
                'attribute' => 'tipo',
                'filter'=>array (1 => 'Suspensão', 0 => 'Trancamento'),
                'contentOptions' => function ($model){
                    return [
                            'style' => 'background-color: '.($model->tipo == 0 ? '#9999e6' : '#f0b3ff')
                           ];
                },
                'format' => 'html',
                'value' => function($model) {
                    if ($model->tipo == 0) return '<span class="glyphicon glyphicon-lock"></span> Trancamento';
                    return '<span class="glyphicon glyphicon-hourglass"></span> Suspensão';
                },
            ],
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
              'template'=> '{download} {ativar} {encerrar} {view} {update} {delete}',
                'buttons'=>
                [
                  'download' => function ($url, $model) { 
                    return Html::a('<span class="glyphicon glyphicon-download"></span>', $model->documento, [
                            'target' => '_blank',
                            'title' => Yii::t('yii', 'Download do Documento'),
                            'data-pjax'=> "0"
                        ]);
                  },
                  'ativar' => function ($url, $model) { 
                              if ($model->status == 1) return false; //Disables button if status is active
                              return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['ativar', 'id' => $model->id], [
                                        'data' => [
                                            'confirm' => 'Ativar o trancamento? Essa ação apagará a data de encerramento atual!',
                                            'method' => 'post',
                                        ],
                                        'title' => Yii::t('yii', 'Ativar Trancamento'),
                    ]);   
                  },
                  'encerrar' => function ($url, $model) { 
                              if ($model->status == 0) return false; //Disables button if status is closed
                              return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['encerrar', 'id' => $model->id], [
                                        'data' => [
                                            'confirm' => 'Encerrar o trancamento?',
                                            'method' => 'post',
                                        ],
                                        'title' => Yii::t('yii', 'Encerrar Trancamento'),
                    ]);   
                  }
                ]                            
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
