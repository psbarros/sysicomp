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
        <?= Html::a('<span class="fa fa-plus"></span> Nova Linha Pesquisa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            'sigla',
            [   'label' => 'Icone/Cor',
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
