<?php

use yii\helpers\Html;
use yii\grid\GridView;
use xj\bootbox\BootboxAsset;

BootboxAsset::register($this);
BootboxAsset::registerWithOverride($this);


$this->title = 'Linhas de Pesquisa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linha-pesquisa-index">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>&nbsp;&nbsp;Voltar','#',['class' => 'btn btn-warning','onclick'=>"history.go(-1);"]); ?>
        <?= Html::a('<span class="fa fa-plus"></span> Nova Linha Pesquisa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="row col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Linhas de Pesquisas Existentes</b></h3>
            </div>
            <div class="panel-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'nome',
                        'sigla',
                        [   'label' => 'Ícone/Cor',
                            'attribute' => 'cor',
                            'contentOptions' => function ($model){
                                return ['style' => 'background-color: '.$model->cor];
                            },
                            'format' => 'html',
                            'value' => function ($model){
                                return "<span class='fa ". $model->icone ." fa-lg'/> ";
                            }
                        ],
                        ['class' => 'yii\grid\ActionColumn',
                          'template'=>'{view} {delete} {update}',
                            'buttons'=>[
                              'delete' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                                        'data' => [
                                            'confirm' => 'Remover o linha de pesquisa \''.$model->nome.'\'?',
                                            'method' => 'post',
                                        ],
                                        'title' => Yii::t('yii', 'Remover Edital'),
                                ]);
                              }
                          ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
